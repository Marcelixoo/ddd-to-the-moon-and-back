<?php

declare(strict_types=1);

use Atlas\DDD\Application\ApplicationServiceProvider;

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

return $containerBuilder->build();