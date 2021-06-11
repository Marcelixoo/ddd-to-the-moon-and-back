<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member;

use DateTimeImmutable;

class Member
{
    public function __construct(
        string $identifier,
        string $institutionId,
        MemberName $name,
        CernEmail $emailAddress
    ) {
        $this->id = $identifier;
        $this->institutionId = $institutionId;
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
}