<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\AdminController;

class CommentsLikesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param   int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('admin.comments.likes.index')
               ->with('comment', $this->getCommentWithUserAndArticleAndLikes($id));
    }

    /**
     * @param   int $id
     *
     * @return mixed
     */
    protected function getCommentWithUserAndArticleAndLikes($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Comment::findOrFail($id)
                              ->load(['user', 'article', 'likes']);
            }
        );
    }
}
