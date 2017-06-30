<?php

namespace App;

use App\Kitano\Traits\HasGravatar;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $slug
 * @property bool|string $avatar
 * @property string $bio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ArticleLike[] $articleLikes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RegistrationToken[] $codes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CommentLike[] $commentLikes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read bool $active
 * @property-read bool $admin
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBio($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasGravatar, Notifiable;


    /** @var array */
    protected $fillable = ['name', 'email', 'password', 'slug', 'avatar', 'bio'];

    /** @var array */
    protected $hidden = ['id', 'password', 'remember_token', 'email', 'codes'];

    /** @var array */
    protected $appends = ['active', 'admin'];


    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @param $email
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static|\App\User
     */
    public static function findByEmail($email)
    {
        return static::whereEmail($email)->first();
    }

    /**
     * @param $slug
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static|\App\User
     */
    public static function findBySlug($slug)
    {
        return static::whereSlug($slug)->first();
    }

    /**
     * @param $email
     *
     * @return \App\RegistrationToken[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public static function getTokens($email)
    {
        return static::findByEmail($email)->codes;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function codes()
    {
        return $this->hasMany(RegistrationToken::class)->orderBy('created_at', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articleLikes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Checks if user has activity in the last 5 mins
     *
     * @return mixed
     */
    public function isOnline()
    {
        return cache()->has('user-' . $this->id . '-isOnline');
    }

    /**
     * @return bool
     */
    public function getActiveAttribute()
    {
        return (bool) ! $this->codes->count();
    }

    /**
     * @return bool
     */
    public function getAdminAttribute()
    {
        return (bool) Admin::where('email', $this->email)->count() > 0;
    }
}
