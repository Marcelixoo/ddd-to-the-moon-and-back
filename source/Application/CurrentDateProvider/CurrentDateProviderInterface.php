<?php

declare(strict_types=1);

namespace Atlas\DDD\Application\CurrentDateProvider;

use DateTimeImmutable;

interface CurrentDateProviderInterface
{
    public function currentDate(): DateTimeImmutable;
}