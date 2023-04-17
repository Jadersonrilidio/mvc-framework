<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Auth;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Traits\PasswordHandler;
use Jayrods\MvcFramework\Traits\SSLEncryption;
use Jayrods\MvcFramework\Controller\Validation\ChangePasswordValidator;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class ChangePasswordController extends Controller
{
    use PasswordHandler,
        SSLEncryption;

    /**
     * 
     */
    private const EXPIRATION_TIME = 86400;

    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    private ChangePasswordValidator $changePasswordValidator;

    /**
     * 
     */
    public function __construct(View $view, FlashMessage $flashMsg, UserRepository $userRepository, ChangePasswordValidator $changePasswordValidator)
    {
        parent::__construct($view, $flashMsg);

        $this->changePasswordValidator = $changePasswordValidator;
        $this->userRepository = $userRepository;
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

        $passwordErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('password-errors')
        );

        $passwordConfirmErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('password-confirm-errors')
        );

        $content = $this->view->renderView(
            template: 'auth/change_password',
            content: array(
                'status' => $statusComponent,
                'password-errors' => $passwordErrorComponent,
                'password-confirm-errors' => $passwordConfirmErrorComponent,
                'token-value' => $request->queryParams('token'),
                'password-value' => $this->flashMsg->get('password-value'),
                'password-confirm-value' => $this->flashMsg->get('password-confirm-value'),
            )
        );

        $page = $this->view->renderlayout('Change Passoword', $content);

        return new Response($page);
    }

    /**
     * 
     */
    public function alterPassword(Request $request): Response
    {
        $this->changePasswordValidator->validate($request);

        $token = $this->SSLDecrypt($request->inputs('token'));
        $token = explode('=', $token);

        $email = $token[0];

        $requestTimestamp = $token[1];
        $today = time();
        $elapsedTime = $today - $requestTimestamp;

        if ($elapsedTime > self::EXPIRATION_TIME) {
            $this->flashMsg->set(array(
                'status-class' => 'mensagem-error',
                'status-message' => 'Change password token has expired.',
            ));

            Router::redirect('login');
        }

        $user = $this->userRepository->findByEmail($email);

        $updatedUser = new User(
            name: $user->name(),
            email: $user->email(),
            emailVerified: $user->emailVerified(),
            password: $this->passwordHash($request->inputs('password')),
        );

        if (!$this->userRepository->save($updatedUser)) {
            $this->flashMsg->set(array(
                'status-class' => 'mensagem-erro',
                'status-message' => 'Not possible to update user.',
            ));

            Router::redirect('login');
        }

        $this->flashMsg->set(array(
            'status-class' => 'mensagem-sucesso',
            'status-message' => 'Password redefined with sucess.',
        ));

        Router::redirect('login');
        exit;
    }
}
