<?php

namespace App\Http\Controllers\Front;

use App\User;
use App\Kitano\ApiRepo\UserRepo;
use App\Http\Controllers\FrontController;
use App\Kitano\Transformers\UserTransformer;

class UsersController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = false;

        if (auth()->check() && auth()->user()->admin) {
            $users = User::orderBy('name', 'ASC')->get();
        }

        return view('front.users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $auth = auth()->check();
        $transformer = new UserTransformer();
        $repo = new UserRepo();
        $own = $auth ? $slug === auth()->user()->slug : false;

        $authFlag = $auth ? '_AUTH' : '';
        $transformedUsers = $transformer->transform($repo->all(), $auth);

        if (! $repo->hasItem($slug)) {
            return false;
        }

        $user = $this->remember(
            "TRANSFORMED_USER_{$slug}{$authFlag}",
            function () use ($transformedUsers, $slug) {
                return collect($transformedUsers)
                           ->where('slug', $slug)
                           ->values()[0];
            }
        );

        return view('front.users.show', compact('user'), compact('own'));
    }
}
