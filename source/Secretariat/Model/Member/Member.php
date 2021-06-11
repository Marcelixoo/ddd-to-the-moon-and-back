<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member;

use DateTimeImmutable;
use Atlas\DDD\Secretariat\Model\Member\AffiliationRegime\CanonicalAffiliation;

class Member
{
    public function __construct(
        string $identifier,
        MemberName $name,
        CernEmail $emailAddress,
        CanonicalAffiliation $canonicalAffiliation
    ) {
        $this->id = $identifier;
        $this->canonicalAffiliation = $canonicalAffiliation;
        $this->name = $name;
        $this->emailAddress = $emailAddress;
        $this->affiliationDate = new DateTimeImmutable("now");
    }

    public function id(): string
    {
        return $this->id;
    }

    public function emailAddress(): CernEmail
    {
        return $this->emailAddress;
    }

    public function canonicalAffiliation(): CanonicalAffiliation
    {
        return $this->canonicalAffiliation;
    }
}