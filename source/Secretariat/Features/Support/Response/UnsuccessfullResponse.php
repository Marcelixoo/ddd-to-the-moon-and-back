<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\Support\Response;

class UnuccessfullResponse implements FeatureResponse
{
    /** @var Backtrace */
    private $backtrace;

    public function __construct(Reason ...$reasons)
    {
        $this->backtrace = new Backtrace(...$reasons);
    }

    public static function for(Reason $reason): static
    {
        $self = new self();

        $self->backtrace->track($reason);

        return $self;
    }

    public function and(Reason $reason): static
    {
        $this->backtrace->track($reason);

        return $this;
    }

    public function succeeded(): bool
    {
        return false;
    }

    public function backtrace(): Backtrace
    {
        return $this->backtrace;
    }

    public function payload(): OptionalPayload
    {
        return new OptionalPayload($this->backtrace->toArray());
    }
}