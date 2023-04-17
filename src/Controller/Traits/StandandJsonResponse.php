<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Traits;

use Jayrods\MvcFramework\Http\Core\JsonResponse;
use Jayrods\MvcFramework\Infrastructure\ErrorMessage;

trait StandandJsonResponse
{
    /**
     * 
     */
    public function errorJsonResponse(string $message, int $httpCode = 400): JsonResponse
    {
        return new JsonResponse(['error' => $message], $httpCode);
    }

    /**
     * 
     */
    public function errorMessagesJsonResponse(): JsonResponse
    {
        return new JsonResponse(['errors' => ErrorMessage::errorMessages()], 400);
    }

    /**
     * 
     */
    public function notFoundJsonResponse(string $message = 'Not found'): JsonResponse
    {
        return new JsonResponse(['error' => $message], 404);
    }

    /**
     * 
     */
    public function forbiddenJsonResponse(string $message = 'Forbidden'): JsonResponse
    {
        return new JsonResponse(['error' => $message], 403);
    }
}
