<?php

declare(strict_types=1);

namespace Atlas\DDD\Application\ProfileLinkGenerator;

interface ProfileLinkGeneratorInterface
{
    public function forInstitution(string $institutionId): string;
}