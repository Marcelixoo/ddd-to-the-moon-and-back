<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\RegisterInstitution;

use Atlas\DDD\Secretariat\Model\Institution\Institution;
use Atlas\DDD\Secretariat\Model\Institution\InstitutionsRepositoryInterface;
use Atlas\DDD\Secretariat\Notifiers\InstitutionRegistrationNotifier;
use Ramsey\Uuid\Uuid;

final class InstitutionRegistrationService
{
    /** @var InstitutionsRepositoryInterface */
    private $institutionsRepository;

    /** @var InstitutionRegistrationNotifier */
    private $registrationNotifier;

    public function __construct(
        InstitutionsRepositoryInterface $institutionsRepository,
        InstitutionRegistrationNotifier $registrationNotifier
    ) {
        $this->institutionsRepository = $institutionsRepository;
        $this->registrationNotifier = $registrationNotifier;
    }

    public function execute(NewInstitutionRequest $request): string
    {
        $newInstitution = new Institution(Uuid::uuid4()->toString(), $request->name, $request->country);

        $this->institutionsRepository->save($newInstitution);
        $this->registrationNotifier->notify($newInstitution);

        return $newInstitution->id();
    }
}