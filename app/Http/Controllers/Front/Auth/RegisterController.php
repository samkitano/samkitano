<?php

namespace App\Http\Controllers\Front\Auth;

use Auth;
use App\User;
use Validator;
use Carbon\Carbon;
use App\RegistrationToken;
use Illuminate\Http\Request;
use App\Kitano\Traits\Cacheable;
use App\Http\Controllers\FrontController;
use App\Notifications\ConfirmRegistration;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends FrontController
{
    use Cacheable, RegistersUsers;

    /** @var \App\User */
    protected $user;

    /** @var string */
    protected $redirectTo = '/';


    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->middleware(['guest', 'sanitize']);
    }

    /**
     * Register a user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $this->validation()->validate();
        $this->makeSlug()->store();

        $this->user->notify(new ConfirmRegistration($this->makeToken()));

        $this->forget();

        return response()->json(['count' => 1], 201);
    }

    /**
     * Confirm/Activate a user
     *
     * @param   string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($token)
    {
        $hasToken = RegistrationToken::findCode($token);
        $alert = [
            'title' => 'Account Confirmation',
            'type' => 'error',
            'text' => 'Invalid Token',
        ];

        if ($hasToken) {
            Auth::login($hasToken->user);
            RegistrationToken::deleteCode($hasToken->user_id);

            $this->forget();

            $alert['type'] = 'info';
            $alert['text'] = 'Registration Confirmed. Thanks!';
        }

        if (! $hasToken && Auth::check()) {
            $alert['text'] = 'Account already activated';
            return redirect()->intended('/');
        }

        return redirect('/')->with('status', $alert);
    }

    /**
     * Resend token by user request
     * User can only request 3 tokens per hour
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend()
    {
        if (! $this->request->email) {
            return response()->json(['error' => 'Error', 'message' => 'Bad Request'], 400);
        }

        $input = array_except($this->request->all(), ['_method', '_token']);

        $this->emailValidator($input)->validate();

        $email = $input['email'];
        $user = User::findByEmail($email);

        if (! $user->count()) {
            return response()->json(['error' => 'Error', 'message' => trans('passwords.user')], 422);
        }

        $tokens = User::getTokens($email);

        $count = $tokens->count();
        $now = Carbon::now();
        $last_token_date = $count ? $tokens->first()->created_at : $now;
        $interval = $now->diffInSeconds($last_token_date) / 60;

        // check how many tokens have been sent already to an email we will
        // allow to stack 3 tokens. user can activate with any of them.
        // if 3rd time, check 3rd timestamp and disable for 1 hour.
        if ($count > 2 and $interval < 60) {
            $try_again = round(60 - $interval);
            $next_time = $now->addMinutes(60);

            return response()->json([
                'error' => 'Error',
                'message' => "Try again in {$try_again} minutes. ({$next_time})"
            ], 401);
        } elseif ($count > 2 and $interval >= 60) {
            // after 1 hour we need to delete previous tokens
            RegistrationToken::deleteCode($tokens->first()->user_id);
        }

        // generate new token and mail it
        $user->notify(new ConfirmRegistration($this->makeToken()));

        return response()->json(['count' => $count + 1], 201);
    }

    /**
     * @param $input
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function emailValidator($input)
    {
        return Validator::make($input, [
            'email' => 'required|email|max:255|exists:users',
        ]);
    }

    /**
     * Generate a token for this user
     *
     * @return static
     */
    protected function makeToken()
    {
        return RegistrationToken::createFor(User::findByEmail($this->request->email));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validation()
    {
        $data = $this->request->input();

        return Validator::make($data, [
            'name'  => [
                'required',
                'min:5',
                'max:20',
                'regex:/^[\pL\s]+$/u',
            ],
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return static|\App\User
     */
    protected function store()
    {
        $data = $this->request->all();

        $this->user = User::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Generate a slug for this user
     *
     * @return $this
     */
    protected function makeSlug()
    {
        $name  = $this->request->name;
        $slug  = str_slug($name);
        $count = str_slug(microtime());

        $this->request->merge(['slug'=>"{$slug}-{$count}"]);

        return $this;
    }
}
