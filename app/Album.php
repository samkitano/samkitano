<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Album
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Media[] $media
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereName($value)
 * @mixin \Eloquent
 */
class Album extends Model
{
    /** @var array */
    protected $fillable = ['name'];

    /** @var bool */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
