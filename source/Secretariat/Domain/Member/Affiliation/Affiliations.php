<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Affiliations
{
    public function __construct(array $affiliations)
    {
        Assert::allIsInstanceOf($affiliations, AffiliationInterface::class);
        Assert::minCount($affiliations, 1);

        $this->affiliations = $affiliations;
    }

    public function add(AffiliationInterface $newAffiliation): void
    {
        foreach ($this->affiliations as $affiliation) {
            if ($affiliation->overlaps($newAffiliation)) {
                throw new OverlappingAffiliationPeriod("Overlapping affiliation periods are forbidden.");
            }
        }
        $this->affiliations[] = $newAffiliation;
    }

    public function toArray(): array
    {
        return $this->affiliations;
    }
}

class OverlappingAffiliationPeriod extends InvalidArgumentException
{
}