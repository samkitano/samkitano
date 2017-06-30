<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\AdminController;

class ArticleTagsController extends AdminController
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('admin.articles.tags.index')
               ->with('article', $this->getArticle($id));
    }

    /**
     * @param  int $id
     *
     * @return mixed
     */
    protected function getArticle($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Article::findOrFail($id);
            }
        );
    }
}
