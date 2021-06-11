<?php

declare(strict_types=1);

namespace Atlas\DDD\Backoffice\Model;

use Webmozart\Assert\Assert;

class CernEmail
{
    public function __construct(string $email)
    {
        Assert::email($email);
        Assert::endsWith($email, "@cern.ch", "Only CERN e-mail addresses are allowed.");

        $this->email = $email;
    }

    public function isEqualTo(CernEmail $other): bool
    {
        return strtolower($this->email) === strtolower($other->email);
    }
}