<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Database;

use Atlas\DDD\Secretariat\Database\Queries\QueryResult;
use Atlas\DDD\Secretariat\Database\Queries\SelectEmailQuery;
use Atlas\DDD\Tests\Testing\InMemoryStorage;
use Atlas\DDD\Secretariat\Model\Member\CernEmail;

class SecretariatDatabase
{
    use InMemoryStorage;

    public function __construct(array $initialStorage = [])
    {
        foreach ($initialStorage as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function selectEmailThatMatches(CernEmail $email): QueryResult
    {
        $query = new SelectEmailQuery($this->connection);

        return $query->execute($email);
    }
}