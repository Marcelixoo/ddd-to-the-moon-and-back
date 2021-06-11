<?php

declare(strict_types=1);

namespace Atlas\DDD\Backoffice\Model;

use Atlas\DDD\Backoffice\Model\InstitutionNotFoundException;

interface InstitutionsRepositoryInterface
{
    public function save(Institution $institution): void;
    /** @throws InstitutionNotFoundException */
    public function findById(string $institutionId): Institution;
}