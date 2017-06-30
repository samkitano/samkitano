<?php

namespace App\Http\Controllers\Front;

use App\Share;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Kitano\ApiRepo\ArticleRepo;
use App\Http\Controllers\FrontController;
use App\Kitano\Transformers\ArticleTransformer;

class ArticlesController extends FrontController
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
     * All articles
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index()
    {
        return view($this->args['view']);
    }

    /**
     * Single article
     *
     * @param $slug
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show($slug)
    {
        $authed = auth()->check();
        $auth = $authed ? '_AUTH' : '';
        $transformedArticles = $this->transformer->transform($this->repo->all(), $authed);

        if (! $this->repo->hasItem($slug)) {
            return view($this->args['view'])->with('article', null);
        }

        $article = $this->remember(
            "TRANSFORMED_ARTICLE_{$slug}{$auth}",
            function () use ($transformedArticles, $slug) {
                return collect($transformedArticles)
                           ->where('slug', $slug)
                           ->values()[0];
            }
        );

        $likes = $this->getIconLikesData($article);

//        Twitter count is dead, so... not wasting time on this, for the moment.
//        $shares = $this->getShares($article['slug']);

        return view($this->args['view'])
            ->with('article', $this->repo->addBody($article))
//            ->with(compact('shares'))
            ->with(compact('likes'));
    }

    /**
     * TODO: translation
     * TODO: too much stuff for a controller. rethink this approach.
     *
     * @param $article
     *
     * @return array
     */
    protected function getIconLikesData($article)
    {
        $auth = auth()->check();
        $user = auth()->user();
        $slug = $auth ? $user->slug : null;
        $unlikable = $auth && in_array($slug, $article['likes']['users']);
        $canLike = ! $unlikable && $auth;
        $total = $article['likes']['total'];
        $others = $total - 1;
        $res = $total > 1 ? 'Likes' : 'Like';

        if ($canLike && ! $unlikable) {
            $ariaText = 'Click to like this article';
        } else {
            if ($unlikable) {
                $ariaText = $total > 1 ? "You and {$others} others like this article" : 'You Like This Article';
            } else {
                $ariaText = $total . ' ' . $res;
            }
        }

        $clickable = $canLike ? ' Svg__Icon_clickable' : ' Svg__Icon_unclickable';
        $liked = $unlikable ? ' Svg__Icon_active' : '';
        $iconClass = 'Svg__Icon ' . $liked . $clickable;

        return [
            'iconText' => $total,
            'iconClass' => $iconClass,
            'ariaText' => $ariaText,
            'inactive' => ! $canLike || $unlikable,
        ];
    }

    /**
     * @param string $articleSlug
     *
     * @return mixed
     */
    protected function getShares($articleSlug)
    {
        $domain = env('APP_DOMAIN');
        $url = "http://graph.facebook.com/?id=http://{$domain}/articles/{$articleSlug}";
        $data = $this->getFbApiData($url);
        $count = isset($data['share']['share_count']) ? $data['share']['share_count'] : null;
        $res['facebook'] = $count;

        return $res;
    }

    /**
     * @param string $url
     *
     * @return bool|mixed
     */
    protected function getFbApiData($url)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $url);
        } catch (Exception $e) {
            return null;
        }

        return json_decode($response->getBody(), true);
    }
}
