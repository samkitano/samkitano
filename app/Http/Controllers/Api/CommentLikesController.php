<?php

namespace App\Http\Controllers\Api;

use App\CommentLike;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Kitano\ApiRepo\CommentLikesRepo;

class CommentLikesController extends ApiController
{
    /** @var \App\Kitano\ApiRepo\CommentLikesRepo */
    protected $repo;


    /**
     * @param \Illuminate\Http\Request             $request
     * @param \App\Kitano\ApiRepo\CommentLikesRepo $repo
     */
    public function __construct(Request $request, CommentLikesRepo $repo)
    {
        parent::__construct($request);

        $this->repo = $repo;
    }

    /**
     * @param $article
     * @param $commentId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($article, $commentId)
    {
        $user = $this->request->user();

        // better safe than sorry
        // check if comment is already liked
        $userLikes = CommentLike::where('user_id', $user->id)
                                ->where('comment_id', $commentId)
                                ->count();

        if ($userLikes) {
            return $this->respondUnprocessable('Comment already liked');
        }

        $cl = new CommentLike();

        $cl->user_id = $user->id;
        $cl->comment_id = $commentId;

        $this->repo->save($cl);

        return $this->respondCreated('Comment Like');
    }
}
