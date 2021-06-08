<?php

declare(strict_types=1);

namespace Atlas\DDD\Notification\Stories;

use Atlas\DDD\Membership\Model\Institution;
use Fence\Mailer;
use Fence\View\View;

final class NotifyInstitutionRegistrationByEmail
{
    public function __construct(
        Mailer $mailer,
        View $templateEngine
    ) {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
    }

    public function fire(Institution $institution): void
    {
        $messageBody = $this->templateEngine->generateRender(
            "notifications/institution-registered.html.twig", [
            'institution' => [
                'name' => $institution->name(),
                'country' => $institution->country(),
                'profile' => "http://localhost:8000/institutions/{$institution->id()}",
            ]
        ]);

        $this->mailer->set_subject("Institution registration");
        $this->mailer->setRecipient("Atlas.Secretariat@cern.ch");
        $this->mailer->set_message($messageBody);

        $this->mailer->fire();
    }
}