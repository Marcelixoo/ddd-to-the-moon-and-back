<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\Support\Response;

class OptionalPayload
{
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function __get($property)
    {
        if (array_key_exists($property, $this->payload)) {
            return $this->payload[$property];
        }
        return null;
    }
}