<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Secretariat;

use RuntimeException;
use Ramsey\Uuid\Uuid;
use Atlas\DDD\Secretariat\Features\RegisterMember\MembersRegistrationService;
use Atlas\DDD\Secretariat\Features\RegisterMember\NewMemberRequest;
use Atlas\DDD\Secretariat\Model\Member\CernEmail;
use Atlas\DDD\Secretariat\Model\Member\Member;
use Atlas\DDD\Secretariat\Model\Member\MembersRepositoryInterface;
use Atlas\DDD\Tests\Testing\EmailTestCase;
use Atlas\DDD\Tests\Testing\InMemoryStorage;

final class MemberRegistrationTest extends EmailTestCase
{

    protected function setUp(): void
    {
        $this->container = require 'tests/bootstrap.php';
        parent::setUp();
    }

    /** @test */
    public function a_new_member_is_registered_with_a_unique_cern_email(): void
    {
        $registrationService = new MembersRegistrationService($repository = new MembersInMemoryRepository());

        $newMemberId = $registrationService->register(new NewMemberRequest(
            Uuid::uuid4()->toString(),
            "Marcelo",
            "Teixeira dos Santos",
            "marcelo.teixeira@cern.ch"
        ));

        $fromRepository = $repository->get($newMemberId);

        $this->assertTrue($fromRepository->emailAddress()->isEqualTo(new CernEmail("marcelo.teixeira@cern.ch")));
    }

    /** @test */
    public function members_have_a_canonical_affiliation(): void
    {
        $registrationService = new MembersRegistrationService($repository = new MembersInMemoryRepository());

        $newMemberId = $registrationService->register(new NewMemberRequest(
            $institutionId = Uuid::uuid4()->toString(),
            "Marcelo",
            "Teixeira dos Santos",
            "marcelo.teixeira@cern.ch"
        ));

        $fromRepository = $repository->get($newMemberId);

        $this->assertEquals($fromRepository->canonicalAffiliation()->institutionId(), $institutionId);
    }

}

class MembersInMemoryRepository implements MembersRepositoryInterface
{
    use InMemoryStorage;

    public function add(Member $member): void
    {
        $this->set($member->id(), $member);
    }

    public function nextIdentity(): string
    {
        return Uuid::uuid4()->toString();
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