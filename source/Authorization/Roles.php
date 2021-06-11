<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

final class Roles
{
    public static function for(AuthenticatedUser $authenticatedUser): array
    {
        if ((new SecretariatSpecification())->isSatisfiedBy($authenticatedUser)) {
            return [Secretariat::ROLE_NAME];
        }

        return [Member::ROLE_NAME];
    }
}