<?php

namespace App\Kitano\ApiRepo;

use App\CommentLike;

class CommentLikesRepo extends ApiRepo
{
    public function __construct()
    {
        $this->setModel(app(CommentLike::class));
    }
}
