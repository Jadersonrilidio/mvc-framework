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
use Jayrods\MvcFramework\Repository\UserRepository\JsonUserRepository;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;
use Jayrods\MvcFramework\Service\MailService;

class ForgetPasswordController extends Controller
{
    use SSLEncryption;

    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    private MailService $mail;

    /**
     * 
     */
    public function __construct(View $view, FlashMessage $flashMsg, UserRepository $userRepository, MailService $mail)
    {
        parent::__construct($view, $flashMsg);

        $this->userRepository = $userRepository;
        $this->mail = $mail;
    }

    /**
     * 
     */
    public function index(Request $request): Response
    {
        $content = $this->view->renderView(
            template: 'auth/forget_password',
            content: array(
                'status' => '',
                'email-value' => '',
                'email-errors' => '',
            )
        );

        $page = $this->view->renderlayout('Forget Password', $content);

        return new Response($page);
    }

    /**
     * 
     */
    public function sendmail(Request $request): Response
    {
        $user = $this->userRepository->findByEmail(
            email: $request->inputs('email')
        );

        if (!$user instanceof User) {
            $user = new User(
                name: 'not registered user',
                email: 'not.registered.user@example.com'
            );
        }

        $token = $this->SSLCrypt(
            data: $user->email() . '=' . time()
        );

        $link = APP_URL . SLASH . "change-password?token=$token";

        $this->mail->sendMail(
            to: $user->email(),
            name: $user->name(),
            subject: 'Password redefinition.',
            body: "Hi there! Click on the following link to define your account password: $link."
        );

        $this->flashMsg->set(array(
            'status-class' => 'mensagem-sucesso',
            'status-message' => 'Email sent, please check your mail box to retrieve your password.'
        ));

        Router::redirect('login');
        exit;
    }
}
