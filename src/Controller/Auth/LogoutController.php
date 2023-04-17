<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Auth;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Infrastructure\Auth;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;

class LogoutController extends Controller
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
    public function logout(Request $request): Response
    {
        if ($this->auth->authLogout()) {
            $this->flashMsg->set(array(
                'status-class' => 'mensagem-sucesso',
                'status-message' => 'User logged out.',
            ));

            Router::redirect('login');
        }

        Router::redirect();
        exit;
    }
}
