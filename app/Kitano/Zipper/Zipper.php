<?php

namespace App\Kitano\Zipper;

use ZipArchive;
use App\Kitano\Zipper\Exceptions\ZipperException;

class Zipper
{
    /**
     * @throws \App\Kitano\Zipper\Exceptions\ZipperException
     */
    public function __construct()
    {
        if (! extension_loaded('zip')) {
            throw new ZipperException('Zip extension is not installed.');
        }
    }

    /**
     * Compress a file into a Zip Archive
     *
     * @param string $file          File to Compress
     * @param string $destination   Destination Folder
     *
     * @return bool
     */
    public function compress($file, $destination = 'downloads')
    {
        if (! file_exists($file)) {
            return false;
        }

        $dirName  = public_path($destination);

        if (! file_exists($dirName)) {
            mkdir($dirName, 0777);
        }

        $name = basename($file);
        $zipName = $name . '.zip';
        $zip = new ZipArchive();
        $zipOp = ZIPARCHIVE::CREATE;
        $destFile = $destination . DIRECTORY_SEPARATOR . $zipName;

        if (file_exists($destFile)) {
            $zipOp = ZIPARCHIVE::OVERWRITE;
        }

        if ($zip->open($dirName . DIRECTORY_SEPARATOR . $zipName, $zipOp) !== true) {
            return false;
        }

        $zip->addFile($file, $name);
        $zip->close();

        return file_exists($destFile);
    }
}
