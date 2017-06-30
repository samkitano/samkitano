<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\CommentLike
 *
 * @property int $user_id
 * @property int $comment_id
 * @property-read \App\Comment $comment
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\CommentLike whereCommentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CommentLike whereUserId($value)
 * @mixin \Eloquent
 */
class CommentLike extends Model
{
    /** @var array */
    protected $fillable = ['user_id', 'comment_id'];

    /** @var bool */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
