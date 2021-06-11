<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\AffiliationRegime;

interface Affiliation
{
    public function institutionId(): string;
    public function isActiveNow(): bool;
    public function isNotActiveNow(): bool;
}