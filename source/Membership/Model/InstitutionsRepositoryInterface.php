<?php

declare(strict_types=1);

namespace Atlas\DDD\Membership\Model;

use Ramsey\Uuid\Rfc4122\UuidInterface;
use Atlas\DDD\Membership\Model\InstitutionNotFoundException;

interface InstitutionsRepositoryInterface
{
    public function save(Institution $institution): void;
    /** @throws InstitutionNotFoundException */
    public function findById(string $institutionId): Institution;
}