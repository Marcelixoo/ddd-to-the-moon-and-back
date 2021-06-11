<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Affiliation;

use League\Period\Period;

interface AffiliationInterface
{
    public function period(): Period;
    public function overlaps(AffiliationInterface $other): bool;
}