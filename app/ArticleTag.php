<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\ArticleTag
 *
 * @property int $article_id
 * @property int $tag_id
 * @property-read \App\Article $article
 * @property-read \App\Tag $tag
 * @method static \Illuminate\Database\Query\Builder|\App\ArticleTag whereArticleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArticleTag whereTagId($value)
 * @mixin \Eloquent
 */
class ArticleTag extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'article_tag';

    /** @var array */
    protected $fillable = ['article_id', 'tag_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
