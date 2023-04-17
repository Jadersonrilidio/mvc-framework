<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Auth;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Traits\SSLEncryption;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class EmailVerificationController extends Controller
{
    use SSLEncryption;

    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    public function __construct(View $view, FlashMessage $flashMsg, UserRepository $userRepository)
    {
        parent::__construct($view, $flashMsg);

        $this->userRepository = $userRepository;
    }

    /**
     * 
     */
    public function verifyEmail(Request $request): Response
    {
        $token = $request->queryParams('token');

        $email = $this->SSLDecrypt($token);

        $user = $this->userRepository->findByEmail($email);

        $userEmailNotFound = $this->userEmailNotFound(
            user: $user
        );

        $emailAlreadyVerified = $this->emailAlreadyVerified(
            user: $user
        );
        
        if (!$userEmailNotFound or !$emailAlreadyVerified) {
            Router::redirect('login');
        }

        $this->flashMsg->set([
            'status-class' => 'mensagem-sucesso',
            'status-message' => 'Email verified with success. Proceed to login.'
        ]);

        $user->verifyEmail();

        $this->userRepository->save($user);

        Router::redirect('login');
        exit;
    }

    /**
     * 
     */
    private function userEmailNotFound(User|bool $user): bool
    {
        if (!$user instanceof User) {
            $this->flashMsg->set([
                'status-class' => 'mensagem-erro',
                'status-message' => 'Error on email verification: User email not found.'
            ]);

            return false;
        }

        return true;
    }

    /**
     * 
     */
    private function emailAlreadyVerified(User $user): bool
    {
        if ($user->emailVerified()) {
            $this->flashMsg->set([
                'status-class' => 'mensagem-erro',
                'status-message' => 'Email is already verified.'
            ]);

            return false;
        }

        return true;
    }
}
