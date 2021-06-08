<?php

declare(strict_types=1);

namespace Atlas\DDD\Membership\Model;

use JsonSerializable;
use Ramsey\Uuid\Uuid;

final class Institution implements JsonSerializable
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $country;

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