<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Validation;

use Jayrods\MvcFramework\Http\Core\Request;

interface Validator
{
    /**
     * 
     */
    public function validate(Request $request): bool;
}
