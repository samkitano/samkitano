<?php

namespace App\Kitano\CrudToolbar\ViewComposers\Admin;

use Illuminate\Contracts\View\View;
use App\Kitano\CrudToolbar\Services\CrudToolbarBuilder;

class CrudToolbarComposer extends BaseComposer
{
    /** @var array */
    protected $except = [
        'admin::dashboard',
        'admin::login',
        'home'
    ];

    /** @var array */
    protected $baseViews = ['create', 'index', 'show', 'edit'];


    /**
     * @param \Illuminate\Contracts\View\View $view
     */
    public function compose(View $view)
    {
        if (in_array($this->pagename, $this->except) || $this->namespace == 'front') {
            return;
        }

        $viewName = $view->name();
        $method = substr($viewName, strrpos($viewName, '.', -0) + 1);

        // if we don't perform this check, the toolbar will be instantiated
        // again and again for every view partial called, including layouts
        if (in_array($method, $this->baseViews)) {
            view()->share('toolbar', $this->getToolbar($view));
        }
    }

    /**
     * @param $view
     *
     * @return \stdClass
     */
    protected function getToolbar($view)
    {
        return app(CrudToolbarBuilder::class)->build(
            $this->pagename,
            $this->viewRoute,
            $view->getData()
        );
    }
}
