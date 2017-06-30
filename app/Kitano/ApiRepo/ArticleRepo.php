<?php

namespace App\Kitano\ApiRepo;

use App\Article;

class ArticleRepo extends ApiRepo
{
    public function __construct()
    {
        $this->setModel(app(Article::class));
    }

    /**
     * All articles
     *
     * @return bool|mixed
     */
    public function all()
    {
        if (! $this->hasItems()) {
            return false;
        }

        return $this->remember(
            'ELOQUENT_ARTICLES_ALL',
            function () {
                return $this->model
                            ->wherePublished(true)
                            ->with(['tags', 'comments.user', 'likes.user'])
                            ->get();
            }
        );
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->idx());
    }

    /**
     * Each article body
     *
     * @param   string $slug
     *
     * @return mixed
     */
    protected function getBody($slug)
    {
        $articles = $this->all();

        return $this->remember(
            "ELOQUENT_ARTICLE_BODY_{$slug}",
            function () use ($articles, $slug) {
                return collect($articles)
                           ->where('slug', $slug)
                           ->values()[0]['body'];
            }
        );
    }

    /**
     * Each article
     *
     * @param   string $slug
     *
     * @return bool|mixed
     */
    public function one($slug)
    {
        if (! $this->hasItem($slug)) {
            return false;
        }

        return $this->remember(
            "ELOQUENT_ARTICLE_{$slug}",
            function () use ($slug) {
                return collect($this->all())
                           ->where('slug', $slug)
                           ->values()[0];
            }
        );
    }

    /**
     * Get the latest articles
     *
     * @param int $amount The number of articles to retrieve
     *
     * @return mixed
     */
    public function mostRecent($amount = 3)
    {
        if (! $this->count()) {
            return [];
        }

        return $this->remember(
            "MOST_RECENT",
            function () use ($amount) {
                return $this->all()->sortByDesc('created_at')->take($amount);
            }
        );
    }

    /**
     * Attach body to an article
     *
     * @param   array $article
     *
     * @return mixed
     */
    public function addBody($article)
    {
        $article['content'] = $this->getBody($article['slug']);

        return $article;
    }
}
