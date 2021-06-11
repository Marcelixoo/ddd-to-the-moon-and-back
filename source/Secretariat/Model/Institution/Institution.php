<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Model\Institution;

use Atlas\DDD\Secretariat\Model\Member\Capabilities\ActiveMember;
use Atlas\DDD\Secretariat\Model\Member\Member;
use InvalidArgumentException;
use JsonSerializable;

final class Institution implements JsonSerializable
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $country;

    /** @var string */
    private $representativeId;

    public function __construct(string $institutionId, string $name, string $country)
    {
        $this->id = $institutionId;
        $this->name = $name;
        $this->country = $country;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function assignAsRepresentative(ActiveMember $candidate): void
    {
        if (! $candidate->isEligibleToRepresentInstitution()) {
            throw new InvalidArgumentException("Only physicists are eligible to be representatives");
        }

        $this->representativeId = $candidate->id();
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "country" => $this->country,
        ];
    }

    public static function fromState(string $stateAsJson): self
    {
        $state = json_decode($stateAsJson);

        return new self(
            $state->id,
            $state->name,
            $state->country
        );
    }
}