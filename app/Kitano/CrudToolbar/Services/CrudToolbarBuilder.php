<?php

namespace App\Kitano\CrudToolbar\Services;

use Cache;

class CrudToolbarBuilder
{
    /** @var array */
    protected $crudMethods = ['index', 'create', 'show', 'edit', 'destroy'];

    /** @var string */
    protected $pagename;

    /** @var \stdClass */
    protected $toolbarOutput;

    /** @var \Illuminate\Routing\Route */
    protected $viewRoute;

    /** @var array */
    protected $viewData;

    /** @var array */
    protected $routeInfo = [];

    /** @var array */
    protected $icons = [
        'edit' => 'fa-edit',
        'index' => 'fa-list',
        'show' => 'fa-eye',
        'create' => 'fa-plus-square-o',
        'destroy' => 'fa-trash'
    ];



    /**
     * Build the toolbar
     *
     * @param $pagename
     * @param $viewRoute
     * @param $viewData
     *
     * @return \stdClass
     */
    public function build($pagename, $viewRoute, $viewData)
    {
        $this->pagename = $pagename;
        $this->viewRoute = $viewRoute;
        $this->viewData = $viewData;
        $this->toolbarOutput = new \stdClass();

        $this->makeToolbar();

        return Cache::remember('tb' . md5(serialize($this->toolbarOutput)), 30, function () {
            return $this->toolbarOutput;
        });
    }

    /**
     * Create the toolbar data
     *
     * @var $model \Illuminate\Database\Eloquent\Model|null
     */
    protected function makeToolbar()
    {
        $routeName = $this->viewRoute->getName();
        $routeFullResource = substr($routeName, strpos($routeName, ':') + 2);
        $method = substr($routeFullResource, strpos($routeFullResource, '.') + 1);
        $resource = substr($routeFullResource, 0, strpos($routeFullResource, '.'));
        $parameters =  $this->viewRoute->parameters;
        $hasModel = count($this->viewData) > 0;
        $modelName = $hasModel ? key($this->viewData) : null;
        $model = $hasModel ? $this->viewData[$modelName] : null;
        $isParent = $hasModel && $method === 'index';

        $this->routeInfo['name'] = $routeName;
        $this->routeInfo['fullResource'] = $routeFullResource;
        $this->routeInfo['resource'] = $resource;
        $this->routeInfo['method'] = $method;
        $this->routeInfo['methods'] = get_class_methods($this->viewRoute->controller);
        $this->routeInfo['parameters'] = $parameters;
        $this->routeInfo['parameterName'] = empty($parameters) ? null : $this->viewRoute->parameterNames[0];
        $this->routeInfo['hasModel'] = $hasModel;
        $this->routeInfo['model'] = $model;
        $this->routeInfo['modelId'] = $hasModel ? $model['id'] : null;
        $this->routeInfo['isParent'] = $isParent;
        $this->routeInfo['hasControllerMethod'] = in_array($method, $this->routeInfo['methods']);

        $this->makeLeftSection()
             ->makeCenterSection()
             ->makeRightSection()
             ->makeFormSection();
    }

    /**
     * Generate Toolbar Destroy form data
     */
    protected function makeFormSection()
    {
        $this->toolbarOutput->resource = $this->routeInfo['resource'];
    }

    /**
     * Generate Toolbar Left hand side data
     *
     * @return $this
     */
    protected function makeLeftSection()
    {
        $action = $this->viewRoute->getAction()['controller'];
        $controller = substr($action, strrpos($action, '\\', -0) + 1);

        $this->toolbarOutput->info = $this->routeInfo['name'];
        $this->toolbarOutput->controller = $controller;
        $this->toolbarOutput->controllerMethods = $this->getControllerExistentCrudMethods();
        $this->toolbarOutput->resourceId = $this->routeInfo['hasModel'] ? $this->routeInfo['modelId'] : '-';

        return $this;
    }

    /**
     * @return string
     */
    protected function getControllerExistentCrudMethods()
    {
        $existentMethods = $this->routeInfo['methods'];
        $res = '';

        foreach ($this->crudMethods as $method) {
            if (in_array($method, $existentMethods)) {
                $res .= $res != '' ? ', ' : '';
                $res .= $method;
            }
        }

        return (string) $res;
    }

    /**
     * Generate Crud Buttons
     *
     * @return $this
     */
    protected function makeCenterSection()
    {
        $this->toolbarOutput->crud = new \stdClass();

        $this->makeCrudItems();

        return $this;
    }

    /**
     * Generate Toolbar Right hand side data
     *
     * @return $this
     */
    protected function makeRightSection()
    {
        $this->toolbarOutput->relations = new \stdClass();

        if (! $this->routeInfo['isParent'] && ! $this->routeInfo['hasModel']) {
            $this->toolbarOutput->relations->hasRelation = null;
            return $this;
        }

        $relations = $this->routeInfo['hasModel'] ? $this->routeInfo['model']->getRelations() : null;

        $this->toolbarOutput->relations->hasRelation = true;
        $this->toolbarOutput->relations->models = $relations;
        $this->toolbarOutput->relations->resource = $this->routeInfo['resource'];
        $this->toolbarOutput->relations->resourceId = $this->routeInfo['modelId'];

        return $this;
    }

    /**
     * @param $method
     *
     * @return string
     */
    protected function composeRoute($method)
    {
        if (! in_array($method, $this->routeInfo['methods'])) {
            return '#';
        }

        if ($method != 'index' && $method != 'create') {
            return route("admin::{$this->routeInfo['resource']}.{$method}", $this->routeInfo['modelId']);
        }

        return route("admin::{$this->routeInfo['resource']}.{$method}");
    }

    /**
     * @param $method
     *
     * @return bool
     */
    protected function shouldDisable($method)
    {
        // this route doesn't have this method or this method dos not exist in controller
        if (! $this->routeInfo['hasControllerMethod'] || ! in_array($method, $this->routeInfo['methods'])) {
            return true;
        }

        $currentMethod = $this->routeInfo['method'];

        if ($currentMethod === $method) {
            return true;
        }

        if ($currentMethod == 'index' && ($method == 'edit' || $method == 'show' || $method == 'destroy')) {
            return true;
        }

        if ($currentMethod == 'create' && ($method == 'edit' || $method == 'show' || $method == 'destroy')) {
            return true;
        }

        return false;
    }


    /**
     * @return $this
     */
    protected function makeCrudItems()
    {
        $items = new \stdClass();

        foreach ($this->crudMethods as $method) {
            $item = new \stdClass();
            $alwaysEnabled = $method == 'create' || $method == 'index';
            $disabled = $this->shouldDisable($method);

            // liClass
            $item->liClass = $disabled ? 'hint hint--bottom-left disabled' : 'hint hint--bottom-left';

            // aClass
            if ($method == 'destroy') {
                $item->aClass = 'Toolbar__Crud_destroy';
            } else {
                $item->aClass = $this->routeInfo['hasModel']  || $alwaysEnabled ? '' : 'no-action';
            }

            // aHref
            $item->aHref = $this->routeInfo['hasModel'] || $alwaysEnabled ? $this->composeRoute($method) : '#';
            // liLabel
            $item->liLabel = ucfirst($method);
            // iClass
            $item->icon = $this->icons[$method];

            $items->{$method} = $item;
        }

        $this->toolbarOutput->crud = $items;
        return $this;
    }
}
