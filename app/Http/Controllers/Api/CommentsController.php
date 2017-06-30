<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Comment;
use Illuminate\Http\Request;
use App\Kitano\ApiRepo\CommentRepo;
use App\Http\Controllers\ApiController;
use App\Kitano\Transformers\CommentTransformer;

class CommentsController extends ApiController
{
    /** @var \App\Kitano\ApiRepo\CommentRepo */
    protected $repo;

    /** @var \App\Kitano\Transformers\CommentTransformer */
    protected $transformer;


    /**
     * @param \Illuminate\Http\Request                    $request
     * @param \App\Kitano\ApiRepo\CommentRepo             $repo
     * @param \App\Kitano\Transformers\CommentTransformer $transformer
     */
    public function __construct(Request $request, CommentRepo $repo, CommentTransformer $transformer)
    {
        parent::__construct($request);

        $this->repo = $repo;
        $this->transformer = $transformer;
    }

    /**
     * @param \App\Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Article $article)
    {
        $comments = $this->getAllEloquent($article);

        if (! $comments) {
            $this->respondNotFound(ApiController::COMMENTS_NOT_FOUND);
        }

        $transformedComments = $this->getAllTransformed($article, $comments);

        return $this->respondOk(['comments' => $transformedComments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Article $article
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Article $article)
    {
        $userID = $this->request->user()->id;
        $articleID = $article->id;

        $newComment = new Comment();

        $newComment->user_id = $userID;
        $newComment->article_id = $articleID;
        $newComment->body = $this->request->input('body');

        $saved = $this->repo->save($newComment);
        $newID = $saved->id;

        $this->forgetQuery('comments');

        $allEloquent = $this->getAllEloquent($article);
        $comment = collect($this->getAllTransformed($article, $allEloquent))
                       ->where('id', $newID)
                       ->values()[0];

        return $this->respondOk(['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Article $article
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Article $article, Comment $comment)
    {
        $comment->body = $this->request->input('body');
        $comment->save();
        $this->forgetQuery('comments');

        return $this->respondNoContent(ApiController::COMMENT_UPDATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Article $article
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Article $article, Comment $comment)
    {
        $comment->likes()->delete();
        $comment->delete();

        $this->forgetQuery('comments');

        return $this->respondNoContent(ApiController::COMMENT_DELETED);
    }

    /**
     * @param \App\Article $article
     * @param              $comments
     *
     * @return mixed
     */
    protected function getAllTransformed(Article $article, $comments)
    {
        return $this->transformer->transform($comments, $article->id, auth()->check());
    }

    /**
     * @param \App\Article $article
     *
     * @return bool|mixed
     */
    protected function getAllEloquent(Article $article)
    {
        return $this->repo->all($article);
    }
}
