<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\RegisterInstitution;

final class NewInstitutionRequest
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