<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Http\Middleware;

use Closure;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Middleware\Middleware;

class SessionMiddleware implements Middleware
{
    /**
     * 
     */
    public function handle(Request $request, Closure $next): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return call_user_func($next, $request);
    }
}
