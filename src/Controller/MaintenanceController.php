<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;

class MaintenanceController extends Controller
{
    /**
     * 
     */
    public function index(Request $request): Response
    {
        $content = $this->view->renderView(template: 'maintenance');
        $page = $this->view->renderlayout('App Maintenance', $content);

        return new Response($page);
    }
}
