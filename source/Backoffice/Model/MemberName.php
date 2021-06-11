<?php

declare(strict_types=1);

namespace Atlas\DDD\Backoffice\Model;

class MemberName
{
    public function __construct(
        string $firstName,
        string $lastName
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function first(): string
    {
        return $this->firstName;
    }

    public function last(): string
    {
        return $this->lastName;
    }
}