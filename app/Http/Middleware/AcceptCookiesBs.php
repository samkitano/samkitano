<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Contracts\Foundation\Application;

class AcceptCookiesBs
{
    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;


    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! $response instanceof Response || ! $this->hasBodyTag($response)) {
            return $response;
        }

        return $this->addPermissionScript($response);
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return bool
     */
    protected function hasBodyTag(Response $response)
    {
        return $this->getCloseBodyTag($response->getContent()) !== false;
    }

    /**
     * @param \Illuminate\Http\Response $response
     *
     * @return $this
     */
    protected function addPermissionScript(Response $response)
    {
        $bodyCtt = $response->getContent();
        $bodyTag = $this->getCloseBodyTag($bodyCtt);
        $newCtt = substr($bodyCtt, 0, $bodyTag)
                  . view('front.allow-cookies')->render()
                  . substr($bodyCtt, $bodyTag);

        return $response->setContent($newCtt);
    }

    /**
     * @param string $content
     *
     * @return int|bool
     */
    protected function getCloseBodyTag($content = '')
    {
        return strripos($content, '</body>');
    }
}
