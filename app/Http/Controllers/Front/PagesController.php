<?php

namespace App\Http\Controllers\Front;

use App\Article;
use Illuminate\Http\Request;
use App\Kitano\ApiRepo\ArticleRepo;
use App\Http\Controllers\FrontController;
use App\Kitano\Transformers\ArticleTransformer;

class PagesController extends FrontController
{
    /** @var \App\Kitano\ApiRepo\ArticleRepo */
    protected $repo;

    /** @var \App\Kitano\Transformers\ArticleTransformer */
    protected $transformer;


    public function __construct(Request $request, ArticleRepo $repo, ArticleTransformer $transformer)
    {
        parent::__construct($request);

        $this->repo = $repo;
        $this->transformer = $transformer;
    }

    /**
     * About Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view($this->args['view']);
    }

    /**
     * Contact Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view($this->args['view']);
    }

    /**
     * Home Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return view($this->args['view'])->with('latest', $this->getLatestArticles(3));
    }

    /**
     * @param int $amount
     *
     * @return mixed
     */
    protected function getLatestArticles($amount)
    {
        $articles = $this->repo->mostRecent($amount);

        return $this->rememberQuery(
            __FUNCTION__,
            function () use ($articles) {
                return $this->transformer->transform($articles, auth()->check());
            }
        );
    }
}
