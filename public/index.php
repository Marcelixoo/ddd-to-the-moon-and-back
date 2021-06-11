<?php

declare(strict_types=1);

ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');

$container = require_once __DIR__ . '/../bootstrap/bootstrap.php';
$fence = require_once __DIR__ . '/../bootstrap/fence.php';

use Fence\Fence;
use Atlas\DDD\Application\Pages\IndexPage;

try {
    $fence->add_content($container->get(IndexPage::class));
    $fence->render();
} catch(\Exception $exception) {
    Fence::handle_exception($exception);
}