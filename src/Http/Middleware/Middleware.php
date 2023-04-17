<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Http\Middleware;

use Closure;
use Jayrods\MvcFramework\Http\Core\Request;

interface Middleware
{
    /**
     * 
     */
    public function handle(Request $request, Closure $next): bool;
}
