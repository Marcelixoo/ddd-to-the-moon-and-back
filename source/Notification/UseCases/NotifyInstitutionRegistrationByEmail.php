<?php

declare(strict_types=1);

namespace Atlas\DDD\Notification\UseCases;

final class NotifyInstitutionRegistrationByEmail
{
    public function __construct(Mailer $mailer, ProfileLinkGenerator $profileLinkGenerator)
    {
        $this->mailer = $mailer;
        $this->profileLinkGenerator = $profileLinkGenerator;
    }

    public function __invoke(string $institutionId, string $name, string $country): void
    {
        $this->mailer->send("institution-registered.html.twig", [
            'institution' => [
                'name' => $name,
                'country' => $country,
                'profile' => $this->profileLinkGenerator->forInstitution($institutionId)
            ]
        ]);
    }

    public function onInstitutionRegistered(InstitutionWasRegistered $event): void
    {
        return $this($event->institutionId, $event->name, $event->country);
    }
}