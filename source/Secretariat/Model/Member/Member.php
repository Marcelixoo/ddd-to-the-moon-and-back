<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member;

use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationInterface;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\Affiliations;

class Member
{
    public function __construct(
        string $identifier,
        MemberName $name,
        CernEmail $emailAddress,
        AffiliationInterface $canonicalAffiliation
    ) {
        $this->id = $identifier;
        $this->name = $name;
        $this->emailAddress = $emailAddress;
        $this->affiliations = new Affiliations([$canonicalAffiliation]);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function emailAddress(): CernEmail
{
        return $this->emailAddress;
    }

    public function addAffiliation(AffiliationInterface $affiliation): void
    {
        $this->affiliations->add($affiliation);
    }

    public function affiliations(): array
    {
        return $this->affiliations->toArray();
    }
}