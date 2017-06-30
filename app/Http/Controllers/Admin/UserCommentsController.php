<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\AdminController;

class UserCommentsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id User id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('admin.users.comments.index')
               ->with('user', $this->getUserWithComments($id));
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    protected function getUserWithComments($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return User::findOrFail($id)
                           ->load('comments');
            }
        );
    }
}
