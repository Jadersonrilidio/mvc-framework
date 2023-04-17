<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Validation;

use Jayrods\MvcFramework\Traits\PasswordHandler;
use Jayrods\MvcFramework\Traits\SSLEncryption;
use Jayrods\MvcFramework\Controller\Validation\Validator;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\Router;
use Jayrods\MvcFramework\Infrastructure\Auth;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class ChangePasswordValidator implements Validator
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
    private FlashMessage $flashMsg;

    /**
     * 
     */
    private Auth $auth;

    /**
     * 
     */
    public function __construct(FlashMessage $flashMsg, UserRepository $userRepository, Auth $auth)
    {
        $this->userRepository = $userRepository;
        $this->flashMsg = $flashMsg;
        $this->auth = $auth;
    }

    /**
     * 
     */
    public function validate(Request $request): bool
    {
        $passwordHasAtLeastTenChars = $this->validatePasswordHasAtLeastTenChars(
            password: $request->inputs('password')
        );

        $passwordsMatch = $this->validatePasswordsMatch(
            password: $request->inputs('password'),
            passwordConfirm: $request->inputs('password-confirm')
        );

        if (!$passwordHasAtLeastTenChars or !$passwordsMatch) {
            $this->flashMsg->set([
                'status-class' => 'mensagem-erro',
                'status-message' => 'Error: Invalid inputs.',
                'password-value' => $request->inputs('password'),
                'password-confirm-value' => $request->inputs('password-confirm'),
            ]);

            Router::redirect('change-password?token=' . $request->inputs('token'));
        }

        return true;
    }

    /**
     * 
     */
    private function validatePasswordHasAtLeastTenChars(string $password): bool
    {
        if (strlen($password) < 10) {
            $this->flashMsg->add([
                'password-errors' => 'Password must have at least 10 characters.'
            ]);

            return false;
        }

        return true;
    }

    /**
     * 
     */
    private function validatePasswordsMatch(string $password, string $passwordConfirm): bool
    {
        if ($password !== $passwordConfirm) {
            $this->flashMsg->add([
                'password-errors' => 'Passwords does not match.',
                'password-confirm-errors' => 'Passwords does not match.'
            ]);

            return false;
        }

        return true;
    }
}
