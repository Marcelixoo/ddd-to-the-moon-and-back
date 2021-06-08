<?php

declare(strict_types=1);

use Atlas\DDD\Application\ApplicationServiceProvider;
use Atlas\DDD\Notification\Stories\NotifyInstitutionRegistrationByEmail;
use Fence\Mailer;
use Fence\View;
use Psr\Container\ContainerInterface;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load environment files
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$path = dirname(__DIR__);

if (file_exists("{$path}/.env")) {
    new \Fence\Dotenv($path, ".env");
}

if (file_exists("{$path}/.env.testing")) {
    new \Fence\Dotenv($path, ".env.testing");
}

/*
|--------------------------------------------------------------------------
| Builds dependency injection container
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(ApplicationServiceProvider::getDefinitions());
$containerBuilder->addDefinitions([
    'root_path' => dirname(__DIR__),
    NotifyInstitutionRegistrationByEmail::class => function (ContainerInterface $c) {
        return new NotifyInstitutionRegistrationByEmail(new Mailer(), $c->get(View::class));
    }
]);

return $containerBuilder->build();