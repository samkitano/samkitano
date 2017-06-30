<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Kitano\ApiRepo\ArticleRepo;
use App\Http\Controllers\ApiController;
use App\Kitano\Transformers\ArticleTransformer;

class ArticlesController extends ApiController
{
    /** @var \App\Kitano\ApiRepo\ArticleRepo */
    protected $repo;

    /** @var \App\Kitano\Transformers\ArticleTransformer */
    protected $transformer;


    /**
     * @param \Illuminate\Http\Request                    $request
     * @param \App\Kitano\ApiRepo\ArticleRepo             $repo
     * @param \App\Kitano\Transformers\ArticleTransformer $transformer
     */
    public function __construct(Request $request, ArticleRepo $repo, ArticleTransformer $transformer)
    {
        parent::__construct($request);

        $this->repo = $repo;
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $articles = $this->repo->all();

        if (! $articles) {
            return $this->respondNotFound(ApiController::ARTICLES_NOT_FOUND);
        }

        $transformedArticles = $this->transformer->transform($articles, auth()->check());

        return $this->respondOk(['articles' => $transformedArticles]);
    }
}
