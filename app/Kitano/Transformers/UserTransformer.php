<?php

namespace App\Kitano\Transformers;

class UserTransformer
{
    /** @var \Illuminate\Database\Eloquent\Model|\App\Comment */
    protected $item;

    /** @var array */
    protected $transformed = [];

    /** @var array */
    protected $result = [];

    /** @var bool|array */
    protected $hasUser;


    /**
     * Transform Users
     *
     * @param \Illuminate\Database\Eloquent\Collection $items   Items to transform
     * @param bool|false                               $hasUser Authenticated
     *
     * @return array
     */
    public function transform($items, $hasUser = false)
    {
        $this->hasUser = $hasUser;

        foreach ($items as $item) {
            $this->item = $item;
            $this->doTransform();
            $this->transformed[] = $this->result;

            unset($this->result);
        }

        return $this->transformed;
    }

    /**
     * @return mixed
     */
    protected function articleLikesTransformer()
    {
        return $this->item->articleLikes->reduce(function ($carry, $item) {
            $carry[] = [
                'title' => (string) $item->article->title,
                'slug' => (string) $item->article->slug,
            ];

            return $carry;
        }, []);
    }

    /**
     * @return mixed
     */
    protected function commentsTransformer()
    {
        return $this->item->comments->reduce(function ($carry, $item) {
            $carry[] = [
                'comment_id' => (int) $item->id,
                'article_slug' => (string) $item->article->slug,
                'article_title' => (string) $item->article->title,
            ];

            return $carry;
        }, []);
    }

    /**
     * @return mixed
     */
    protected function commentLikesTransformer()
    {
        return $this->item->commentLikes->reduce(function ($carry, $item) {
            if (isset($item->comment)) {
                $carry[] = [
                    'comment_id' => (int) $item->comment_id,
                    'user_name' => (string) $item->user->name,
                    'user_slug' => (string) $item->user->slug,
                    'article_title' => (string) $item->comment->article->title,
                    'article_slug' => (string) $item->comment->article->slug,
                ];

                return $carry;
            }
        }, []);
    }

    /**
     * Do the transformation
     */
    protected function doTransform()
    {
        $this->result = $this->userTransformer();
        $this->result['likes'] = $this->likesTransformer();
        $this->result['comments'] = $this->commentsTransformer();
    }

    /**
     * @return array
     */
    protected function likesTransformer()
    {
        return [
            'articles' => (array) $this->articleLikesTransformer(),
            'comments' => (array) $this->commentLikesTransformer(),
        ];
    }

    /**
     * @return array
     */
    protected function userTransformer()
    {
        return [
            'name' => (string) $this->item->name,
            'slug' => (string) $this->item->slug,
            'avatar' => (string) $this->item->avatar,
            'bio' => (string) $this->item->bio,
            'created' => (string) $this->item->created_at->toDateTimeString(),
            'updated' => (string) $this->item->updated_at->toDateTimeString(),
        ];
    }
}
