<?php

declare(strict_types=1);

namespace Atlas\DDD\Membership\Stories\RegisterInstitution;

use Atlas\DDD\Membership\Model\Institution;
use Atlas\DDD\Membership\Model\InstitutionsRepositoryInterface;
use Atlas\DDD\Notification\Stories\NotifyInstitutionRegistrationByEmail;
use Ramsey\Uuid\Uuid;

final class RegisterInstitutionCommand
{
    /** @var InstitutionsRepositoryInterface */
    private $institutionsRepository;

    /** @var NotifyInstitutionRegistrationByEmail */
    private $registrationNotifier;

    public function __construct(
        InstitutionsRepositoryInterface $institutionsRepository,
        NotifyInstitutionRegistrationByEmail $registrationNotifier
    ) {
        $this->institutionsRepository = $institutionsRepository;
        $this->registrationNotifier = $registrationNotifier;
    }

    public function execute(RegisterInstitutionRequest $request): string
    {
        $newInstitution = new Institution(Uuid::uuid4()->toString(), $request->name, $request->country);

        $this->institutionsRepository->save($newInstitution);
        $this->registrationNotifier->fire($newInstitution);

        return $newInstitution->id();
    }
}