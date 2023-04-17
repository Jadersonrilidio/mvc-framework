<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Infrastructure;

use Jayrods\MvcFramework\Entity\User\Role;
use Jayrods\MvcFramework\Traits\SSLEncryption;
use Jayrods\MvcFramework\Entity\User\User;

class Auth
{
    use SSLEncryption;

    /**
     * 
     */
    private const SESSION_AUTH = 'user';

    /**
     * 
     */
    private static ?User $user = null;

    /**
     * 
     */
    public function authenticate(User $user): bool
    {
        $sessionUser = new User(
            name: $user->name(),
            email: $user->email(),
            emailVerified: $user->emailVerified(),
            password: null,
            id: $user->id(),
            role: $user->role(),
            created_at: $user->createdAt(),
            updated_at: $user->updatedAt()
        );

        if (!$userData = $this->SSLCrypt($sessionUser)) {
            return false;
        }

        $_SESSION[self::SESSION_AUTH] = $userData;

        return true;
    }

    /**
     * 
     */
    public function authUser(): User|false
    {
        if (!is_null(self::$user) and self::$user instanceof User) {
            return self::$user;
        }

        if ($session = $_SESSION[self::SESSION_AUTH] ?? false) {
            $userData = $this->SSLDecrypt($session);

            self::$user = new User(
                name: $userData->name,
                email: $userData->email,
                emailVerified: $userData->emailVerified,
                password: null,
                id: (int) $userData->id,
                role: Role::from($userData->role),
                created_at: $userData->created_at,
                updated_at: $userData->update_at
            );

            return self::$user;
        }

        return false;
    }

    /**
     * 
     */
    public function authLogout(): bool
    {
        if (isset($_SESSION[self::SESSION_AUTH])) {
            unset($_SESSION[self::SESSION_AUTH]);

            return true;
        }

        return false;
    }
}
