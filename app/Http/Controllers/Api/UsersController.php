<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Kitano\ApiRepo\UserRepo;
use App\Http\Controllers\ApiController;
use App\Kitano\Transformers\UserTransformer;

class UsersController extends ApiController
{
    /** @var \App\Kitano\ApiRepo\UserRepo */
    protected $repo;

    /** @var \App\Kitano\Transformers\UserTransformer */
    protected $transformer;

    /**
     * @param \Illuminate\Http\Request                 $request
     * @param \App\Kitano\ApiRepo\UserRepo             $repo
     * @param \App\Kitano\Transformers\UserTransformer $transformer
     */
    public function __construct(Request $request, UserRepo $repo, UserTransformer $transformer)
    {
        parent::__construct($request);

        $this->repo = $repo;
        $this->transformer = $transformer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update($slug)
    {
        $this->user = User::findBySlug($slug);

        if ($this->request->has('id') || $this->request->has('slug')) {
            return $this->respondUnauthorized();
        }

        if ($this->request->has('bio') && $this->request->input('bio') == '') {
            $this->saveUser();
            return $this->respondNoContent(ApiController::USER_UPDATED);
        }

        if ($this->request->has('avatar')) {
            return $this->respondUpdated(['avatar' => $this->user->avatar]);
        }

        $this->validateRequest()->saveUser();

        if ($this->request->has('email')) {
            return $this->respondUpdated(['avatar' => $this->user->avatar]);
        }

        return $this->respondNoContent(ApiController::USER_UPDATED);
    }

    /**
     * @return $this
     */
    protected function validateRequest()
    {
        $inputs = array_except($this->request->input(), ['_method', '_token']);
        $rules = [];

        foreach ($inputs as $input => $val) {
            if (! ($input == 'bio' && is_null($val))) {
                $rules[$input] = $this->getRules($input);
            }
        }

        $this->validate($this->request, $rules);

        return $this;
    }

    /**
     * @param $field
     *
     * @return mixed
     */
    protected function getRules($field)
    {
        $rules = [
            'name'  => [
                'required',
                'min:5',
                'max:20',
                'regex:/^[\pL\s.-]+$/u',
            ],
            'old_email' => [
                'required',
                'email',
                "exists:users,email",
                'different:email',
            ],
            'email_confirmation' => [
                'same:email'
            ],
            'email' => [
                'required',
                'confirmed',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:6',
            ],
            'old_password' => [
                'required',
                'min:6',
                'different:password',
                'hash:' . $this->user->password
            ],
            'password_confirmation' => [
                'same:password'
            ],
            'bio'   => [
                'max:255',
                'regex:/^[\pL\pN\s"_.,#:;@?!\/*+-]+$/u',
            ],
        ];

        return $rules[$field];
    }

    /**
     * @return array
     */
    protected function saveUser()
    {
        $user = $this->user;

        $req = array_except(
            $this->request->input(),
            [
                '_method',
                '_token',
                'old_email',
                'email_confirmation',
                'old_password',
                'password_confirmation',
            ]
        );

        if ($this->request->has('password')) {
            $user->password = bcrypt($req['password']);
        } else {
            $user[key($req)] = $this->request->input(key($req));
        }

        $user->save();

        if (! $this->isChangingCredentials()) {
            $this->forget();
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function isChangingCredentials()
    {
        return (bool) $this->request->has('password') || $this->request->has('email');
    }
}
