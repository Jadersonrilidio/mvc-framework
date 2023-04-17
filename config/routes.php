<?php

declare(strict_types=1);

/**
 * Routes map, with keys containing the routes and values containing the parameters controller, method and middlewares to execute.
 */
return array(
    // Web routes

    // Web Auth Routes
    'GET|/register' => [Jayrods\MvcFramework\Controller\Auth\RegisterController::class, 'index', ['guest']],
    'POST|/register' => [Jayrods\MvcFramework\Controller\Auth\RegisterController::class, 'register', ['guest']],
    'GET|/login' => [Jayrods\MvcFramework\Controller\Auth\LoginController::class, 'index', ['guest']],
    'POST|/login' => [Jayrods\MvcFramework\Controller\Auth\LoginController::class, 'login', ['guest']],
    'GET|/logout' => [Jayrods\MvcFramework\Controller\Auth\LogoutController::class, 'logout', ['auth']],
    'GET|/delete-account' => [Jayrods\MvcFramework\Controller\Auth\DeleteAccountController::class, 'deleteAccount', ['auth']],
    'GET|/forget-password' => [Jayrods\MvcFramework\Controller\Auth\ForgetPasswordController::class, 'index', ['guest']],
    'POST|/forget-password' => [Jayrods\MvcFramework\Controller\Auth\ForgetPasswordController::class, 'sendMail', ['guest']],
    'GET|/change-password' => [Jayrods\MvcFramework\Controller\Auth\ChangePasswordController::class, 'index', ['guest']],
    'POST|/change-password' => [Jayrods\MvcFramework\Controller\Auth\ChangePasswordController::class, 'alterPassword', ['guest']],
    'GET|/verify-email' => [Jayrods\MvcFramework\Controller\Auth\EmailVerificationController::class, 'verifyEmail', ['guest']],

    // Web Routes
    'GET|/' => [Jayrods\MvcFramework\Controller\HomeController::class, 'index', ['auth']],

    // Web Fallback Route
    'fallback' => [Jayrods\MvcFramework\Controller\NotFoundController::class, 'index'],

    // API Routes

    // Authentication
    // 'GET|/api/auth/login' => [Jayrods\MvcFramework\Controller\API\Auth\JwtAuthController::class, 'login'],
    // 'GET|/api/auth/refresh' => [Jayrods\MvcFramework\Controller\API\Auth\JwtAuthController::class, 'refresh'],
    // 'GET|/api/auth/logout' => [Jayrods\MvcFramework\Controller\API\Auth\JwtAuthController::class, 'logout', ['jwtauth']],
    // 'GET|/api/auth/me' => [Jayrods\MvcFramework\Controller\API\Auth\JwtAuthController::class, 'me', ['jwtauth']],

    // Users
    'GET|/api/users' => [Jayrods\MvcFramework\Controller\API\UserController::class, 'all'],
    'GET|/api/users/{id}' => [Jayrods\MvcFramework\Controller\API\UserController::class, 'find'],
    'POST|/api/users' => [Jayrods\MvcFramework\Controller\API\UserController::class, 'store'],
    'PUT|/api/users/{id}' => [Jayrods\MvcFramework\Controller\API\UserController::class, 'update'],
    'PATCH|/api/users/{id}' => [Jayrods\MvcFramework\Controller\API\UserController::class, 'update'],
    'DELETE|/api/users/{id}' => [Jayrods\MvcFramework\Controller\API\UserController::class, 'remove'],

    // API Fallback Route
    'api-fallback' => [Jayrods\MvcFramework\Controller\API\ApiNotFoundController::class, 'notFound'],
);
