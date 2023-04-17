<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Entity\User;

use DomainException;
use Jayrods\MvcFramework\Entity\EntityWithDates;
use Jayrods\MvcFramework\Entity\User\Role;
use JsonSerializable;

class User extends EntityWithDates implements JsonSerializable
{
    /**
     * 
     */
    protected ?int $id;

    /**
     * 
     */
    protected string $name;

    /**
     * 
     */
    protected string $email;

    /**
     * 
     */
    protected bool $emailVerified;

    /**
     * 
     */
    protected ?string $password;

    /**
     * 
     */
    protected Role $role;

    /**
     * 
     */
    public function __construct(
        string $name,
        string $email,
        bool $emailVerified = false,
        ?string $password = null,
        ?int $id = null,
        Role $role = Role::User,
        ?string $created_at = null,
        ?string $updated_at = null,
    ) {
        parent::__construct($created_at, $updated_at);

        $this->name = $name;
        $this->email = $email;
        $this->emailVerified = $emailVerified;
        $this->password = $password;
        $this->id = $id;
        $this->role = $role;
    }

    /**
     * @throws DomainException
     */
    public function identify(int $id): void
    {
        if (!is_null($this->id)) {
            throw new DomainException('User already has identity.');
        }

        $this->id = $id;
    }

    /**
     * 
     */
    public function verifyEmail(): void
    {
        $this->emailVerified = true;
    }

    /**
     * 
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * 
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * 
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * 
     */
    public function emailVerified(): bool
    {
        return $this->emailVerified;
    }

    /**
     * 
     */
    public function password(): ?string
    {
        return $this->password;
    }

    /**
     * 
     */
    public function becomeUser(): void
    {
        $this->role = Role::User;
    }

    /**
     * 
     */
    public function becomeAdmin(): void
    {
        $this->role = Role::Admin;
    }

    /**
     * 
     */
    public function role(): Role
    {
        return $this->role;
    }

    /**
     * 
     */
    public function jsonSerialize(): mixed
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'emailVerified' => $this->emailVerified,
            'password' => $this->password,
            'role' => $this->role->value,
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
        );
    }
}
