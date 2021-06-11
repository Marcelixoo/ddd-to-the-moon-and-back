<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member;

interface MembersRepositoryInterface
{
    public function checkEmailIsNotYetTaken(CernEmail $email): void;
    public function nextIdentity(): string;
    public function add(Member $member): void;
}