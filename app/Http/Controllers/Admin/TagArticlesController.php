<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\Http\Controllers\AdminController;

class TagArticlesController extends AdminController
{
    /**
     * @param int $id
     *
     * @return $this
     */
    public function index($id)
    {
        return view('admin.tags.articles.index')
               ->with('tag', $this->getTag($id));
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    protected function getTag($id)
    {
        return $this->remember(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Tag::findOrFail($id);
            }
        );
    }
}
