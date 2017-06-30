<?php

namespace App\Kitano\Transformers;

use App\Kitano\Traits\Cacheable;

class CommentTransformer
{
    use Cacheable;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $items;

    /** @var \Illuminate\Database\Eloquent\Model|\App\Comment */
    protected $item;

    /** @var array */
    protected $transformed = [];

    /** @var array */
    protected $result = [];

    /** @var bool|array */
    protected $hasUser;


    /**
     * Transform Comments
     *
     * @param \Illuminate\Database\Eloquent\Collection $items       Items to transform
     * @param bool|false                               $hasUser     Authenticated
     * @param int                                      $articleID   Article ID
     *
     * @return mixed
     */
    public function transform($items, $articleID, $hasUser = false)
    {
        $this->items = $items;
        $this->hasUser = $hasUser;

        $authed = $hasUser ? '_AUTHED' : '';

        return $this->remember(
            "TRANSFORMED_COMMENTS_{$articleID}{$authed}",
            function () use ($items) {
                return $this->start();
            }
        );
    }

    /**
     * @return string
     */
    protected function articleTransformer()
    {
        return (string) $this->item->article->slug;
    }

    /**
     * @return array
     */
    protected function authorTransformer()
    {
        $user = $this->item->user;

        if (! $this->hasUser) {
            return [];
        }

        return [
            'name' => (string) $user->name,
            'slug' => (string) $user->slug,
            'avatar' => (string) $user->avatar,
            //'online' => (bool) $user->online,
            'registered' => (string) $user->created_at->toDateTimeString(),
        ];
    }

    /**
     * @return array
     */
    protected function commentTransformer()
    {
        return [
            'id' => (int) $this->item->id,
            'body' => (string) $this->item->body,
            'created' => (string) $this->item->created_at->toDateTimeString(),
            'updated' => (string) $this->item->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Do the transformation
     *
     * @return void
     */
    protected function doTransform()
    {
        $this->result = $this->commentTransformer();
        $this->result['likes'] = $this->likesTransformer();
        $this->result['author'] = $this->authorTransformer();
        $this->result['article'] = $this->articleTransformer();
    }

    /**
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
}
