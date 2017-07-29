<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Tag
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereName($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    /** @var array */
    protected $fillable = ['name'];

    /** @var bool */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
