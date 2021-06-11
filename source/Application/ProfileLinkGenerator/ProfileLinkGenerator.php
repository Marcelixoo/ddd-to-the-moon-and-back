<?php

declare(strict_types=1);

namespace Atlas\DDD\Application\ProfileLinkGenerator;

class ProfileLinkGenerator implements ProfileLinkGeneratorInterface
{
    /** @var string */
    private $uri;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    public function forInstitution(string $institutionId): string
    {
        return "{$this->uri}/institutions/{$institutionId}";
    }
}