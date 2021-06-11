<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Notifiers;

use Fence\Mailer;
use Fence\View\View;
use Atlas\DDD\Application\ProfileLinkGenerator\ProfileLinkGeneratorInterface;
use Atlas\DDD\Secretariat\Model\Institution\Institution;

final class InstitutionRegistrationNotifier
{
    public function __construct(
        Mailer $mailer,
        View $templateEngine,
        ProfileLinkGeneratorInterface $profileLinkGenerator
    ) {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
        $this->profileLinkGenerator = $profileLinkGenerator;
    }

    public function notify(Institution $institution): void
    {
        $messageBody = $this->templateEngine->generateRender(
            "notifications/institution-registered.html.twig", [
            'institution' => [
                'name' => $institution->name(),
                'country' => $institution->country(),
                'profile' => $this->profileLinkGenerator->forInstitution($institution->id()),
            ]
        ]);
        $this->mailer->set_subject("Institution registration");
        $this->mailer->setRecipient("Atlas.Secretariat@cern.ch");
        $this->mailer->set_message($messageBody);
        $this->mailer->fire();
    }
}