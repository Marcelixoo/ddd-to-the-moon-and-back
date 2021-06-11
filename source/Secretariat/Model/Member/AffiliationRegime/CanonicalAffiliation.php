<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\AffiliationRegime;

use League\Period\Datepoint;
use League\Period\Period;

class CanonicalAffiliation implements Affiliation
{
    public function __construct(string $institutionId)
    {
        $this->institutionId = $institutionId;
        $this->period = Period::around("now", "1 YEAR");
    }

    public function institutionId(): string
    {
        return $this->institutionId;
    }

    public function isActiveNow(): bool
    {
        return Datepoint::create("now")->isDuring($this->period);
    }

    public function isNotActiveNow(): bool
    {
        return ! $this->isActiveNow();
    }
}