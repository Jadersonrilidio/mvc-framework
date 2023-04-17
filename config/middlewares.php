<?php

declare(strict_types=1);

return array(
    'maintenance' => Jayrods\MvcFramework\Http\Middleware\MaintenanceMiddleware::class,
    'session' => Jayrods\MvcFramework\Http\Middleware\SessionMiddleware::class,
    'auth' => Jayrods\MvcFramework\Http\Middleware\AuthMiddleware::class,
    'guest' => Jayrods\MvcFramework\Http\Middleware\GuestMiddleware::class,
);
