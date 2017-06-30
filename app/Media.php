<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Media
 *
 * @property int $id
 * @property int $album_id
 * @property string $name
 * @property string $description
 * @property string $file_name
 * @property string $type
 * @property string $orientation
 * @property int $size
 * @property int $width
 * @property int $height
 * @property string $ratio
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Album $album
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereAlbumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereFileName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereOrientation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereRatio($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Media whereWidth($value)
 * @mixin \Eloquent
 */
class Media extends Model
{
    /** @var array */
    protected $fillable = [
        'album_id',
        'name',
        'description',
        'orientation',
        'type',
        'file_name',
        'size',
        'width',
        'height',
        'ratio',
        'order',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
