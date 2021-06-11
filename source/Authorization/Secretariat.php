<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

final class Secretariat extends AuthenticatedUser
{
    public const ROLE_NAME = "Atlas Secretariat";

    public function authenticate($aux_data = null)
    {
        if ($this->notInEgroup("Atlas.Secretariat")) {
            $this->isNotCleared();
            return;
        }

        $this->isClearedAs(self::ROLE_NAME);
    }
}