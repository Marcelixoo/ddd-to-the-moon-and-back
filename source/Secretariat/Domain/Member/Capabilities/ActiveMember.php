<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Capabilities;

use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation\Employment;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation\Physicist;

class ActiveMember
{
    /** @var Employment */
    private $activeEmployment;

    public function __construct(string $memberId, Employment $activeEmployment)
    {
        $this->memberId = $memberId;
        $this->activeEmployment = $activeEmployment;
    }

    public function id(): string
    {
        return $this->memberId;
    }

    public function isEligibleToRepresentInstitution(): bool
    {
        return $this->activeEmployment instanceof Physicist;
    }
}