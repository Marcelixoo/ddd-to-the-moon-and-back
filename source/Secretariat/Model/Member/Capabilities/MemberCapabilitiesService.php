<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member\Capabilities;

use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationDeadlineService;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationInterface;
use InvalidArgumentException;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation\Employment;
use Atlas\DDD\Secretariat\Model\Member\Member;

class MemberCapabilitiesService
{
    /** @var AffiliationDeadlineService */
    private $affiliationDeadlineService;

    public function __construct(AffiliationDeadlineService $affiliationDeadlineService)
    {
        $this->affiliationDeadlineService = $affiliationDeadlineService;
    }

    public function asActiveMember(Member $candidate): ActiveMember
    {
        $activeAffiliation = $this->findActiveAffiliation($candidate);

        if (is_null($activeAffiliation)) {
            throw new InvalidArgumentException(
                "Member with id {$candidate->id()} has no active affiliation."
            );
        }

        if (!($activeAffiliation instanceof Employment)) {
            throw new InvalidArgumentException(
                "Member with id {$candidate->id()} has no active employment."
            );
        }

        return new ActiveMember($candidate->id(), $activeAffiliation);
    }

    private function findActiveAffiliation(Member $candidate): ?AffiliationInterface
    {
        foreach ($candidate->affiliations() as $potentialActiveAffiliation) {
            if ($this->affiliationDeadlineService->affiliationIsActiveNow($potentialActiveAffiliation)) {
                return $potentialActiveAffiliation;
            }
        }

        return null;
    }
}