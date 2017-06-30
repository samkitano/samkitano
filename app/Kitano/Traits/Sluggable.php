<?php

namespace App\Kitano\Traits;

/**
 * @method saving($closure)
 * @property-read Sluggable $title
 */
trait Sluggable
{
    /**
     * Trigger slug generation when saving
     */
    public static function bootSluggable()
    {
        static::saving(
            function ($model) {
                $model->attributes['slug'] = $model->generateSlug();
            }
        );
    }

    /**
     * Generates slug
     *
     * @return string
     */
    protected function generateSlug()
    {
        return str_slug($this->title);
    }
}
