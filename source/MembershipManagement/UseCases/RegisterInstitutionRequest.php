<?php

declare(strict_types=1);

namespace Atlas\DDD\MembershipManagement\UseCases\RegisterInstitution;

final class RegisterInstitutionRequest
{
    /** @var string */
    public $name;

    /** @var string */
    public $country;

    public function __construct(string $name, string $country)
    {
        $this->name = $name;
        $this->country = $country;
    }
}