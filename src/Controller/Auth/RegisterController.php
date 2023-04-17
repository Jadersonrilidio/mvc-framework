<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Auth;

use Jayrods\MvcFramework\Controller\Controller;
use Jayrods\MvcFramework\Traits\PasswordHandler;
use Jayrods\MvcFramework\Traits\SSLEncryption;
use Jayrods\MvcFramework\Controller\Validation\RegisterValidator;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Response;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;
use Jayrods\MvcFramework\Service\MailService;

class RegisterController extends Controller
{
    use PasswordHandler,
        SSLEncryption;

    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    private RegisterValidator $registerValidator;

    /**
     * 
     */
    private MailService $mail;

    /**
     * 
     */
    public function __construct( View $view, FlashMessage $flashMsg, UserRepository $userRepository, RegisterValidator $registerValidator, MailService $mail)
    {
        parent::__construct($view, $flashMsg);

        $this->registerValidator = $registerValidator;
        $this->userRepository = $userRepository;
        $this->mail = $mail;
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

        $nameErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('name-errors')
        );

        $emailErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('email-errors')
        );

        $passwordErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('password-errors')
        );

        $passwordConfirmErrorComponent = $this->view->renderErrorMessageComponent(
            errorMessages: $this->flashMsg->getArray('password-confirm-errors')
        );

        $content = $this->view->renderView(
            template: 'auth/register',
            content: array(
                'status' => $statusComponent,
                'name-errors' => $nameErrorComponent,
                'email-errors' => $emailErrorComponent,
                'password-errors' => $passwordErrorComponent,
                'password-confirm-errors' => $passwordConfirmErrorComponent,
                'name-value' => $this->flashMsg->get('name-value'),
                'email-value' => $this->flashMsg->get('email-value'),
                'password-value' => $this->flashMsg->get('password-value'),
                'password-confirm-value' => $this->flashMsg->get('password-confirm-value'),
            )
        );

        $page = $this->view->renderLayout('Register', $content);

        return new Response($page);
    }

    /**
     * 
     */
    public function register(Request $request): Response
    {
        $this->registerValidator->validate($request);

        $user = new User(
            name: $request->inputs('name'),
            email: $request->inputs('email'),
            password: $this->passwordHash($request->inputs('password'))
        );

        $this->userRepository->save($user);

        $token = $this->SSLCrypt($user->email());

        $link = APP_URL . SLASH . "verify-email?token=$token";

        $this->mail->sendMail(
            to: $user->email(),
            name: $user->name(),
            subject: 'User account verification.',
            body: "Hi there! Click on the following link to verify your account: $link."
        );

        Router::redirect('login');
        exit;
    }
}
