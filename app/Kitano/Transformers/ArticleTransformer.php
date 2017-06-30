<?php

namespace App\Kitano\Transformers;

use App\Kitano\Traits\Cacheable;

class ArticleTransformer
{
    use Cacheable;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $items;

    /** @var \Illuminate\Database\Eloquent\Model|\App\Article */
    protected $item;

    /** @var array */
    protected $transformed = [];

    /** @var array */
    protected $result = [];

    /** @var bool|array */
    protected $hasUser;


    /**
     * Transform all articles
     *
     * @param \Illuminate\Database\Eloquent\Collection  $items   Items to transform
     * @param bool|array                                $hasUser Authenticated
     *
     * @return array
     */
    public function transform($items, $hasUser = false)
    {
        $this->items = $items;
        $this->hasUser = $hasUser;

        $authed = $hasUser ? '_AUTHED' : '';

        return $this->remember(
            'TRANSFORMED_ARTICLES' . $authed,
            function () use ($items) {
                return $this->start();
            }
        );
    }


    /**
     * Root transformer
     *
     * @return array
     */
    protected function articleTransformer()
    {
        return [
            'slug' => (string) $this->item->slug,
            'title' => (string) $this->item->title,
            'subtitle' => (string) $this->item->subtitle,
            'teaser' => (string) $this->getTeaser($this->item->body),
            'created' => (string) $this->item->created_at->toDateTimeString(),
            'updated' => (string) $this->item->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Relationship transformer [comments]
     *
     * @return mixed
     */
    protected function commentsTransformer()
    {
        $articleComments = $this->item->comments;
        $comments['total'] = $articleComments->count();
        $comments['users'] = [];

        if ($this->hasUser && $comments['total']) {
            foreach ($articleComments as $comment) {
                $author = $comment->user->slug;

                $comments['users'][$author] =
                    isset($comments['users'][$author])
                        ? $comments['users'][$author] + 1
                        : 1;
            }
        }

        return $comments;
    }

    /**
     * Do the transformation
     *
     * @return void
     */
    protected function doTransform()
    {
        $this->result = $this->articleTransformer();
        $this->result['likes'] = $this->likesTransformer();
        $this->result['comments'] = $this->commentsTransformer();
        $this->result['tags'] = (string) $this->tagsTransformer();
    }

    /**
     * @param   string $body
     *
     * @return string
     */
    protected function getTeaser($body)
    {
        if (strlen($body) < 100) {
            return $body;
        }

        return strip_tags(substr($body, 0, 100)) . '[...]';
    }

    /**
     * Relationship transformer [likes]
     *
     * @return mixed
     */
    protected function likesTransformer()
    {
        $likers = $this->item->likes;
        $likes['total'] = $likers->count();
        $likes['users'] = [];

        if ($this->hasUser and $likes['total']) {
            $likes['users'] = $likers->reduce(function ($carry, $item) {
                $carry[] = (string) $item->user->slug;

                return $carry;
            }, []);
        }

        return $likes;
    }

    /**
     * @return array
     */
    protected function start()
    {
        foreach ($this->items as $item) {
            $this->item = $item;
            $this->doTransform();
            $this->transformed[] = $this->result;

            unset($this->result);
        }

        return $this->transformed;
    }

    /**
     * Relationship transformer [tags]
     *
     * @return array|mixed
     */
    protected function tagsTransformer()
    {
        if (! $this->item->tags->count()) {
            return '';
        }

        return implode(
            $this->item->tags->reduce(function ($carry, $item) {
                $carry[] = $item->name;
                return $carry;
            }, []),
            ','
        );
    }
}
