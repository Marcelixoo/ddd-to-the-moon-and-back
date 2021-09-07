<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\RegisterMember;

use Atlas\DDD\Secretariat\Model\Member\CernEmail;
use Atlas\DDD\Secretariat\Model\Member\MemberName;
use Atlas\DDD\Secretariat\Model\Member\Affiliation\AffiliationType\LongTermAssociation\LongTermAssociation;

final class MemberRegistrationRequest
{
    /** @var array */
    private $requestData;

    public function __construct(
        string $firstName,
        string $lastName,
        string $emailAddress,
        string $institutionId,
        string $employmentTitle,
        string $employmentStartDate,
        string $employmentEndDate
    ) {
        $this->requestData["name"] = new MemberName($firstName, $lastName);
        $this->requestData["emailAddress"] = new CernEmail($emailAddress);
        $this->requestData["affiliation"] = LongTermAssociation::fromPrimitives(
            $institutionId,
            $employmentTitle,
            $employmentStartDate,
            $employmentEndDate
        );

    }

    public function affiliation(): LongTermAssociation
    {
        return $this->requestData["affiliation"];
    }

    public function name(): MemberName
    {
        return $this->requestData["name"];
    }

    public function emailAddress(): CernEmail
    {
        return $this->requestData["emailAddress"];
    }

    public static function fromArray(array $requestData): self
    {
        return new self();
    }
}