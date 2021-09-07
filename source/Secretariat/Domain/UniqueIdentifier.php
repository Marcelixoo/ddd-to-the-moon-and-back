<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Member;

use Ramsey\Uuid\Uuid;

class UniqueIdentifier
{
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public static function asPrimitive(): string
    {
        return (new self())->toString();
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString()
    {
        return $this->toString();
    }
}