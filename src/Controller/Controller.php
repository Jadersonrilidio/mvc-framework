<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller;

use Jayrods\MvcFramework\Http\Core\View;
use Jayrods\MvcFramework\Infrastructure\FlashMessage;

abstract class Controller
{
    /**
     * 
     */
    protected View $view;

    /**
     * 
     */
    protected FlashMessage $flashMsg;

    /**
     * 
     */
    public function __construct(View $view, FlashMessage $flashMsg)
    {
        $this->view = $view;
        $this->flashMsg = $flashMsg;
    }
}
