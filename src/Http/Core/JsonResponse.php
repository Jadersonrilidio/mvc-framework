<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Http\Core;

use Jayrods\MvcFramework\Http\Core\Response;

class JsonResponse extends Response
{
    /**
     * 
     */
    public function __construct(mixed $content, int $httpCode = 200, string $contentType = 'application/json', array $headers = [])
    {
        parent::__construct(json_encode($content), $httpCode, $contentType, $headers);
    }
}
