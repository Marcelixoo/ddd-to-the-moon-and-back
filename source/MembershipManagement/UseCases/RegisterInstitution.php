<?php

declare(strict_types=1);

namespace Atlas\DDD\MembershipManagement\UseCases\RegisterInstitution;

use Atlas\DDD\MembershipManagement\Model\Institution;
use Atlas\DDD\MembershipManagement\Model\InstitutionsRepositoryInterface;
use Ramsey\Uuid\Uuid;

final class RegisterInstitutionCommand
{
    /** @var InstitutionsRepositoryInterface */
    private $institutionsRepository;

    public function __construct(
        InstitutionsRepositoryInterface $institutionsRepository
    ) {
        $this->institutionsRepository = $institutionsRepository;
    }

    public function execute(RegisterInstitutionRequest $request): string
    {
        $newInstitution = new Institution(Uuid::uuid4()->toString(), $request->name, $request->country);

        $this->institutionsRepository->save($newInstitution);

        return $newInstitution->id();
    }
}