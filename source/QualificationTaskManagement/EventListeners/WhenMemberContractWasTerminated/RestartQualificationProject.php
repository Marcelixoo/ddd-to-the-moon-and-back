<?php

declare(strict_types=1);

namespace Atlas\DDD\QualificationTaskManagement\EventListeners\WhenMemberContractWasTerminated;

final class RestartQualificationProject
{
    public function __invoke(MemberContractWasTerminated $event): void
    {
        $qualification = $this->qualifications->findOneByMemberId($event->memberId);

        $qualification->reset();
    }
}