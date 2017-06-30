<?php

namespace App\Kitano\MediaManager;

use App\Album;
use App\Media;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Kitano\MediaManager\FileTypes as FileType;

class Manager
{
    const SIZE_LARGE = 400;
    const SIZE_MEDIUM = 200;
    const SIZE_SMALL = 50;
    const ORIENTATION_LANDSCAPE = 'landscape';
    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_SQUARE = 'square';

    /** @var string */
    protected $album;

    /** @var string */
    protected $albumPath;

    /** @var array|\Illuminate\Http\UploadedFile|null */
    protected $file;

    /** @var string */
    protected $fileType;

    /** @var string */
    protected $finalName;

    /** @var string */
    protected $guessedExtension;

    /** @var int */
    protected $height;

    /** @var \Intervention\Image\Facades\Image */
    protected $image;

    /** @var string */
    protected $imagePath;

    /** @var string */
    protected $name;

    /** @var string */
    protected $orientation;

    /** @var string */
    protected $originalExtension;

    /** @var string */
    protected $originalName;

    /** @var string */
    protected $realExtension;

    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var int */
    protected $size;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $onDb;

    /** @var string */
    protected $thumbsPath;

    /** @var int */
    protected $width;

    /**
     * Where should I store the media files?
     *
     * @var string Media Base Folder
     */
    public static $mediaFolder = 'media';


    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->file = $request->file('media');
    }

    /**
     * Create a Media Album
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAlbum()
    {
        $album = $this->request->input('name');

        $this->makeFolder($album);

        return Album::create($this->request->all());
    }

    /**
     * Create a media item
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createMedium()
    {
        if (! $this->request->file('media')->isValid()) {
            return false;
        }

        $album = Album::find($this->request->input('album_id'))->name;

        $this->albumPath = public_path(static::$mediaFolder . "/{$album}");

        $this->setAlbum($album)
             ->setFileProperties()
             ->upload()
             ->processThumbs()
             ->mergeProperties();

        $media = Media::create($this->request->all());

        return $media;
    }

    /**
     * Destroy an album, both from DB and Storage
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroyAlbum($id)
    {
        $name = Album::find($id)->name;
        $folder_path = public_path(static::$mediaFolder . "/{$name}");

        if (dirHasFiles($folder_path) || ! $this->deleteFolder($folder_path)) {
            return false;
        }

        Album::destroy($id);

        return true;
    }

    /**
     * Delete a media item from both DB and Storage
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroyMedium($id)
    {
        $medium = Media::find($id)->load(['album']);
        $album = $medium->album->name;
        $files = [static::$mediaFolder . DIRECTORY_SEPARATOR . $album . DIRECTORY_SEPARATOR . $medium->file_name];
        $type = $medium->type;
        $thumbs = [];

        $this->setAlbum($album);

        if ($type === FileType::TYPE_IMAGE) {
            $thumbs = $this->getThumbs($medium->file_name);
        }

        if (count($thumbs)) {
            $files = array_merge($files, $thumbs);
        }

        foreach ($files as $f) {
            $removed = $this->remove($f);

            if (! $removed) {
                return $f;
            }
        }

        $medium->destroy($id);

        return true;
    }

    /**
     * Get a list of orphan files
     *
     * @return array
     */
    public function getOrphans()
    {
        $this->setOnDb($this->getDbData());

        $filesOnDisk = $this->getListFromDisk(false);

        if (! count($filesOnDisk)) {
            return [];
        }

        $filesOnDb = $this->getListFromDb();

        if (! count($filesOnDb)) {
            return $filesOnDisk;
        }

        return array_diff(
            $filesOnDisk,
            $filesOnDb
        );
    }

    /**
     * Move medium order down
     *
     * @param int $albumId
     * @param int $mediumId
     *
     * @return bool
     */
    public function moveOrderDown($albumId, $mediumId)
    {
        $media = Media::find($mediumId);
        $order = $media->order;
        $mediaCount = Media::whereAlbumId($albumId)->count();

        if ($order >= $mediaCount) {
            return false;
        }

        $nxtorder = $order + 1;
        $next = Media::whereAlbumId($albumId)
                     ->whereOrder($nxtorder)
                     ->first();

        $media->increment('order');
        $next->update(['order' => $order]);

        return true;
    }

    /**
     * Move medium order up
     *
     * @param int $albumId
     * @param int $mediumId
     *
     * @return bool
     */
    public function moveOrderUp($albumId, $mediumId)
    {
        $media = Media::find($mediumId);
        $order = $media->order;

        if ($order == 0) {
            return false;
        }

        $prevorder = $order - 1;
        $previous = Media::whereAlbumId($albumId)
                         ->whereOrder($prevorder)
                         ->first();

        $media->decrement('order');
        $previous->update(['order' => $order]);

        return true;
    }

    /**
     * Remove Orphan Files
     *
     * @return bool
     */
    public function unlinkOrphans()
    {
        $orphans = $this->getOrphans();

        foreach ($orphans as $orphan) {
            $removed = $this->remove($orphan);

            if (! $removed) {
                return $orphan;
            }
        }

        return true;
    }

    /**
     * Update an album
     *
     * @param int $id
     *
     * @return void
     */
    public function updateAlbum($id)
    {
        $album = Album::find($id);
        $old = $album->name;
        $album->update($this->request->all());
        $new = $this->request->input('name');
        $this->renameAlbum($old, $new);
    }

    /**
     * Updates a media item.
     * Only Description can be updated!
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function updateMedium($id)
    {
        $media = Media::find($id);

        $media->description = $this->request->input('description');
        $media->save();

        return $media;
    }

    /**
     * @return $this
     */
    protected function createLargeThumb()
    {
        $img = $this->image;

        $img->widen(static::SIZE_LARGE);
        $img->save($this->thumbsPath . DIRECTORY_SEPARATOR . 'large_' . $this->getFinalName());

        return $this;
    }

    /**
     * @return $this
     */
    protected function createMediumThumb()
    {
        $img = $this->image;

        $img->widen(static::SIZE_MEDIUM);
        $img->save($this->thumbsPath . DIRECTORY_SEPARATOR . 'medium_' . $this->getFinalName());

        return $this;
    }

    /**
     * @return $this
     */
    protected function createSmallThumb()
    {
        $img = $this->image;

        $img->widen(static::SIZE_SMALL);
        $img->save($this->thumbsPath . DIRECTORY_SEPARATOR . 'small_' . $this->getFinalName());

        return $this;
    }

    /**
     * @return $this
     */
    protected function createThumbs()
    {
        $this->makeImage()
             ->createLargeThumb()
             ->createMediumThumb()
             ->createSmallThumb()
             ->destroyImage();

        return $this;
    }

    /**
     * @param string $dir Full path to dir
     *
     * @return bool
     */
    protected function deleteFolder($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));

        foreach ($files as $file) {
            is_dir("{$dir}/{$file}") ? $this->deleteFolder("{$dir}/{$file}") : unlink("{$dir}/{$file}");
        }

        return rmdir($dir);
    }

    /**
     * @return $this
     */
    protected function destroyImage()
    {
        $this->image->destroy();

        return $this;
    }

    /**
     * Greatest Common Divisor
     *
     * @param int $y
     * @param int $z
     *
     * @return mixed
     */
    protected function gcd($y, $z)
    {
        return ($y % $z) ? $this->gcd($z, $y % $z) : $z;
    }

    /**
     * @return int
     */
    protected function getCurrentOrder()
    {
        return (int) Media::whereAlbumId($this->request->album_id)->count();
    }

    /**
     * @return array
     */
    protected function getDbData()
    {
        return Media::all()->load('album');
    }

    /**
     * @return array
     */
    protected function getListFromDb()
    {
        $files = [];

        foreach ($this->onDb as $onDb) {
            $type = $onDb->type;
            $fileName = $onDb->file_name;
            $album = $onDb->album->name;

            $this->setAlbum($album);

            $files[] = static::$mediaFolder . DIRECTORY_SEPARATOR . $album . DIRECTORY_SEPARATOR . $fileName;

            if ($type !== FileType::TYPE_IMAGE) {
                continue;
            }

            $thumbs = $this->getThumbs($fileName);

            if (count($thumbs)) {
                $files = array_merge($files, $thumbs);
            }
        }

        return $files;
    }

    /**
     * @param bool $info Return Files Info Object (SplFileInfo)
     *
     * @return array
     */
    protected function getListFromDisk($info = true)
    {
        $itFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(static::$mediaFolder));
        $files = [];

        foreach ($itFiles as $file) {
            $fn = $file->getFilename();

            if ($fn !== '.' && $fn !== '..') {
                $files[] = $info ? $file->getPathInfo() : $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * @return mixed
     */
    protected function getFinalName()
    {
        return $this->finalName;
    }

    /**
     * @return string
     */
    protected function getOrientation()
    {
        if ($this->width > $this->height) {
            return static::ORIENTATION_LANDSCAPE;
        }

        if ($this->width < $this->height) {
            return static::ORIENTATION_PORTRAIT;
        }

        return static::ORIENTATION_SQUARE;
    }

    /**
     * Aspect Ratio for width and height
     *
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    protected function getRatio($width, $height)
    {
        $gcd = $this->gcd($width, $height);

        return ($width / $gcd) . ':' . ($height / $gcd);
    }

    /**
     * @return mixed
     */
    protected function getStoredFileNames()
    {
        return $this->onDb->pluck('file_name');
    }

    /**
     * @return mixed
     */
    protected function getType()
    {
        return $this->fileType;
    }

    /**
     * @param string $file
     *
     * @return array
     */
    protected function getThumbs($file)
    {
        $thumbs = [];
        $path = static::$mediaFolder . DIRECTORY_SEPARATOR . $this->album . DIRECTORY_SEPARATOR . "thumbs";
        $t1 = $path . DIRECTORY_SEPARATOR . 'small_' . $file;
        $t2 = $path . DIRECTORY_SEPARATOR . 'medium_' . $file;
        $t3 = $path . DIRECTORY_SEPARATOR . 'large_' . $file;

        if (file_exists(public_path($t1))) {
            $thumbs[] = $t1;
        }

        if (file_exists(public_path($t2))) {
            $thumbs[] = $t2;
        }

        if (file_exists(public_path($t3))) {
            $thumbs[] = $t3;
        }

        return $thumbs;
    }

    /**
     * @param string $name New folder name
     *
     * @return void
     */
    protected function makeFolder($name)
    {
        if (! file_exists(public_path(static::$mediaFolder . "/{$name}"))) {
            mkdir(public_path(static::$mediaFolder . "/{$name}"), 0777, true);
        }
    }

    /**
     * @return $this
     */
    protected function makeImage()
    {
        $this->image = Image::make($this->imagePath);

        return $this;
    }

    /**
     * @return $this
     */
    protected function makeThumbFolder()
    {
        if (! file_exists($this->thumbsPath)) {
            mkdir($this->thumbsPath, 0777);
        }

        return $this;
    }

    /**
     * @return void
     */
    protected function mergeProperties()
    {
        $newProperties = [
            'height' => $this->height,
            'orientation' => $this->orientation,
            'width' => $this->width,
            'size' => $this->size,
            'name' => $this->originalName,
            'file_name' => $this->getFinalName(),
            'order' => $this->getCurrentOrder(),
            'type' => $this->getType(),
            'ratio' => $this->getRatio($this->width, $this->height),
        ];

        $this->request->merge($newProperties);
    }

    /**
     * @return $this
     */
    protected function processThumbs()
    {
        if (! $this->fileType == FileType::TYPE_IMAGE) {
            return $this;
        }

        $this->makeThumbFolder()
             ->createThumbs();

        return $this;
    }

    /**
     * @param string $medium File to delete (full path)
     *
     * @return bool
     */
    protected function remove($medium)
    {
        $file = public_path($medium);

        if (file_exists($file)) {
            unlink($file);
        }

        return ! file_exists($file);
    }

    /**
     * @param string $old Old folder name
     * @param string $new New folder name
     *
     * @return void
     */
    protected function renameAlbum($old, $new)
    {
        rename(
            public_path(static::$mediaFolder . "/{$old}"),
            public_path(static::$mediaFolder . "/{$new}")
        );
    }

    /**
     * @param string $album
     *
     * @return $this
     */
    protected function setAlbum($album)
    {
        $this->album = $album;
        
        return $this;
    }

    /**
     * @return $this
     */
    protected function setFileProperties()
    {
        $g = $this->file->guessExtension();
        $this->guessedExtension =  $g == 'jpeg' ? 'jpg' : $g;
        $this->fileType = $this->setFileType();
        $this->finalName = $this->setFinalName();
        $this->originalExtension = strtolower($this->file->getClientOriginalExtension());
        $this->originalName = $this->file->getClientOriginalName();
        $this->thumbsPath = $this->albumPath . DIRECTORY_SEPARATOR . 'thumbs';
        $this->size = $this->file->getClientSize();

        return $this;
    }

    /**
     * @return string
     */
    protected function setFileType()
    {
        if (in_array($this->guessedExtension, ['mp3', 'wav', 'ogg'])) {
            return FileType::TYPE_AUDIO;
        }

        if (in_array($this->guessedExtension, ['gif', 'jpeg', 'jpg', 'png'])) {
            return FileType::TYPE_IMAGE;
        }

        if (in_array($this->guessedExtension, ['mp4', 'mov', 'webm'])) {
            return FileType::TYPE_VIDEO;
        }

        if (in_array($this->guessedExtension, ['txt'])) {
            return FileType::TYPE_TEXT;
        }

        if (in_array($this->guessedExtension, ['zip', 'rar', 'tar', 'gz', '7z'])) {
            return FileType::TYPE_ARCHIVE;
        }

        if ($this->guessedExtension == 'pdf') {
            return FileType::TYPE_PDF;
        }

        if ($this->guessedExtension == 'svg') {
            return FileType::TYPE_SVG;
        }

        return FileType::TYPE_OTHER;
    }

    /**
     * @return string
     */
    protected function setFinalName()
    {
        return str_slug(time() . '_' . microtime()) . '.' . $this->guessedExtension;
    }

    /**
     * @return void
     */
    protected function setImageInfo()
    {
        $this->imagePath = $this->albumPath . DIRECTORY_SEPARATOR . $this->getFinalName();
        $img = Image::make($this->imagePath);
        $this->width = $img->width();
        $this->height = $img->height();
        $img->destroy();
        $this->orientation = $this->getOrientation();
    }

    /**
     * @param $onDb
     *
     * @return $this
     */
    protected function setOnDb($onDb)
    {
        $this->onDb = $onDb;
        
        return $this;
    }

    /**
     * @return void
     */
    protected function setUploaded()
    {
        $this->imagePath = $this->albumPath . DIRECTORY_SEPARATOR . $this->getFinalName();
        $this->setImageInfo();
    }

    /**
     * @return $this
     */
    protected function upload()
    {
        $this->file->move($this->albumPath, $this->getFinalName());

        $this->setUploaded();

        return $this;
    }
}
