<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Contact
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property string $ip
 * @property string $user_agent
 * @property int $read
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contact whereUserId($value)
 * @mixin \Eloquent
 */
class Contact extends Model
{
    /** @var array */
    protected $fillable = ['name', 'email', 'message', 'ip', 'user_agent', 'read', 'user_id'];


    /**
     * Save request details
     */
    protected static function boot()
    {
        parent::boot();

        Contact::creating(
            function ($contact) {
                $contact->ip = Request::ip();
                $contact->user_agent = Request::server('HTTP_USER_AGENT');
            }
        );
    }
}
