<?php

declare(strict_types=1);

namespace Atlas\DDD\Backoffice\Features\RegisterInstitution;

use Atlas\DDD\Backoffice\Model\Institution;
use Atlas\DDD\Backoffice\Model\InstitutionsRepositoryInterface;
use Atlas\DDD\Notifications\Notifiers\InstitutionRegistrationNotifier;
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