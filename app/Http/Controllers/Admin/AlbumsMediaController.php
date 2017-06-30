<?php

namespace App\Http\Controllers\Admin;

use App\Album;
use Illuminate\Http\Request;
use App\Kitano\MediaManager\Manager;
use App\Http\Controllers\AdminController;

class AlbumsMediaController extends AdminController
{
    /** @var \App\Kitano\MediaManager\Manager */
    protected $mediaManager;


    /**
     * @param \App\Kitano\MediaManager\Manager $manager
     * @param \Illuminate\Http\Request         $request
     */
    public function __construct(Manager $manager, Request $request)
    {
        parent::__construct($request);

        $this->mediaManager = $manager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('admin.albums.media.index')
            ->with('album', $this->getAlbumWithMedia($id));
    }

    /**
     * Reorder medium
     *
     * @param int $album
     * @param int $medium
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moveUp($album, $medium)
    {
        $moved = $this->mediaManager->moveOrderUp($album, $medium);

        if (! $moved) {
            return redirect()->back();
        }

        $this->forgetQuery('media');

        return redirect()->route('admin::albums.edit', $album);
    }

    /**
     * Reorder Medium
     *
     * @param int $album
     * @param int $medium
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moveDown($album, $medium)
    {
        $moved = $this->mediaManager->moveOrderDown($album, $medium);

        if (! $moved) {
            return redirect()->back();
        }

        $this->forgetQuery('media');

        return redirect()->route('admin::albums.edit', $album);
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    protected function getAlbumWithMedia($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Album::findOrFail($id)
                              ->load(['media']);
            }
        );
    }
}
