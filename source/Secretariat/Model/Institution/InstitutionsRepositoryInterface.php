<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Institution;

use Atlas\DDD\Secretariat\Model\Institution\InstitutionNotFoundException;

interface InstitutionsRepositoryInterface
{
    public function save(Institution $institution): void;
    /** @throws InstitutionNotFoundException */
    public function findById(string $institutionId): Institution;
}