<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\AdminController;

class CommentsUserController extends AdminController
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
        return redirect()
               ->route('admin::users.show', $this->getCommentUserId($id));
    }

    /**
     * @param   int $id
     *
     * @return mixed
     */
    protected function getCommentUserId($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Comment::findOrFail($id)->user_id;
            }
        );
    }
}
