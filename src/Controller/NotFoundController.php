<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;

class NotFoundController extends Controller
{
    /**
     * 
     */
    public function index(Request $request): Response
    {
        $content = $this->view->renderView(template: 'not_found');

        $page = $this->view->renderlayout('404 - Not Found', $content);

        return new Response(
            content: $page,
            httpCode: 404
        );
    }
}
