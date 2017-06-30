<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class TagsController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('Tag');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tags.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate($this->request, $this->rules());

        $tag = strtolower($this->request->input('name'));

        $record = Tag::create(['name' => $tag]);

        $this->forgetQuery('tags');

        return redirect()->route('admin::tags.index')
                         ->with($this->successSaveStatus($record->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.tags.show')
               ->with('tag', $this->getTagWithArticles($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.tags.edit')
               ->with('tag', $this->getTagWithArticles($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->rules());

        $tag = strtolower($this->request->input('name'));

        $record = Tag::findOrFail($id);
        $record->name = $tag;
        $record->save();

        $this->forgetQuery('tags');

        return redirect()->route('admin::tags.index')
                         ->with($this->successUpdateStatus($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Tag::findOrFail($id)->load('articles')->articles->count()) {
            return back()
                 ->with($this->failRelationDestroyStatus($id));
        }

        Tag::destroy($id);

        $this->forgetQuery('tags');

        return redirect()->route('admin::tags.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    protected function getTagWithArticles($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Tag::findOrFail($id)
                          ->load('articles');
            }
        );
    }

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required|alpha|min:2|max:20|unique:tags,name',
        ];
    }
}
