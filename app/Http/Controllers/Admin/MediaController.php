<?php

namespace App\Http\Controllers\Admin;

use App\Media;
use Illuminate\Http\Request;
use App\Kitano\MediaManager\Manager;
use App\Http\Controllers\AdminController;

class MediaController extends AdminController
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
        parent::setResource('Medium');

        $this->mediaManager = $manager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate($this->request, $this->rules());
        $created = $this->mediaManager->createMedium();

        if (! $created) {
            return back()->with($this->failUploadStatus($this->request->name));
        }

        $this->forgetQuery('media');

        return redirect()->route('admin::media.index')
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
        return view('admin.media.show')->with('media', Media::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.media.edit')->with('media', Media::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * 'description' is the only editable attribute
     * 'order' will have own method
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->updateRules());
        $this->mediaManager->updateMedium($id);
        $this->forgetQuery('media');

        return redirect()->route('admin::media.index')
                         ->with($this->successUpdateStatus($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = $this->mediaManager->destroyMedium($id);

        $this->forgetQuery('media');

        if ($destroyed !== true) {
            return back()->with($this->failFileUnlinkStatus($id));
        }

        return redirect()->route('admin::media.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'album_id' => [
                'required',
                'integer',
                'exists:albums,id',
            ],
            'description' => [
                'required',
                'string',
                'max:255',
            ],
            'media' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
            ]
        ];
    }

    /**
     * @return array
     */
    protected function updateRules()
    {
        return [
            'description' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
