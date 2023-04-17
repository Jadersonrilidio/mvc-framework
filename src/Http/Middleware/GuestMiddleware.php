<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Http\Middleware;

use Closure;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Infrastructure\Auth;
use Jayrods\MvcFramework\Http\Middleware\Middleware;

class GuestMiddleware implements Middleware
{
    /**
     * 
     */
    private Auth $auth;

    /**
     * 
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * 
     */
    public function handle(Request $request, Closure $next): bool
    {
        if ($this->auth->authUser()) {
            Router::redirect();
        }

        return call_user_func($next, $request);
    }
}
