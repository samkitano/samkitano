<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class UsersController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('User');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.users.show')
               ->with('user', $this->getUserWithComments($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->articleLikes()->delete();
        $user->commentLikes()->delete();
        $user->comments()->delete();
        $user->delete();

        $this->forgetQuery('users');

        return redirect()
               ->route('admin::users.index')
               ->with($this->successDestroyStatus($id));
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
