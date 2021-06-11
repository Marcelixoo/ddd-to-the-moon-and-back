<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Features\RegisterMember;

use Atlas\DDD\Secretariat\Model\Member\CernEmail;
use Atlas\DDD\Secretariat\Model\Member\MemberName;

final class NewMemberRequest
{
    /** @var array */
    private $requestData;

    public function __construct(
        string $institutionId,
        string $firstName,
        string $lastName,
        string $emailAddress
    ) {
        $this->requestData["institutionId"] = $institutionId;
        $this->requestData["name"] = new MemberName($firstName, $lastName);
        $this->requestData["emailAddress"] = new CernEmail($emailAddress);

    }

    public function institutionId(): string
    {
        return $this->requestData["institutionId"];
    }

    public function name(): MemberName
    {
        return $this->requestData["name"];
    }

    public function emailAddress(): CernEmail
    {
        return $this->requestData["emailAddress"];
    }
}