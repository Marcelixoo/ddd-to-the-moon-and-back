<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member;

use Atlas\DDD\Secretariat\Model\Member\Capabilities\ActiveMember;

interface MembersRepositoryInterface
{
    public function checkEmailIsNotYetTaken(CernEmail $email): void;
    public function nextIdentity(): string;
    public function add(Member $member): void;
    public function findActiveMemberById(string $memberId): ActiveMember;
}