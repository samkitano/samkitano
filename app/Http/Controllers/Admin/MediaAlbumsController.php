<?php

namespace App\Http\Controllers\Admin;

use App\Media;
use App\Http\Controllers\AdminController;

class MediaAlbumsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('admin.media.albums.index')
            ->with('album', $this->getMediaWithAlbum($id));
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    protected function getMediaWithAlbum($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Media::findOrFail($id)
                            ->load(['album']);
            }
        );
    }
}
