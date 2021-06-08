<?php

/*
|--------------------------------------------------------------------------
| Bootstrap the framework
|--------------------------------------------------------------------------
|
| Fence provides a king of entrypoint entity that bootstraps the
| whole application in one go and takes care of loading assets
| and authenticating the current logged user.
|
*/

use Fence\Fence;

try {
    $fence = new Fence();
    $fence->authenticate_user($_POST);
    $fence->add_script('/js/app.bundle.js');
    $fence->add_stylesheet('/fence/stylesheets/fence.css');
    $fence->add_stylesheet('/css/app.css');
    return $fence;
} catch(\Exception $exception) {
    Fence::handle_exception($exception);
}