<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\AdminController;

class ArticleCommentsController extends AdminController
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
        return view('admin.articles.comments.index')
               ->with('article', $this->getArticleWithComments($id));
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    protected function getArticleWithComments($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Article::findOrFail($id)
                              ->load(['comments']);
            }
        );
    }
}
