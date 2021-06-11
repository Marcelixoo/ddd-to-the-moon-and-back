<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

final class Secretariat extends AuthenticatedUser
{
    public const ROLE_NAME = "Atlas Secretariat";

    public function authenticate($aux_data = null)
    {
        $specification = new SecretariatSpecification();

        if ($specification->isSatisfiedBy($this)) {
            $this->allowAccess();
        }
    }
}