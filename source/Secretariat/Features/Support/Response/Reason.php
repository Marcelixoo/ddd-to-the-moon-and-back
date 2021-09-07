<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\Support\Response;

class Reason
{
    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function __toString()
    {
        return $this->reason;
    }
}