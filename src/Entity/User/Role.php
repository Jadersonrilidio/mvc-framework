<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Entity\User;

enum Role: int
{

    case User = 0;
    case Admin = 1;

    /**
     * 
     */
    public function toString(): string
    {
        return match ($this) {
            self::User => 'user',
            self::Admin => 'admin'
        };
    }
}
