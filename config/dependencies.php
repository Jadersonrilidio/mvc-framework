<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Jayrods\MvcFramework\Service\MailService;
use PHPMailer\PHPMailer\PHPMailer;

$builder = new ContainerBuilder();

$builder->addDefinitions(array(
    MailService::class => function () {
        $mail = new PHPMailer(ENVIRONMENT === 'production' ? false : true);
        return new MailService($mail);
    }
));

/** @var \Psr\Container\ContainerInterface */
$container = $builder->build();

return $container;
