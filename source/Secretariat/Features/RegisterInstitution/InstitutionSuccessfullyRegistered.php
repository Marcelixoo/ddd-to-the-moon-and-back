<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\RegisterInstitution;

use Atlas\DDD\Secretariat\Features\Support\Response\OptionalPayload;
use Atlas\DDD\Secretariat\Features\Support\Response\SuccessfullResponse;

final class InstitutionSuccessfullyRegistered extends SuccessfullResponse
{
    /** @var array */
    private $payload;

    public function __construct(string $institutionId)
    {
        $this->payload["institutionId"] = $institutionId;
    }

    public function payload(): OptionalPayload
    {
        return new OptionalPayload($this->payload);
    }
}