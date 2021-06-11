<?php

declare(strict_types=1);

ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');
ini_set('sendmail_path', '/usr/bin/env catchmail -f fence-developers@cern.ch');

$container = require_once __DIR__ . '/../../../bootstrap/bootstrap.php';
$fence = require_once __DIR__ . '/../../../bootstrap/fence.php';

use Atlas\DDD\Application\Pages\Institutes\InstitutesRegistrationPage;
use Fence\Fence;
use Fence\Mailer;

$mailer = new Mailer();

$mailer->set_from("marcelixoo@gmail.com");
$mailer->set_reply_to("marcelixoo@gmail.com");
$mailer->set_subject("Testing Mailcatcher");
$mailer->setRecipient("marcelo.teixeira.dos.santos@outlook.com");
$mailer->set_message("A testing message");
$mailer->fire();

try {
    $fence->add_content($container->get(InstitutesRegistrationPage::class));
    $fence->render();
} catch(\Exception $exception) {
    Fence::handle_exception($exception);
}