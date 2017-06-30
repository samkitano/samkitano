<?php

namespace App\Http\Controllers\Admin;

use App\Statics;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

class StaticsController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('Page');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.statics.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.statics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate($this->request, $this->rules());
        $page = Statics::create($this->request->all());

        $this->forgetQuery('statics');

        return redirect()->route('admin::statics.index')
                         ->with($this->successSaveStatus($page->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.statics.show')->with('article', Statics::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.statics.edit')->with('article', Statics::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->rules($id));

        $page = Statics::findOrFail($id);
        $page->update($this->request->all());

        $this->forgetQuery('statics');

        return redirect()->route('admin::statics.edit', $id)
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
        Statics::destroy($id);

        $this->forgetQuery('statics');

        return redirect()->route('admin::statics.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * @param null $id
     *
     * @return array
     */
    protected function rules($id = null)
    {
        return [
            'slug' => [
                'required',
                'alpha',
                'min:3',
                'max:255',
                Rule::unique('statics')->ignore($id),
            ],
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|max:255',
            'body' => 'required'
        ];
    }
}
