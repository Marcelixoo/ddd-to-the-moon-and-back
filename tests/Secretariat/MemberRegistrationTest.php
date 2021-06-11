<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Secretariat;

use DateTimeImmutable;
use Atlas\DDD\Application\CurrentDateProvider\CurrentDateProviderInterface;
use Atlas\DDD\Tests\Testing\EmailTestCase;
use Atlas\DDD\Application\IdentifierGenerator\UuidGenerator;
use Atlas\DDD\Secretariat\Features\RegisterMember\MembersRegistrationService;
use Atlas\DDD\Secretariat\Features\RegisterMember\NewMemberRequest;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationDeadlineService;
use Atlas\DDD\Secretariat\Model\Member\Capabilities\MemberCapabilitiesService;
use Atlas\DDD\Secretariat\Model\Member\CernEmail;
use Atlas\DDD\Secretariat\Repositories\MembersInMemoryRepository;

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
        $registrationService = new MembersRegistrationService($repository = new MembersInMemoryRepository(new MemberCapabilitiesService(new AffiliationDeadlineService(new Today()))));

        $newMemberId = $registrationService->register(new NewMemberRequest(
            "Marcelo",
            "Teixeira dos Santos",
            "marcelo.teixeira@cern.ch",
            $this->generateIdentity(),
            "student",
            "19-11-2020",
            "19-11-2022",
        ));

        $fromRepository = $repository->get($newMemberId);

        $this->assertTrue($fromRepository->emailAddress()->isEqualTo(new CernEmail("marcelo.teixeira@cern.ch")));
    }

    private function generateIdentity(): string
    {
        return (new UuidGenerator())->generateNewIdentifier();
    }
}

class Today implements CurrentDateProviderInterface
{
    public function currentDate(): DateTimeImmutable
    {
        return new DateTimeImmutable("2021-06-11");
    }
}