<?php

declare(strict_types=1);

namespace Atlas\DDD\QualificationTaskManagement\EventListeners\WhenProjectWasRejected;

final class RestartQualificationProject
{
    public function __invoke(ProjectWasRejected $event): void
    {
        $qualification = $this->qualifications->findOneByMemberId($event->memberId);

        $qualification->postponeStartDate();

        $this->qualifications->save($qualification);
    }
}