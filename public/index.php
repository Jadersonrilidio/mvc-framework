<?php

declare(strict_types=1);

use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Middleware\MiddlewareQueue;
use Psr\Container\ContainerInterface;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

/** @var ContainerInterface */
$diContainer = require CONFIG_DIR . 'dependencies.php';

$routes = require CONFIG_DIR . 'routes.php';

$router = new Router(
    request: new Request(),
    middlewareQueue: new MiddlewareQueue(),
    diContainer: $diContainer,
    routes: $routes 
);

$router->handleRequest()->sendResponse();
