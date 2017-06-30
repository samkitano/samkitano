<?php

namespace App\Kitano\ApiRepo;

use App\User;

class UserRepo extends ApiRepo
{
    public function __construct()
    {
        $this->setModel(app(User::class));
    }

    /**
     * All articles
     *
     * @return bool|mixed
     */
    public function all()
    {
        if (! $this->hasItems()) {
            return false;
        }

        return $this->remember(
            'ELOQUENT_USERS_ALL',
            function () {
                return $this->model
                    ->with([
                        'comments',
                        'comments.article',
                        'articleLikes.article',
                        'commentLikes.comment',
                        'commentLikes.user',
                        'commentLikes.comment.article'])
                    ->get();
            }
        );
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->idx());
    }
}
