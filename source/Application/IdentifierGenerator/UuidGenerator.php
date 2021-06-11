<?php

declare(strict_types=1);

namespace Atlas\DDD\Application\IdentifierGenerator;

use Ramsey\Uuid\Uuid;

final class UuidGenerator implements IdentifierGeneratorInterface
{
    public function generateNewIdentifier(): string
    {
        return Uuid::uuid4()->toString();
    }
}