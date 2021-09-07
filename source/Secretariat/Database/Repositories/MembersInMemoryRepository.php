<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Database\Repositories;

use RuntimeException;
use Atlas\DDD\Tests\Testing\InMemoryStorage;
use Atlas\DDD\Secretariat\Model\Member\CernEmail;
use Atlas\DDD\Secretariat\Model\Member\Member;
use Atlas\DDD\Secretariat\Model\Member\MembersRepositoryInterface;
use Atlas\DDD\Secretariat\Model\Member\Capabilities\ActiveMember;
use Atlas\DDD\Secretariat\Model\Member\Capabilities\MemberCapabilitiesService;
use Atlas\DDD\Secretariat\Model\Member\UniqueIdentifier;

class MembersInMemoryRepository implements MembersRepositoryInterface
{
    use InMemoryStorage;

    /** @var MemberCapabilitiesService */
    private $memberCapabilitiesService;

    /** @var CernEmail[] */
    private $alreadyTakenEmails;

    public function __construct(
        MemberCapabilitiesService $memberCapabilitiesService,
        DatabaseConnection $connection
    ) {
        $this->memberCapabilitiesService = $memberCapabilitiesService;
        $this->alreadyTakenEmails = [];
        $this->connection = $connection;
    }

    public function add(Member $member): void
    {
        $this->set($member->id(), $member);
        $this->alreadyTakenEmails[] = $member->emailAddress();
    }

    public function nextIdentity(): string
    {
        return (string) new UniqueIdentifier();
    }

    public function findActiveMemberById(string $memberId): ActiveMember
    {
        return $this->memberCapabilitiesService->asActiveMember($this->get($memberId));
    }

    public function checkEmailIsNotYetTaken(CernEmail $uncheckedEmail): void
    {
        foreach ($this->alreadyTakenEmails as $email) {
            if ($email->isEqualTo($uncheckedEmail)) {
                throw new RuntimeException("Email {$uncheckedEmail} has already been taken.");
            }
        }
    }
}
