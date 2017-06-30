<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\User;
use App\Admin;
use App\Album;
use App\Media;
use App\Statics;
use App\Comment;
use App\Contact;
use App\Article;
use App\ArticleTag;
use App\ArticleLike;
use App\CommentLike;
use Yajra\Datatables\Datatables;
use App\Kitano\MediaManager\Manager;
use App\Http\Controllers\AdminController;

class DatatablesController extends AdminController
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public function admins()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Admin::all()->makeVisible(['email']);
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit' href='"
                   . route('admin::accounts.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('name', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::accounts.show', $row->id) . "'>{$row->name}</a>";
        })->editColumn('avatar', function ($row) {
            return $this->formatAvatar($row->avatar);
        })->editColumn('boss', function ($row) {
            return $row->boss ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED;
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['avatar', 'id', 'name', 'boss', 'created_at', 'updated_at'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function albums()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Album::all();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit' href='"
                   . route('admin::albums.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('name', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::albums.show', $row->id) . "'>{$row->name}</a>";
        })->rawColumns(['id', 'name'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function articles()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Article::select(['id', 'title', 'subtitle', 'published', 'created_at', 'updated_at'])
                              ->with(['comments', 'likes'])
                              ->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit' href='"
                   . route('admin::articles.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('title', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::articles.show', $row->id) . "'>{$row->title}</a>";
        })->editColumn('published', function ($row) {
            return $row->published ? ICON_FLAG_GREEN : ICON_FLAG_RED;
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->addColumn('comments', function ($row) {
            return $row->comments->count();
        })->addColumn('likes', function ($row) {
            return $row->likes->count();
        })->rawColumns(['id', 'title', 'published', 'created_at', 'updated_at'])->make(true);
    }

    /**
     * @param   int $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function articleComments($id)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $id,
            function () use ($id) {
                return Comment::whereArticleId($id)
                              ->with(
                                  [
                                      'article' => function ($q) {
                                          $q->select('id');
                                      },
                                      'user' => function ($r) {
                                          $r->select('id', 'name');
                                      },
                                      'likes'
                                  ]
                              )->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit Comment' href='"
                   . route('admin::comments.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('title', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Article' href='"
                   . route('admin::articles.show', $row->article_id) . "'>{$row->article_id}</a>";
        })->editColumn('body', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Comment' href='"
                   . route('admin::comments.show', $row->id) . "'>{$row->body}</a>";
        })->addColumn('user', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show User' href='"
                   . route('admin::users.show', $row->user_id) . "'>{$row->user->name}</a>";
        })->addColumn('likes', function ($row) {
            return $row->likes->count();
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['id', 'title', 'body', 'user', 'created_at', 'updated_at'])->make(true);
    }

    /**
     * @param   int $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function articleLikes($id)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $id,
            function () use ($id) {
                return ArticleLike::whereArticleId($id)
                                  ->with(
                                      [
                                          'article' => function ($q) {
                                              $q->select('id', 'title', 'created_at');
                                          },
                                          'user' => function ($r) {
                                              $r->select('id', 'name');
                                          }
                                      ]
                                  )
                                  ->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit' href='"
                   . route('admin::articles.edit', $row->article_id) . "'>{$row->article_id}</a>";
        })->editColumn('title', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::articles.show', $row->article_id) . "'>{$row->article->title}</a>";
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->article->created_at);
        })->addColumn('user', function ($row) {
            return "<a class='hint hint--top' aria-label='Show User' href='"
                   . route('admin::users.show', $row->user_id) . "'>{$row->user->name}</a>";
        })->rawColumns(['id', 'title', 'user', 'created_at'])->make(true);
    }

    /**
     * @param   int $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function articleTags($id)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $id,
            function () use ($id) {
                return ArticleTag::whereArticleId($id)
                                 ->with(
                                     [
                                         'article' => function ($q) {
                                             $q->select('id', 'title');
                                         },
                                         'tag' => function ($r) {
                                             $r->select('id', 'name');
                                         }
                                     ]
                                 )->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit Tag' href='"
                   . route('admin::tags.edit', $row->tag_id) . "'>{$row->tag_id}</a>";
        })->editColumn('title', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Article' href='"
                   . route('admin::articles.show', $row->article_id) . "'>{$row->article->title}</a>";
        })->editColumn('name', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Tag' href='"
                   . route('admin::tags.show', $row->tag_id) . "'>{$row->tag->name}</a>";
        })->rawColumns(['id', 'title', 'name'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function comments()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Comment::with(
                    [
                        'user' => function ($x) {
                            $x->select('id', 'name');
                        },
                        'likes',
                        'article' => function ($y) {
                            $y->select('id');
                        }
                    ]
                )->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right-right' aria-label='Edit Comment' href='"
                   . route('admin::comments.edit', $row->id) . "'>{$row->id}</a>";
        })->addColumn('user', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show User' href='"
                   . route('admin::users.show', $row->user->id) . "'>{$row->user->name}</a>";
        })->addColumn('article', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Article' href='"
                   . route('admin::articles.show', $row->article->id) . "'>{$row->article->id}</a>";
        })->editColumn('body', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Comment' href='"
                   . route('admin::comments.show', $row->id) . "'>{$row->body}</a>";
        })->addColumn('likes', function ($row) {
            return $row->likes->count();
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['id', 'user', 'article', 'body', 'created_at', 'updated_at'])->make(true);
    }

    /**
     * @param   int $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function commentLikes($id)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $id,
            function () use ($id) {
                return CommentLike::whereCommentId($id)
                                  ->with(
                                      [
                                          'user' => function ($q) {
                                              $q->select('id', 'name');
                                          }
                                      ]
                                  )->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Comment' href='"
                   . route('admin::comments.show', $row->comment_id) . "'>{$row->comment_id}</a>";
        })->editColumn('user', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show User' href='"
                   . route('admin::users.show', $row->user->id) . "'>{$row->user->name}</a>";
        })->rawColumns(['id', 'user'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function contacts()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Contact::orderBy(
                    'created_at',
                    'DESC'
                )->get([
                    'id',
                    'name',
                    'email',
                    'message',
                    'ip',
                    'read',
                    'user_id',
                    'created_at'
                ]);
            }
        );

        return Datatables::of(
            $query
        )->editColumn('name', function ($row) {
            if (isset($row->user_id)) {
                return "<a class='hint hint--top-right' aria-label='Show User' href='"
                       . route('admin::users.show', $row->user_id) . "'>{$row->name}</a>";
            }
            return $row->name;
        })->editColumn('message', function ($row) {
            $intro = substr($row->message, 0, 50);
            return "<a class='hint hint--top-right' aria-label='Show Message' href='"
                   . route('admin::contacts.show', $row->id) . "'>{$intro}</a>";
        })->editColumn('read', function ($row) {
            if ($row->read) {
                return ICON_CHECK_MARK_GREEN;
            }
            return ICON_X_MARK_RED;
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->rawColumns(['name', 'message', 'read', 'created_at'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function media()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Media::with(['album'])->orderBy('order', 'ASC')->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit " . $row->file_name . "' href='"
                   . route('admin::media.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('album', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Album' href='"
                   . route('admin::albums.show', $row->album_id) . "'>{$row->album->name}</a>";
        })->editColumn('name', function ($row) {
            return $this->getMedia($row);
        })->editColumn('size', function ($row) {
            return formatBytes($row->size);
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['id', 'album', 'name', 'created_at', 'updated_at'])
                         ->removeColumn('file_name')
                         ->make(true);
    }

    /**
     * @param   int $media
     *
     * @return mixed
     * @throws \Exception
     */
    public function mediaAlbums($media)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $media,
            function () use ($media) {
                return Media::whereAlbumId($media)
                            ->with(['album'])
                            ->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit Album' href='"
                   . route('admin::albums.edit', $row->album_id) . "'>{$row->album_id}</a>";
        })->editColumn('album', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Album' href='"
                   . route('admin::albums.show', $row->album_id) . "'>{$row->album->name}</a>";
        })->editColumn('name', function ($row) {
            return $this->getMedia($row);
        })->rawColumns(['id', 'album', 'name'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function statics()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Statics::all(['id', 'slug', 'title', 'created_at', 'updated_at']);
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit' href='"
                   . route('admin::statics.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('slug', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::statics.show', $row->id) . "'>{$row->slug}</a>";
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['id', 'slug', 'created_at', 'updated_at'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function tags()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return Tag::all();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit' href='"
                   . route('admin::tags.edit', $row->id) . "'>{$row->id}</a>";
        })->editColumn('name', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::tags.show', $row->id) . "'>{$row->name}</a>";
        })->rawColumns(['id', 'name'])->make(true);
    }

    /**
     * @param   int $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function tagArticles($id)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $id,
            function () use ($id) {
                return ArticleTag::whereTagId($id)
                                 ->with(
                                     [
                                         'article' => function ($q) {
                                             $q->select('id', 'title');
                                         },
                                         'tag' => function ($r) {
                                             $r->select('id', 'name');
                                         }
                                     ]
                                 )->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit Article' href='"
                   . route('admin::articles.edit', $row->article->id) . "'>{$row->article->id}</a>";
        })->editColumn('name', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Tag' href='"
                   . route('admin::tags.show', $row->tag->id) . "'>{$row->tag->name}</a>";
        })->editColumn('title', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Article' href='"
                   . route('admin::articles.show', $row->article->id) . "'>{$row->article->title}</a>";
        })->rawColumns(['id', 'title', 'name'])->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function users()
    {
        $query = $this->rememberQuery(
            __FUNCTION__,
            function () {
                return User::select(['id', 'name', 'avatar', 'email', 'slug', 'created_at', 'updated_at'])
                           ->get()
                           ->makeVisible(['id', 'email'])
                           ->load(['comments']);
            }
        );

        return Datatables::of(
            $query
        )->editColumn('name', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show' href='"
                   . route('admin::users.show', $row->id) . "'>{$row->name}</a>";
        })->editColumn('avatar', function ($row) {
            return $this->formatAvatar($row->avatar);
        })->addColumn('comments', function ($row) {
            return $row->comments->count();
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['avatar', 'name', 'comments', 'created_at', 'updated_at'])->make(true);
    }

    /**
     * @param   int $userId
     *
     * @return mixed
     * @throws \Exception
     */
    public function userComments($userId)
    {
        $query = $this->rememberQuery(
            __FUNCTION__ . $userId,
            function () use ($userId) {
                return Comment::whereUserId($userId)
                              ->with(
                                  [
                                      'likes',
                                      'article' => function ($q) {
                                          $q->select('id', 'title');
                                      }
                                  ]
                              )->get();
            }
        );

        return Datatables::of(
            $query
        )->editColumn('id', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Edit Comment' href='"
                   . route('admin::comments.edit', $row->id) . "'>{$row->id}</a>";
        })->addColumn('user', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show User' href='"
                   . route('admin::users.show', $row->user->id) . "'>{$row->user->name}</a>";
        })->editColumn('body', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Comment' href='"
                   . route('admin::comments.show', $row->id) . "'>{$row->body}</a>";
        })->addColumn('article', function ($row) {
            return "<a class='hint hint--top-right' aria-label='Show Article' href='"
                   . route('admin::articles.show', $row->article->id) . "'>{$row->article->title}</a>";
        })->addColumn('likes', function ($row) {
            return $row->likes->count();
        })->editColumn('created_at', function ($row) {
            return $this->formatDateOutput($row->created_at);
        })->editColumn('updated_at', function ($row) {
            return $this->formatDateOutput($row->updated_at);
        })->rawColumns(['id', 'user', 'body', 'article', 'created_at', 'updated_at'])->make(true);
    }


    /**
     * @param $row
     *
     * @return string
     */
    protected function getMedia($row)
    {
        if ($row->type != 'image') {
            return "<a class='hint hint--top-right' aria-label='Show File' href='"
                   . route('admin::media.show', $row->id) . "'>" . $this->truncateName($row->name) . "</a>";
        }

        return "<a class='hint hint--top-right' aria-label='Show {$row->name}' href='"
               . route('admin::media.show', $row->id) . "'>"
               . "<img src='"
               . url(Manager::$mediaFolder . '/' . $row->album->name . '/thumbs/small_' . $row->file_name)
               . "'></a>";
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function truncateName($name)
    {
        if (strlen($name > 20)) {
            return '...' . substr($name, -20);
        }

        return $name;
    }

    /**
     * @param $date
     *
     * @return string
     */
    protected function formatDateOutput($date)
    {
        return "<span class='label label-primary'>{$date->format('d-m-Y')}</span>"
             . "<br/><span class='label label-info'>{$date->format('H:i:s')}</span>";
    }

    /**
     * @param string $avatar
     *
     * @return string
     */
    protected function formatAvatar($avatar)
    {
        if ($avatar == null) {
            return '---';
        }

        return "<img src='{$avatar}' height='40px' alt=''>";
    }
}
