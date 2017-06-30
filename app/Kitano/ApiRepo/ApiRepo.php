<?php

namespace App\Kitano\ApiRepo;

use App\Kitano\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;

class ApiRepo
{
    use Cacheable;

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $identifyer = 'slug';


    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Model $model)
    {
        $deleted = $model->delete();

        if ($deleted) {
            $this->forget();
        }

        return $deleted;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifyer;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function hasItem($slug)
    {
        return (bool) in_array($slug, $this->idx());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Model $model)
    {
        $model->save();
        $this->forget();

        return $model;
    }


    /**
     * @return bool
     */
    protected function hasItems()
    {
        return (bool) count($this->idx()) > 0;
    }

    /**
     * @return mixed
     */
    protected function idx()
    {
        return $this->remember(
            class_basename($this->model) . '_IDX',
            function () {
                return $this->isPublicable()
                       ? $this->model
                              ->wherePublished(true)
                              ->pluck($this->identifyer)
                              ->toArray()
                       : $this->model
                              ->pluck($this->identifyer)
                              ->toArray();
            }
        );
    }

    /**
     * @return bool
     */
    protected function isPublicable()
    {
        return (bool) in_array('published', $this->model->getFillable());
    }

    /**
     * @return void
     */
    protected function setIdentifier()
    {
        $this->identifyer = in_array('slug', $this->model->getFillable()) ? 'slug' : 'id';
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected function setModel(Model $model)
    {
        $this->model = $model;
        $this->setIdentifier();
    }
}
