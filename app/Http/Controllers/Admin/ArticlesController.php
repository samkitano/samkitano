<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class ArticlesController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('Article');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.articles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate($this->request, $this->rules());

        $fields = array_except($this->request->input(), ['_token', '_method', 'tags']);
        $tags = $this->request->input('tags');
        $fields['published'] = is_null($this->request->input('published')) ? 0 : 1;
        $article = Article::create($fields);

        if (! is_null($tags)) {
            $article->tags()->sync($tags);
        }

        $this->forgetQuery('articles');
        $this->forgetQuery('getLatestArticles');

        return redirect()->route('admin::articles.index')
                         ->with($this->successSaveStatus($article->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.articles.show')
            ->with('article', $this->getArticleWithTagsAndCommentsAndLikes($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.articles.edit')
            ->with('article', $this->getArticleWithTagsAndCommentsAndLikes($id))
            ->with('tags', $this->getAllTags());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->rules());

        $article = Article::find($id);
        $fields = array_except($this->request->input(), ['_token', '_method', 'tags']);
        $fields['published'] = $this->request->has('published') ? '1' : 0;
        $tags = $this->request->input('tags');

        if (is_null($tags)) {
            $article->tags()->detach();
        } else {
            $article->tags()->sync($tags);
        }

        $article->update($fields);

        $this->forgetQuery('articles');
        $this->forgetQuery('getLatestArticles');

        return redirect()->route('admin::articles.edit', $id)
                         ->with($this->successUpdateStatus($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->tags()->detach();
        $article->comments()->delete();
        $article->likes()->delete();
        $article->delete();

        $this->forgetQuery('articles');

        return redirect()->route('admin::articles.index')
                         ->with($this->successDestroyStatus($id));
    }

    /**
     * @return mixed
     */
    protected function getAllTags()
    {
        return $this->remember(
            __FUNCTION__,
            function () {
                return Tag::all(['id', 'name']);
            }
        );
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    protected function getArticleWithTagsAndCommentsAndLikes($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Article::find($id)
                              ->load(['tags', 'comments', 'likes']);
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
            'title' => 'required|min:5',
            'subtitle' => 'required|min:5',
            'body' => 'required|min:5',
        ];
    }
}
