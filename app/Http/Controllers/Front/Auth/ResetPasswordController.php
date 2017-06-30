<?php

namespace App\Http\Controllers\Front\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontController;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends FrontController
{
    use ResetsPasswords;

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
}
