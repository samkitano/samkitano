<?php

namespace App\Providers;

use Cookie;
use Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Kitano\Validators\HashValidator;
use Illuminate\Cookie\Middleware\EncryptCookies;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Hash validator
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new HashValidator($translator, $data, $rules, $messages);
        });

        // Blade json directive
        Blade::directive('json', function ($expression) {
            return "<?php echo json_encode({$expression}); ?>";
        });

        // EU Cookie Agreement warning BS
        $this->app->resolving(EncryptCookies::class, function (EncryptCookies $encryptCookies) {
            $encryptCookies->disableFor('allow_cookies');
        });

        $this->app['view']->composer('front.allow-cookies', function (View $view) {
            $hasAccepted = Cookie::has('allow_cookies');

            $view->with(compact('hasAccepted'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
