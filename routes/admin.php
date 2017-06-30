<?php

Route::group([
    'namespace'  => 'Admin',
    'as'         => 'admin::',
    'middleware' => 'auth:admin',
], function () {
    Route::get('/', function () {
        return redirect()->route('admin::dashboard');
    });
    // TOOLS
    Route::get('ajax-albums', 'ToolsController@getAlbums');
    Route::post('clear-cache', 'ToolsController@clearCache');
    // DASHBOARD
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::post('dashboard/unlink', 'DashboardController@unlinkOrphans')->name('unlink-orphans');
    Route::resource('delogger', 'DeloggerController', ['only' => ['destroy']]);
    Route::post('delogger/download/{file}', 'DeloggerController@download')->name('delogger.download');
    Route::post('delogger/archive/{file}', 'DeloggerController@archive')->name('delogger.archive');
    Route::get('file-download/{file}', 'DownloadsController')->name('file-download');
    // ARTICLES
    Route::resource('articles', 'ArticlesController');
    Route::get('articles/{id}/tags', 'ArticleTagsController@index')->name('articles.tags.index');
    Route::group([
        'prefix' => 'articles',
        'as' => 'articles.'
    ], function () {
        Route::resource('{id}/comments', 'ArticleCommentsController', ['only' =>['index']]);
        Route::resource('{id}/tags', 'ArticleTagsController', ['only' =>['index']]);
        Route::resource('{id}/likes', 'ArticleLikesController', ['only' =>['index']]);
    });
    // TAGS
    Route::resource('tags', 'TagsController');
    Route::get('tags/{id}/articles', 'TagArticlesController@index')->name('tags.articles.index');
    // USERS
    Route::resource('users', 'UsersController', ['only' =>['index', 'show', 'destroy']]);
    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        Route::resource('{id}/comments', 'UserCommentsController', ['only' =>['index']]);
    });
    // COMMENTS
    Route::resource('comments', 'CommentsController');
    Route::group([
        'prefix' => 'comments',
        'as' => 'comments.',
    ], function () {
        Route::resource('{id}/user', 'CommentsUserController', ['only' =>['index']]);
        Route::resource('{id}/article', 'CommentsArticleController', ['only' =>['index']]);
        Route::resource('{id}/likes', 'CommentsLikesController', ['only' =>['index']]);
    });
    // CONTACT
    Route::resource('contacts', 'ContactsController', ['only' =>['index', 'show', 'destroy']]);
    Route::patch('mark-contact-as-read', 'ContactsController@markRead');
    // STATIC PAGES
    Route::resource('statics', 'StaticsController');
    // DATATABLES
    Route::group([
        'prefix'     => 'dt',
        'as'         => 'dt.',
    ], function () {
        Route::get('articles', 'DatatablesController@articles')->name('dt_articles');
        Route::get('articles-tags/{id}', 'DatatablesController@articleTags')->name('dt_article_tags');
        Route::get('articles-likes/{id}', 'DatatablesController@articleLikes')->name('dt_article_likes');
        Route::get('articles-comments/{id}', 'DatatablesController@articleComments')->name('dt_article_comments');
        Route::get('users', 'DatatablesController@users')->name('dt_users');
        Route::get('comments', 'DatatablesController@comments')->name('dt_comments');
        Route::get('comments-likes/{id}', 'DatatablesController@commentLikes')->name('dt_comments_likes');
        Route::get('user-comments/{userId}', 'DatatablesController@userComments')->name('dt_user_comments');
        Route::get('tags', 'DatatablesController@tags')->name('dt_tags');
        Route::get('tags-articles/{id}', 'DatatablesController@tagArticles')->name('dt_tag_articles');
        Route::get('contacts', 'DatatablesController@contacts')->name('dt_contacts');
        Route::get('statics', 'DatatablesController@statics')->name('dt_statics');
        Route::get('media', 'DatatablesController@media')->name('dt_media');
        Route::get('albums', 'DatatablesController@albums')->name('dt_albums');
        Route::get('media-albums/{id}', 'DatatablesController@mediaAlbums')->name('dt_media_albums');
        Route::get('admins', 'DatatablesController@admins')->name('dt_admins');
    });
    // ALBUMS
    Route::resource('albums', 'AlbumsController');
    Route::get('albums/{id}/media', 'AlbumsMediaController@index')->name('albums.media.index');
    Route::post('albums/{album}/media/{medium}/up', 'AlbumsMediaController@moveUp')->name('albums.media.up');
    Route::post('albums/{album}/media/{medium}/down', 'AlbumsMediaController@moveDown')->name('albums.media.down');
    // MEDIA
    Route::resource('media', 'MediaController');
    Route::get('media/{id}/albums', 'MediaAlbumsController@index')->name('media.albums.index');
    // ADMINS
    Route::resource('accounts', 'AccountsController');
    Route::get('change-password', 'AccountsController@getChangepw')->name('change-password');
    Route::patch('change-password', 'AccountsController@postChangepw');
});

// AUTH
Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('admin::login')->middleware('guest');
Route::post('login', 'Admin\Auth\LoginController@login')->name('admin::login')->middleware('sanitize');
Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin::logout')->middleware('auth:admin');
