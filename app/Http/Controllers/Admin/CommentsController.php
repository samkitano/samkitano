<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class CommentsController extends AdminController
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        parent::setResource('Comment');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.comments.index');
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
        return view('admin.comments.show')
            ->with('comment', $this->getCommentWithUserAndArticleAndLikes($id));
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
        return view('admin.comments.edit')
            ->with('comment', $this->getCommentWithUserAndArticleAndLikes($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate($this->request, $this->rules());

        $comment = Comment::findOrFail($id);

        $comment->body = $this->request->input('body');
        $comment->save();

        $this->forgetQuery('comments');

        return redirect()->route('admin::comments.index')
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
        $comment = Comment::findOrFail($id);

        $comment->likes()->delete();
        $comment->delete();

        $this->forgetQuery('comments');

        return redirect()->route('admin::comments.index')
                         ->with($this->successDestroyStatus($id));
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

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'body' => 'min:5',
        ];
    }
}
