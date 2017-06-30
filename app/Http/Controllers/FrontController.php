<?php

namespace App\Http\Controllers;

use Route;
use App\Statics;
use Illuminate\Http\Request;
use App\Kitano\Traits\Cacheable;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FrontController extends BaseController
{
    use AuthorizesRequests, Cacheable, ValidatesRequests;

    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var array */
    protected $args = [];


    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $route = Route::getCurrentRoute();

        if (isset($route)) {
            $r_name = $route->getName();

            $this->args = $this->remember(
                "ARGS_{$r_name}",
                function () use ($route, $r_name) {
                    return $this->setRouteArgs($route, $r_name);
                }
            );

            $content = $this->remember(
                "STATICS_{$this->args['method']}",
                function () {
                    return $this->fetchContent();
                }
            );

            view()->share(['content' => $content, 'args' => $this->args]);
        }
    }

    /**
     * @return mixed
     */
    protected function fetchContent()
    {
        return Statics::where('slug', $this->args['method'])->first();
    }

    /**
     * @param $route
     * @param $r_name
     *
     * @return array
     */
    protected function setRouteArgs($route, $r_name)
    {
        $args = [];
        $args['route'] = $r_name;
        $args['view'] = str_replace('::', '.', $r_name);
        $args['group'] = Route::hasGroupStack() ? Route::getGroupStack() : null;
        $args['resource'] = stringBetween($r_name, '::', '.');
        $args['method'] = $route->getActionMethod();
        $args['isArticles'] = $args['resource'] === 'articles' && $args['method'] === 'index';

        return $args;
    }
}
