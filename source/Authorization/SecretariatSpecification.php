<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

final class SecretariatSpecification
{
    public function isSatisfiedBy(AuthenticatedUser $authenticatedUser): bool
    {
        return (bool) $authenticatedUser->inEgroup("Atlas.Secretariat");
    }
}