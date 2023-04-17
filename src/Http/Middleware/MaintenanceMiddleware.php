<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Http\Middleware;

use Closure;
use Jayrods\MvcFramework\Controller\MaintenanceController;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Http\Middleware\Middleware;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;

class MaintenanceMiddleware implements Middleware
{
    /**
     * 
     */
    public function handle(Request $request, Closure $next): bool
    {
        $maintenance = env('MAINTENANCE', 'false');

        if ($maintenance === 'true') {
            $this->callMaintenanceController($request);
        }

        return call_user_func($next, $request);
    }

    /**
     * 
     */
    private function callMaintenanceController(Request $request): void
    {
        $controller = new MaintenanceController(
            view: new View(),
            flashMsg: new FlashMessage()
        );

        $controller->index($request)->sendResponse();
    }
}
