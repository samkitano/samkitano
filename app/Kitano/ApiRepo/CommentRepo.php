<?php

namespace App\Kitano\ApiRepo;

use App\Comment;

class CommentRepo extends ApiRepo
{
    public function __construct()
    {
        $this->setModel(app(Comment::class));
    }

    /**
     * All comments in given article
     *
     * @param \App\Article $article
     *
     * @return bool|mixed
     */
    public function all($article)
    {
        if (! $this->hasItems()) {
            return false;
        }

        return $this->remember(
            "ELOQUENT_COMMENTS_{$article->slug}",
            function () use ($article) {
                return $this->model
                            ->where('article_id', $article->id)
                            ->get()
                            ->load(['user', 'likes.user']);
            }
        );
    }
}
