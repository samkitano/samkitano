<?php

namespace App\Kitano\Traits;

trait Cacheable
{
    /**
     * Cache results
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return mixed
     * @throws \Exception
     */
    protected function remember($key, $value)
    {
        return cache()->tags('main')
                      ->rememberForever(class_basename(static::class) . '.' . $key, $value);
    }

    /**
     * Remove all repository cached items
     *
     * @throws \Exception
     */
    protected function forget()
    {
        cache()->tags('main')
               ->flush();
    }

    /**
     * @param   string $key
     * @param   mixed  $value
     *
     * @return mixed
     * @throws \Exception
     */
    protected function rememberQuery($key, $value)
    {
        return cache()->tags($key)
                      ->rememberForever('query', $value);
    }

    /**
     * @param   string $key
     *
     * @throws \Exception
     */
    protected function forgetQuery($key)
    {
        cache()->tags($key)
               ->flush();

        // Except for contacts, we need to clear the
        // repo cache to reflect changes on API
        if ($key !== 'contacts') {
            $this->forget();
        }
    }
}
