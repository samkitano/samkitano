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
 * @property string|null $orientation
 * @property int|null $size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $ratio
 * @property int $order
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Album $album
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereWidth($value)
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
