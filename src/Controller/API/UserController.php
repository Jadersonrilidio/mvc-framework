<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\API;

use Jayrods\MvcFramework\Controller\API\ApiController;
use Jayrods\MvcFramework\Controller\Traits\StandandJsonResponse;
use Jayrods\MvcFramework\Traits\PasswordHandler;
use Jayrods\MvcFramework\Controller\Validation\UserValidator;
use Jayrods\MvcFramework\Entity\User\User;
use Jayrods\MvcFramework\Http\Core\Request;
use Jayrods\MvcFramework\Http\Core\JsonResponse;
use Jayrods\MvcFramework\Repository\UserRepository\UserRepository;

class UserController extends ApiController
{
    use PasswordHandler,
        StandandJsonResponse;

    /**
     * 
     */
    private UserRepository $userRepository;

    /**
     * 
     */
    private UserValidator $userValidator;

    /**
     * 
     */
    public function __construct(UserRepository $userRepository, UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->userValidator = $userValidator;
    }

    /**
     * 
     */
    public function all(Request $request): JsonResponse
    {
        $content = $this->userRepository->all();

        return new JsonResponse($content, 200);
    }

    /**
     * 
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->userValidator->validate($request)) {
            return $this->errorMessagesJsonResponse();
        }

        $user = new User(
            name: $request->inputs('name'),
            email: $request->inputs('email'),
            password: $this->passwordHash($request->inputs('password'))
        );

        if (!$this->userRepository->save($user)) {
            return $this->errorJsonResponse('Not possible to create user.');
        }

        return new JsonResponse($user, 201);
    }

    /**
     * 
     */
    public function find(Request $request): JsonResponse
    {
        $user = $this->userRepository->find((int) $request->uriParams('id'));

        if (!$user instanceof User) {
            return $this->notFoundJsonResponse('User not found.');
        }

        return new JsonResponse($user, 200);
    }

    /**
     * 
     */
    public function update(Request $request): JsonResponse
    {
        if (!$this->userValidator->validate($request)) {
            return $this->errorMessagesJsonResponse();
        }

        $user = $this->userRepository->find((int) $request->uriParams('id'));

        if (!$user instanceof User) {
            return $this->notFoundJsonResponse('User not found.');
        }

        $updatedUser = new User(
            name: $request->inputs('name') ?? $user->name(),
            email: $request->inputs('email') ?? $user->email(),
            emailVerified: $user->emailVerified(),
            password: $user->password(),
            id: $user->id(),
            role: $user->role(),
            created_at: $user->createdAt(),
            updated_at: $user->updatedAt()
        );

        if (!$this->userRepository->save($updatedUser)) {
            return $this->errorJsonResponse('Error on update user.');
        }

        return new JsonResponse($updatedUser, 200);
    }

    /**
     * 
     */
    public function remove(Request $request): JsonResponse
    {
        $user = $this->userRepository->find((int) $request->uriParams('id'));

        if (!$user instanceof User) {
            return $this->notFoundJsonResponse('User not found.');
        }

        $this->userRepository->remove($user);

        return new JsonResponse($user, 200);
    }
}
