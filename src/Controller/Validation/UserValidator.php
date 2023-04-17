<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Validation;

use Jayrods\MvcFramework\Controller\Validation\Validator;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Infrastructure\ErrorMessage;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class UserValidator implements Validator
{
    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * 
     */
    public function validate(Request $request): bool
    {
        $validation = [];

        $validation['name'] = $request->inputs('name')
            ? $this->validateName(name: $request->inputs('name'))
            : true;

        $validation['email'] = $request->inputs('email')
            ? $this->validateEmail(email: $request->inputs('email'), request: $request)
            : true;

        $validation['password'] = ($request->inputs('password') and $request->inputs('password-confirm'))
            ? $this->validatePassword(password: $request->inputs('password'))
            : true;

        $validation['passwordsMatch'] = ($request->inputs('password') and $request->inputs('password-confirm'))
            ? $this->passwordsMatch(password: $request->inputs('password'), passwordConfirm: $request->inputs('password-confirm'))
            : true;

        return $this->check($validation);
    }

    /**
     * 
     */
    private function check(array $validation): bool
    {
        foreach ($validation as $value) {
            if (!$value) {
                return false;
            }
        }

        return true;
    }

    /**
     * 
     */
    public function validateName(string $name): bool
    {
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            ErrorMessage::add('name', 'Invalid username.');
            return false;
        }

        if (strlen($name) > 128) {
            ErrorMessage::add('name', 'Username should have less than 128 characters.');
            return false;
        }

        return true;
    }

    /**
     * 
     */
    public function validateEmail(string $email, Request $request): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ErrorMessage::add('email', 'Invalid email input.');
            return false;
        }

        $tutor = $this->userRepository->findByEmail($email);

        if ($request->httpMethod() == 'POST' and $tutor instanceof User) {
            ErrorMessage::add('email', 'Email already in use.');
            return false;
        }

        if ($request->httpMethod() != 'POST' and $tutor instanceof User and $tutor->id() != $request->uriParams('id')) {
            ErrorMessage::add('email', 'Email already in use.');
            return false;
        }

        if (strlen($email) > 128) {
            ErrorMessage::add('email', 'Email should have less than 128 characters.');
            return false;
        }

        return true;
    }

    /**
     * 
     */
    public function validatePassword(string $password): bool
    {
        if (!preg_match('/^[a-zA-Z0-9\.\_\#]+$/', $password)) {
            ErrorMessage::add('password', 'Invalid password input.');
            return false;
        }

        if (strlen($password) < 8) {
            ErrorMessage::add('password', 'Password should have at least 8 characters.');
            return false;
        }

        if (strlen($password) > 128) {
            ErrorMessage::add('password', 'Password should have less than 128 characters.');
            return false;
        }

        return true;
    }

    /**
     * 
     */
    public function passwordsMatch(string $password, string $passwordConfirm): bool
    {
        if ($password !== $passwordConfirm) {
            ErrorMessage::add('password', 'Passwords does not match.');
            ErrorMessage::add('password-confirm', 'Passwords does not match.');
            return false;
        }

        return true;
    }
}
