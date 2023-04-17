<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\API;

use Jayrods\MvcFramework\Controller\API\ApiController;
use Jayrods\MvcFramework\Http\Core\JsonResponse;
use Jayrods\MvcFramework\Http\Core\Request;

class ApiNotFoundController extends ApiController
{
    /**
     * 
     */
    public function notFound(Request $request): JsonResponse
    {
        $content = ['error' => 'resource not found.'];

        return new JsonResponse($content, 404);
    }
}
