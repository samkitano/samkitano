<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Statics
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Statics whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Statics extends Model
{
    /** @var array */
    protected $fillable = ['slug', 'title', 'description', 'body'];


    /**
     * Trim & lowercase slug
     */
    protected static function boot()
    {
        parent::boot();

        Statics::creating(
            function ($page) {
                $page->slug = trim(strtolower($page->slug));
            }
        );
    }
}
