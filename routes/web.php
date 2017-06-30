<?php


Route::group([
    'namespace'  => 'Front',
    'as'         => 'front::',
], function () {
    // PAGES
    Route::get('/', 'PagesController@home')->name('home');
    Route::get('/about', 'PagesController@about')->name('about');
    Route::get('/contact', 'PagesController@contact')->name('contact');

    Route::resource('articles', 'ArticlesController', ['only' => ['index', 'show']]);
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
});

// API
Route::group([
    'namespace'  => 'Api',
    'prefix' => 'api'
], function () {
    Route::resource('users', 'UsersController', ['only' => ['update']]);
    Route::post('contact', 'ContactsController')->middleware('sanitize');
    Route::get('articles', 'ArticlesController');
    Route::group([
        'prefix' => 'articles',
        'as' => 'articles.'
    ], function () {
        Route::post('{article}/like', 'ArticleLikesController')->middleware('auth');
        Route::post('{article}/comments/{commentId}/like', 'CommentLikesController')->middleware('auth');
        Route::resource('{article}/comments', 'CommentsController', ['except' => ['show', 'create', 'edit']]);
    });
});

// AUTH
Route::post('login', 'Front\Auth\LoginController@login');
Route::post('logout', 'Front\Auth\LoginController@logout')->name('logout');
Route::post('register', 'Front\Auth\RegisterController@register');
Route::get('password/reset', 'Front\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Front\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Front\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Front\Auth\ResetPasswordController@reset');
Route::get('/confirm/{token}', 'Front\Auth\RegisterController@confirm')->name('register.confirm');
Route::post('/resend', 'Front\Auth\RegisterController@resend')->name('register.resend');
