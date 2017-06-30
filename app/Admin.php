<?php

namespace App;

use App\Kitano\Traits\HasGravatar;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool|string $avatar
 * @property bool $boss
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereBoss($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasGravatar, Notifiable;

    /** @var array */
    protected $fillable = ['avatar', 'boss', 'email', 'name', 'password',];

    /** @var array */
    protected $hidden = ['email', 'password', 'remember_token'];
}
