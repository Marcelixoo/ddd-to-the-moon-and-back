<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization\Roles;

use Atlas\DDD\Authorization\AuthenticatedUser;

final class UnprivilegedMember extends AuthenticatedUser
{
    public const ROLE_NAME = "Member";

    public function name(): string
    {
        return self::ROLE_NAME;
    }
}