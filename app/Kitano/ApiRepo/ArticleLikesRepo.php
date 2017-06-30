<?php

namespace App\Kitano\ApiRepo;

use App\ArticleLike;

class ArticleLikesRepo extends ApiRepo
{
    public function __construct()
    {
        $this->setModel(app(ArticleLike::class));
    }
}
