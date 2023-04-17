<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Entity;

interface Entity
{
    /**
     * @throws DomainException
     */
    public function identify(int $id): void;
}
