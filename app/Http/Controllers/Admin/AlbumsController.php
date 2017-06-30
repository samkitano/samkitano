<?php

namespace App\Http\Controllers\Admin;

use App\Album;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Kitano\MediaManager\Manager;
use App\Http\Controllers\AdminController;

class AlbumsController extends AdminController
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
        parent::setResource('Album');

        $this->mediaManager = $manager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.albums.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate($this->request, $this->rules($this->request));

        $created = $this->mediaManager->createAlbum();

        $this->forgetQuery('albums');

        return redirect()->route('admin::albums.index')
                         ->with($this->successSaveStatus($created->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.albums.show')->with(
            'album',
            Album::with([
                'media' =>
                    function ($q) {
                        $q->orderBy('order', 'ASC')->get();
                    }
            ])->findOrFail($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.albums.edit')->with(
            'album',
            Album::with([
                'media' =>
                function ($q) {
                    $q->orderBy('order', 'ASC')->get();
                }
            ])->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->rules($this->request));
        $this->mediaManager->updateAlbum($id);
        $this->forgetQuery('albums');

        return redirect()->route('admin::albums.index')
                         ->with($this->successUpdateStatus($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = $this->mediaManager->destroyAlbum($id);

        if ($destroyed !== true) {
            return back()->with($this->failFileDestroyStatus($id));
        }

        $this->forgetQuery('albums');

        return redirect()->route('admin::albums.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * @param $id
     *
     * @return array
     */
    protected function rules($id)
    {
        return [
            'name' => [
                'required',
                'alpha',
                'min:1',
                'max:20',
                Rule::unique('albums')->ignore($id),
            ],
        ];
    }
}
