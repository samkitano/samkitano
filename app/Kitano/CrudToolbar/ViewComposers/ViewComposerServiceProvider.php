<?php

namespace App\Kitano\CrudToolbar\ViewComposers;

use View;
use Illuminate\Support\ServiceProvider;
use App\Kitano\CrudToolbar\ViewComposers\Admin\CrudToolbarComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addComposer('*', CrudToolbarComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

    protected function addComposer($views, $callback)
    {
        View::composer($views, $callback);
    }
}
