<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\ArticleLike
 *
 * @property int $user_id
 * @property int $article_id
 * @property-read \App\Article $article
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\ArticleLike whereArticleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArticleLike whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleLike extends Model
{
    /** @var array */
    protected $fillable = ['user_id', 'article_id'];

    /** @var bool */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
