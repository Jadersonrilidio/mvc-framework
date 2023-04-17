<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Http\Core;

class Response
{
    /**
     * 
     */
    protected int $httpCode;

    /**
     * 
     */
    protected string $content;

    /**
     * 
     */
    protected string $contentType;

    /**
     * 
     */
    protected array $headers;

    /**
     * 
     */
    public function __construct(string $content, int $httpCode = 200, string $contentType = 'text/html', array $headers = [])
    {
        $this->content = $content;
        $this->contentType = $contentType;
        $this->httpCode = $httpCode;
        $this->headers = $headers;
        $this->addContentTypeToHeaders();
    }

    /**
     * 
     */
    public function sendResponse(): void
    {
        http_response_code($this->httpCode);

        $this->sendHeaders();

        echo $this->content;

        exit;
    }

    /**
     * 
     */
    protected function addContentTypeToHeaders(): void
    {
        $this->headers['Content-Type'] = $this->contentType;
    }

    /**
     * 
     */
    protected function sendHeaders(): void
    {
        foreach ($this->headers as $key => $value) {
            header("$key: $value", true);
        }
    }
}
