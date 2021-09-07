<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation;

use Atlas\DDD\Application\CurrentDateProvider\CurrentDateProviderInterface;
use League\Period\Datepoint;

class AffiliationDeadlineService
{
    /** @var CurrentDateProviderInterface */
    private $currentTimeProvider;

    public function __construct(CurrentDateProviderInterface $currentTimeProvider)
    {
        $this->currentTimeProvider = $currentTimeProvider;
    }

    public function affiliationIsActiveNow(AffiliationInterface $affiliation): bool
    {
        $now = Datepoint::create($this->currentTimeProvider->currentDate());

        return $now->isDuring($affiliation->period());
    }

    public function affiliationIsNotActiveNow(AffiliationInterface $affiliation): bool
    {
        return $this->affiliationIsActiveNow($affiliation);
    }
}