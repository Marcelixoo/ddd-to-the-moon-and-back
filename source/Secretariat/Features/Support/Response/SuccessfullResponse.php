<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\Support\Response;

abstract class SuccessfullResponse implements FeatureResponse
{
    public abstract function payload(): OptionalPayload;

    public function succeeded(): bool
    {
        return true;
    }
}