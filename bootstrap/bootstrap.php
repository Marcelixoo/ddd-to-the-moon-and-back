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
| A few sensitive configuration values must be provided to the
| application. This is done through dotenv files ignored by git.
| Good examples of sensitive values are database credentials and
| resource paths.
|
*/

$rootPath = dirname(__DIR__);

if (file_exists("{$rootPath}/.env")) {
    new \Fence\Dotenv($rootPath, ".env");
}

/*
|--------------------------------------------------------------------------
| Builds dependency injection container
|--------------------------------------------------------------------------
|
| In order to leverage the dependency inversion principle,
| DI containers provide well-defined mechanism to autoload
| dependencies and streamline complex dependency chains.
|
| The dependencies come from service providers defined by
| each component of the system.
|
*/
$containerBuilder = new DI\ContainerBuilder();

$containerBuilder->addDefinitions(ApplicationServiceProvider::getDefinitions());

$container = $containerBuilder->build();
$container->set('root_path', $rootPath);

return $container;