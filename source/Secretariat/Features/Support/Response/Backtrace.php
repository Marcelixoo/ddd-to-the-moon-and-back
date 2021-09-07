<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\Support\Response;

class Backtrace
{
    /** @var Reason[] */
    private $reasons;

    public function __construct(Reason ...$reasonsWhySomethingBroke)
    {
        $this->reasons = $reasonsWhySomethingBroke;
    }

    public function track(Reason $reason): void
    {
        $this->reasons[] = $reason;
    }

    public function toArray(): array
    {
        return array_map("strval", $this->reasons);
    }
}