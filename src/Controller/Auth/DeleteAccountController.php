<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Auth;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Infrastructure\Auth;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class DeleteAccountController extends Controller
{
    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    private Auth $auth;

    /**
     * 
     */
    public function __construct(View $view, FlashMessage $flashMsg, UserRepository $userRepository, Auth $auth)
    {
        parent::__construct($view, $flashMsg);

        $this->userRepository = $userRepository;
        $this->auth = $auth;
    }

    /**
     * 
     */
    public function deleteAccount(Request $request): Response
    {
        $user = $this->auth->authUser();

        if (!$this->isValidUser($user)) {
            Router::redirect();
        }

        $result = $this->userRepository->remove($user);

        if (!$this->userRemoved($result) or !$this->logoutSucceed()) {
            Router::redirect();
        }

        $this->flashMsg->set(array(
            'status-class' => 'mensagem-sucesso',
            'status-message' => 'User account deleted with success.'
        ));

        Router::redirect('login');
        exit;
    }

    /**
     * 
     */
    private function isValidUser(User|false $user): bool
    {
        if (!$user instanceof User) {
            $this->flashMsg->set(array(
                'error-message' => 'Error: not possible to delete user account.',
            ));

            return false;
        }

        return true;
    }

    /**
     * 
     */
    private function userRemoved(int|bool $removalResult): bool
    {
        if (!$removalResult) {
            $this->flashMsg->set(array(
                'error-message' => 'Error: not possible to delete user account.',
            ));

            return false;
        }

        return true;
    }

    /**
     * 
     */
    private function logoutSucceed(): bool
    {
        if (!$this->auth->authLogout()) {
            $this->flashMsg->set(array(
                'error-message' => 'Error: not possible to logout user.',
            ));

            return false;
        }

        return true;
    }
}
