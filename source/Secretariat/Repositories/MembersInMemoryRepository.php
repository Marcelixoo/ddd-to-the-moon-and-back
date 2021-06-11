<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Repositories;

use RuntimeException;
use Atlas\DDD\Tests\Testing\InMemoryStorage;
use Atlas\DDD\Application\IdentifierGenerator\UuidGenerator;
use Atlas\DDD\Secretariat\Model\Member\CernEmail;
use Atlas\DDD\Secretariat\Model\Member\Member;
use Atlas\DDD\Secretariat\Model\Member\MembersRepositoryInterface;
use Atlas\DDD\Secretariat\Model\Member\Capabilities\ActiveMember;
use Atlas\DDD\Secretariat\Model\Member\Capabilities\MemberCapabilitiesService;

class MembersInMemoryRepository implements MembersRepositoryInterface
{
    use InMemoryStorage;

    /** @var MemberCapabilitiesService */
    private $memberCapabilitiesService;

    public function __construct(
        MemberCapabilitiesService $memberCapabilitiesService
    ) {
        $this->memberCapabilitiesService = $memberCapabilitiesService;
    }

    public function add(Member $member): void
    {
        $this->set($member->id(), $member);
    }

    public function nextIdentity(): string
    {
        return (new UuidGenerator)->generateNewIdentifier();
    }

    public function findActiveMemberById(string $memberId): ActiveMember
    {
        return $this->memberCapabilitiesService->asActiveMember($this->get($memberId));
    }

    public function checkEmailIsNotYetTaken(CernEmail $email): void
    {
        foreach ($this->storage as $member) {
            if ($member->emailAddress()->isEqualTo($email)) {
                throw new RuntimeException("Email {$email} has already being taken.");
            }
        }
    }
}