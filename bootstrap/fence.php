<?php

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
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