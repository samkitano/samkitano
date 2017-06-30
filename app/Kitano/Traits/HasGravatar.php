<?php

namespace App\Kitano\Traits;

/**
 * Class HasGravatar
 *
 * @package App\Traits
 * @property-read HasGravatar $attributes
 */
trait HasGravatar
{
    /**
     * The gravatar service url
     *
     * @var string
     */
    protected $url = 'http://www.gravatar.com/avatar/';


    /**
     * Test if user has a profile image.
     * If so, use it. Otherwise, test
     * if user's email has a valid
     * gravatar associated, or,
     * just return false.
     *
     * @return bool|string
     */
    public function getAvatarAttribute()
    {
        $avatar = $this->attributes['avatar'];

        if ($avatar !== null) {
            return $avatar;
        }

        $gravatar = $this->testValidAvatar($this->getHash());

        return ! $gravatar
               ? false
               : $gravatar;
    }

    /**
     * Test the hash againsta a 404 to check gravatar existence
     *
     * @param   string $hash
     *
     * @return bool|string
     */
    protected function testValidAvatar($hash)
    {
        $headers = @get_headers($this->url . $hash . '?d=404');

        return preg_match("|200|", $headers[0]) ? $this->url . $hash : false;
    }

    /**
     * Returns the hashed email, required by gravatar
     *
     * @return string
     */
    protected function getHash()
    {
        $email = $this->fetchEmail();

        return md5(strtolower(trim($email)));
    }

    /**
     * @return mixed
     */
    protected function fetchEmail()
    {
        return $this->attributes['email'];
    }
}
