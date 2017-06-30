<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class SanitizeEmails extends TransformsRequest
{
    /** @var array */
    protected $only = [
        'email',
        'email_confirmation',
    ];

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed|string
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->only, true)) {
            return is_string($value) ? strtolower(trim($value)) : $value;
        }

        return $value;
    }
}
