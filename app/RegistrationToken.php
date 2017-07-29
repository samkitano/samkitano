<?php

namespace App;

use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationTokenCreated;
use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\RegistrationToken
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RegistrationToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RegistrationToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RegistrationToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RegistrationToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RegistrationToken whereUserId($value)
 * @mixin \Eloquent
 */
class RegistrationToken extends Model
{
    /** @var array */
    protected $fillable = ['user_id', 'token'];


    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'token';
    }

    /**
     * @param \App\User $user
     *
     * @return static|\App\User
     */
    public static function createFor(User $user)
    {
        return static::create([
            'user_id' => $user->id,
            'token' => static::generateToken(),
        ]);
    }

    /**
     * @param $code
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function findCode($code)
    {
        return static::where('token', $code)->first();
    }

    /**
     * @param $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findCodes($userId)
    {
        return static::where('user_id', $userId)->get();
    }

    /**
     * @param $uid
     *
     * @return bool|null
     * @throws \Exception
     */
    public static function deleteCode($uid)
    {
        return static::where('user_id', $uid)->delete();
    }

    /**
     * @param \App\User $user
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function updateFor(User $user)
    {
        $user_token = static::where('user_id', $user->id)->first();
        $user_token->token = static::generateToken();
        $user_token->save();

        return $user_token;
    }

    /**
     * Email token
     *
     * @return void
     */
    public function deliver()
    {
        $email = new RegistrationTokenCreated($this->token, $this->user->name);

        Mail::to($this->user->email)->send($email);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    protected static function generateToken()
    {
        return hash_hmac('sha256', str_random(64), config('app.key'));
    }
}
