<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\Support\Response;

interface FeatureResponse
{
    public function payload(): OptionalPayload;
    public function succeeded(): bool;
}