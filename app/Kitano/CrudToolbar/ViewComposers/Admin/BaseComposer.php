<?php

namespace App\Kitano\CrudToolbar\ViewComposers\Admin;

use Illuminate\Support\Facades\Route;

class BaseComposer
{
    /** @var string | null */
    protected $pagename;

    /** @var \Illuminate\Routing\Route */
    protected $viewRoute;

    /** @var string | null */
    protected $resourceName;

    /** @var string | null */
    protected $namespace;


    public function __construct()
    {
        $r = Route::getCurrentRoute();

        if (isset($r)) {
            $action = $r->getAction();
            $as = isset($action['as']) ? $action['as'] : '';
            $namespace = substr($as, 0, strrpos($as, ':') - 1);

            $this->viewRoute = $r;
            $this->pagename = $r->getName();
            $this->resourceName = $this->getResourceName();
            $this->namespace = $namespace;
        }

        if ((! is_null($this->viewRoute))
            && $this->pagename !== 'admin::index'
            && $this->pagename !== 'admin::login'
        ) {
            view()->share([
                'pagename' => $this->pagename,
                'resourceName' => $this->resourceName,
                'isBoss' => (bool) auth('admin')->check() ? auth('admin')->user()->boss : false,
            ]);
        }
    }

    /**
     * @return string
     */
    protected function getResourceName()
    {
        $r = $this->viewRoute->uri;

        $res = substr($r, strpos($r, '/', -0) + 1);
        $hasModelId = preg_match('/{/', $r);

        if ($hasModelId) {
            $noId = substr($r, 0, strpos($r, '{') - 1);
            $res = substr($noId, strrpos($noId, '/', -0) + 1);
        }

        return str_replace('/', ':', $res);
    }
}
