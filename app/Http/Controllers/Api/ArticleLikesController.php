<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\ArticleLike;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Kitano\ApiRepo\ArticleLikesRepo;

class ArticleLikesController extends ApiController
{
    /** @var \App\Kitano\ApiRepo\ArticleLikesRepo */
    protected $repo;


    /**
     * @param \Illuminate\Http\Request             $request
     * @param \App\Kitano\ApiRepo\ArticleLikesRepo $repo
     */
    public function __construct(Request $request, ArticleLikesRepo $repo)
    {
        parent::__construct($request);

        $this->repo = $repo;
    }

    /**
     * Like an article
     * Requires authentication
     *
     * @param \App\Article $article
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Article $article)
    {
        if (! auth()->check()) {
            return $this->respondUnauthorized();
        }

        $user = auth()->user();

        // better safe than sorry
        // check if article is already liked
        $userLikes = $article->likes()
                             ->where('user_id', $user->id)
                             ->where('article_id', $article->id)
                             ->count();

        if ($userLikes) {
            return $this->respondUnprocessable('Article already liked');
        }

        $al = new ArticleLike();

        $al->user_id = $user->id;
        $al->article_id = $article->id;

        $this->repo->save($al);

        return $this->respondCreated('Article Like');
    }
}
