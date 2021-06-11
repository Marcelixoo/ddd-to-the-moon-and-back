<?php

declare(strict_types=1);

namespace Atlas\DDD\Application\IdentifierGenerator;

interface IdentifierGeneratorInterface
{
    public function generateNewIdentifier(): string;
}