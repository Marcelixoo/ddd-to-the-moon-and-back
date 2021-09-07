<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization\Roles;

use Atlas\DDD\Authorization\AuthenticatedUser;

final class Secretariat extends AuthenticatedUser
{
    public const ROLE_NAME = "Atlas Secretariat";

    public function name(): string
    {
        return self::ROLE_NAME;
    }
}