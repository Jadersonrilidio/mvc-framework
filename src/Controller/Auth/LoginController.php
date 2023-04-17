<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Auth;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Traits\PasswordHandler;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Infrastructure\Auth;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class LoginController extends Controller
{
    use PasswordHandler;

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
    public function index(Request $request): Response
    {
        $statusComponent = $this->view->renderStatusComponent(
            statusClass: $this->flashMsg->get('status-class'),
            statusMessage: $this->flashMsg->get('status-message')
        );

        $emailErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('email-errors')
        );

        $passwordErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('password-errors')
        );

        $content = $this->view->renderView(
            template: 'auth/login',
            content: [
                'status' => $statusComponent,
                'email-errors' => $emailErrorComponent,
                'password-errors' => $passwordErrorComponent,
                'email-value' => $this->flashMsg->get('email-value'),
                'password-value' => $this->flashMsg->get('password-value'),
            ]
        );

        $page = $this->view->renderLayout('Login', $content);

        return new Response($page);
    }

    /**
     * 
     */
    public function login(Request $request): Response
    {
        $user = $this->userRepository->findByEmail(
            email: $request->inputs('email')
        );

        $passwordCheck = $this->passwordVerify(
            password: $request->inputs('password'),
            hash: $user instanceof User ? $user->password() : ''
        );

        $validEmailAndPassword = $this->validEmailAndPassword(
            request: $request,
            passwordCheck: $passwordCheck
        );

        $emailIsVerified = $this->emailIsVerified(
            request: $request,
            user: $user
        );

        if (!$validEmailAndPassword or !$emailIsVerified) {
            Router::redirect('login');
        }

        if ($this->passwordNeedRehash($user->password())) {
            $this->passwordRehash(
                user: $user,
                password: $request->inputs('password')
            );
        }

        $this->auth->authenticate($user);

        $this->flashMsg->set(array(
            'status-class' => 'mensagem-sucesso',
            'status-message' => "Welcome back, {$user->name()}!"
        ));

        Router::redirect();
        exit;
    }

    /**
     * 
     */
    private function validEmailAndPassword(Request $request, bool $passwordCheck): bool
    {
        if (!$passwordCheck) {
            !$this->flashMsg->set([
                'status-class' => 'mensagem-erro',
                'status-message' => 'Invalid email or password.',
                'email-value' => $request->inputs('email'),
                'password-value' => $request->inputs('password'),
            ]);

            return false;
        }

        return true;
    }

    /**
     * 
     */
    private function emailIsVerified(Request $request, User|bool $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if (!$user->emailVerified()) {
            $this->flashMsg->set([
                'status-class' => 'mensagem-erro',
                'status-message' => 'Email not verified.',
                'email-value' => $request->inputs('email'),
                'password-value' => $request->inputs('password'),
            ]);

            return false;
        }

        return true;
    }

    /**
     * 
     */
    private function passwordRehash(User $user, string $password): bool
    {
        return $this->userRepository->save(
            new User(
                name: $user->name(),
                email: $user->email(),
                password: $this->passwordHash($password),
                emailVerified: $user->emailVerified()
            )
        );
    }
}
