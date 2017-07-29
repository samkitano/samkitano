<?php

namespace App;

use Laravel\Scout\Searchable;
use App\Kitano\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Article
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $subtitle
 * @property string $body
 * @property int $published
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ArticleLike[] $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use Sluggable, Searchable;

    /** @var array */
    protected $guarded = ['id'];

    /** @var array */
    protected $fillable = ['title', 'slug', 'body', 'subtitle', 'published'];

    /**
     * Disable algolia search indexing when we are testing
     */
    protected static function boot()
    {
        static::saved(function ($model) {
            if (! $model->published) {
                $model->unsearchable();
            }
        });

        parent::boot();

        if (config('database.default') == 'testing') {
            static::disableSearchSyncing();
        }
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $tags = $this->tags;

        return [
            //'id'       => $this->id,
            'slug'     => (string) $this->slug,
            'title'    => (string) $this->title,
            'subtitle' => (string) $this->subtitle,
            'body'     => (string) $this->body,
            'created'  => (string) $this->created_at->toDateTimeString(),
            'tags'     => (string) $tags->implode('name', ','),
            'comments' => (array) $this->getSrchComments(),
            'likes'    => (array) $this->getSrchLikes(),
        ];
    }

    /**
     * @return mixed
     */
    private function getSrchComments()
    {
        $comments['total'] = (int) $this->comments->count();
        $comments['users'] = (array) [];

        return $comments;
    }

    /**
     * @return mixed
     */
    private function getSrchLikes()
    {
        $likes['total'] = (int) $this->likes->count();
        $likes['users'] = (array) [];

        return $likes;
    }
}
