<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

final class Member extends AuthenticatedUser
{
    public const ROLE_NAME = "Member";

    public function authenticate($aux_data = null)
    {
        $this->isClearedAs(self::ROLE_NAME);
    }
}