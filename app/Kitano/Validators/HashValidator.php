<?php

namespace App\Kitano\Validators;

use Illuminate\Support\Facades\Hash;
use \Illuminate\Validation\Validator;

class HashValidator extends Validator
{
    /**
     * @param   string $attribute
     * @param   string $value
     * @param   array  $parameters
     *
     * @return bool
     */
    public function validateHash($attribute, $value, $parameters)
    {
        return Hash::check($value, $parameters[0]);
    }
}
