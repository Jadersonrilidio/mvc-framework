<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Infrastructure\Auth;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;

class HomeController extends Controller
{
    /**
     * 
     */
    private Auth $auth;

    /**
     * 
     */
    public function __construct(View $view, FlashMessage $flashMsg, Auth $auth)
    {
        parent::__construct($view, $flashMsg);

        $this->auth = $auth;
    }
    /**
     * 
     */
    public function index(Request $request): Response
    {
        $user = $this->auth->authUser();

        $statusComponent = $this->view->renderStatusComponent(
            statusClass: $this->flashMsg->get('status-class'),
            statusMessage: $this->flashMsg->get('status-message')
        );

        $content = $this->view->renderView(
            template: 'home',
            content: array(
                'status' => $statusComponent,
                'user-name' => $user->name(),
                'user-email' => $user->email(),
                'error-message' => $this->flashMsg->get('error-message')
            )
        );

        $page = $this->view->renderlayout('Home', $content);

        return new Response($page);
    }
}
