<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Contact;
use App\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Kitano\MediaManager;
use App\Kitano\ApiRepo\UserRepo;
use App\Kitano\Depender\Depender;
use App\Kitano\Delogger\Delogger;
use App\Kitano\ApiRepo\ArticleRepo;
use App\Kitano\MediaManager\Manager;
use App\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    /** @var \App\Kitano\Delogger\Delogger */
    protected $delogger;

    /** @var \App\Kitano\MediaManager\Manager */
    protected $mediaManager;


    /**
     * @param \App\Kitano\Delogger\Delogger    $delogger
     * @param \App\Kitano\MediaManager\Manager $manager
     * @param \Illuminate\Http\Request         $request
     */
    public function __construct(Delogger $delogger, Manager $manager, Request $request)
    {
        parent::__construct($request);

        $this->delogger = $delogger;
        $this->mediaManager = $manager;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $envInfo = $this->getDependerInfo();
        $articles['unpublished'] = $this->getUnpublishedArticles();
        $articles['published'] = app(ArticleRepo::class)->count();
        $users['yesterday'] = $this->getYesterdayUsers();
        $users['today'] = app(UserRepo::class)->count();
        $messages = $this->getMessages();
        $logs = $this->delogger->getLogs();
        $orphans = $this->getOrphans();
        $logErrors = $this->getLogErrors($logs);

        return view('admin.dashboard.index')
               ->with(compact('envInfo'))
               ->with(compact('articles'))
               ->with(compact('messages'))
               ->with(compact('users'))
               ->with(compact('orphans'))
               ->with(compact('logErrors'))
               ->with(compact('logs'));
    }

    /**
     * @return mixed
     */
    protected function getDependerInfo()
    {
        return $this->remember(
            __FUNCTION__,
            function () {
                return Depender::getInfo();
            }
        );
    }

    /**
     * @return mixed
     */
    protected function getUnpublishedArticles()
    {
        return $this->remember(
            __FUNCTION__,
            function () {
                return Article::wherePublished(false)
                              ->count();
            }
        );
    }

    /**
     * @return mixed
     */
    protected function getYesterdayUsers()
    {
        return $this->remember(
            __FUNCTION__,
            function () {
                return User::where(
                    'created_at',
                    '<',
                    Carbon::today()
                          ->toDateTimeString()
                )->count();
            }
        );
    }

    /**
     * @param array $logs
     *
     * @return int
     */
    protected function getLogErrors($logs)
    {
        $res = 0;

        foreach ($logs as $log) {
            if (isset($log['file']) && $log['file']['name'] === 'laravel.log') {
                $res = $log['entries'];
            }
        }

        return $res;
    }

    /**
     * @return array
     */
    protected function getMessages()
    {
        return ['total' => $this->getAllMessages(), 'read' => $this->getUnreadMessages()];
    }

    /**
     * @return mixed
     */
    protected function getAllMessages()
    {
        return $this->remember(
            __FUNCTION__,
            function () {
                return Contact::count();
            }
        );
    }

    /**
     * @return mixed
     */
    protected function getUnreadMessages()
    {
        return $this->remember(
            __FUNCTION__,
            function () {
                return Contact::whereRead(true)->count();
            }
        );
    }

    /**
     * @return mixed
     */
    protected function getOrphans()
    {
        return $this->mediaManager->getOrphans();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function unlinkOrphans()
    {
        $deleted = $this->mediaManager->unlinkOrphans();

        if ($deleted !== true) {
            return back()->with($this->failFileUnlinkStatus($deleted));
        }

        return redirect()->route('admin::dashboard')->with($this->successUnlinkStatus());
    }
}
