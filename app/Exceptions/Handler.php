<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    private $r;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $this->r = $request;

        if ($exception instanceof ModelNotFoundException) {
            return $this->modelNotFoundResponse($exception);
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->httpNotFoundResponse($exception);
        } elseif ($exception instanceof TokenMismatchException) {
            return $this->tokenMismatchResponse($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('admin::login'));
    }

    /**
     * @return string
     */
    protected function getArea()
    {
        return $this->r->segment(1) === 'admin' ? 'admin' : 'front';
    }

    /**
     * @param \Illuminate\Database\Eloquent\ModelNotFoundException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function modelNotFoundResponse($exception)
    {
        $area = $this->getArea();

        Log::warning($exception->getMessage());

        if ($this->r->expectsJson()) {
            return response()->json(['error' => true, 'message' => 'Not Found'], 404);
        }

        $status = [
            'title' => 'Model Not Found',
            'type' => 'error',
            'message' => $exception->getMessage(),
        ];

        return response()->view("{$area}.errors", ['status' => $status], 404);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function httpNotFoundResponse($exception)
    {
        $area = $this->getArea();

        Log::warning("404: {$this->r->getUri()} - {$this->r->ip()}");

        if ($this->r->expectsJson()) {
            return response()->json(['error' => true, 'message' => 'Not Found'], 404);
        }

        $status = [
            'title' => '404 - Not Found',
            'type' => 'error',
            'message' => 'No such page.',
        ];

        return response()->view("{$area}.errors", ['status' => $status], 404);
    }

    /**
     * @param \Illuminate\Session\TokenMismatchException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function tokenMismatchResponse($exception)
    {
        if ($this->r->expectsJson()) {
            return response()->json([
                'error' => true,
                'message' => 'Session expired! Please refresh this page and try again.'
            ], 500);
        }

        $status = [
            'title' => 'Token Mismatch',
            'type' => 'error',
            'message' => 'Please refresh this page and try again.',
        ];

        $area = $this->getArea();

        if ($area === 'admin') {
            $status['message'] = 'Session expired. Please try again.';
            return back()->with('status', $status)->withInput();
        }

        return response()->view("{$area}.errors", ['status' => $status], 500);
    }
}
