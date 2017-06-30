<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kitano\Traits\Cacheable;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminController extends BaseController
{
    use AuthorizesRequests, Cacheable, DispatchesJobs, ValidatesRequests;

    /** @var array */
    protected $status = [];

    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var string */
    protected $resource;


    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->status = [
            'title' => trans('general.success'),
            'type' => 'success',
            'message' => '',
        ];
    }

    /**
     * Failed download
     *
     * @param   string $reason
     *
     * @return array
     */
    public function failDownloadStatus($reason)
    {
        return $this->setType('error')
                    ->setTitle(trans('general.error'))
                    ->setMessage(trans('general.f_download', ['reason' => $reason]))
                    ->mountStatus();
    }

    /**
     * Failed destroy
     *
     * @param   int $id
     *
     * @return array
     */
    public function failFileDestroyStatus($id)
    {
        return $this->setType('error')
                    ->setTitle(trans('general.error'))
                    ->setMessage(trans('general.hasFiles', ['resource' => "{$this->resource} #" . $id]))
                    ->mountStatus();
    }

    /**
     * Failed Unlink
     *
     * @param   string $file
     *
     * @return array
     */
    public function failFileUnlinkStatus($file)
    {
        return $this->setType('error')
                    ->setTitle(trans('general.error'))
                    ->setMessage(trans('general.f_unlink', ['file' => $file]))
                    ->mountStatus();
    }

    /**
     * Failed destroy because of existent relationship
     *
     * @param   int $id
     *
     * @return array
     */
    public function failRelationDestroyStatus($id)
    {
        return $this->setType('error')
                    ->setTitle(trans('general.error'))
                    ->setMessage(trans('general.r_is_related', ['resource' => "{$this->resource} #" . $id]))
                    ->mountStatus();
    }

    /**
     * Failed renaming file
     *
     * @param   string $file
     *
     * @return array
     */
    public function failRenameFile($file)
    {
        return $this->setType('error')
                    ->setTitle(trans('general.error'))
                    ->setMessage(trans('general.f_rename', ['file' => $file]))
                    ->mountStatus();
    }

    /**
     * Failed Upload
     *
     * @param   int $id
     *
     * @return array
     */
    public function failUploadStatus($id)
    {
        return $this->setType('error')
                    ->setTitle(trans('general.error'))
                    ->setMessage(trans('general.f_upload', ['resource' => "{$this->resource} #" . $id]))
                    ->mountStatus();
    }

    /**
     * Successful destroy
     *
     * @param   int $id
     *
     * @return array
     */
    public function successDestroyStatus($id)
    {
        return $this->setMessage(trans('general.r_deleted', ['resource' => "{$this->resource} #" . $id]))
                    ->mountStatus();
    }

    /**
     * Sucessful renaming
     *
     * @param   string $old Old file name
     * @param   string $new New file name
     *
     * @return array
     */
    public function successRenameFile($old, $new)
    {
        return $this->setMessage(trans('general.r_renamed', ['resource' => "{$this->resource} " . $old,
                                                             'new' => $new]))
                    ->mountStatus();
    }

    /**
     * Successful saving
     *
     * @param   int $id
     *
     * @return array
     */
    public function successSaveStatus($id)
    {
        return $this->setMessage(trans('general.r_saved', ['resource' => "{$this->resource} #" . $id]))
                    ->mountStatus();
    }

    /**
     * Successful update
     *
     * @param   int $id
     *
     * @return array
     */
    public function successUpdateStatus($id)
    {
        return $this->setMessage(trans('general.r_updated', ['resource' => "{$this->resource} #" . $id]))
                    ->mountStatus();
    }

    /**
     * Successful unlinking
     *
     * @return array
     */
    public function successUnlinkStatus()
    {
        return $this->setMessage(trans('general.r_unlinked'))
                    ->mountStatus();
    }

    /**
     * @return array
     */
    protected function mountStatus()
    {
        return ['status' => $this->status];
    }

    /**
     * @param   string $message
     *
     * @return $this
     */
    protected function setMessage($message)
    {
        $this->status['message'] = $message;

        return $this;
    }

    /**
     * @param   string $resource
     */
    protected function setResource($resource)
    {
        $this->status = [
            'title' => trans('general.success'),
            'type' => 'success',
            'message' => '',
        ];

        $this->resource = $resource;
    }

    /**
     * @param   string $title
     *
     * @return $this
     */
    protected function setTitle($title)
    {
        $this->status['title'] = $title;

        return $this;
    }

    /**
     * @param   string $type
     *
     * @return $this
     */
    protected function setType($type)
    {
        $this->status['type'] = $type;

        return $this;
    }
}
