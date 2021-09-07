<?php

declare(strict_types=1);

namespace Atlas\DDD\Secretariat\Database\Queries;

use RuntimeException;
use Atlas\DDD\Tests\Testing\InMemoryStorage;
use Atlas\DDD\Secretariat\Model\Member\CernEmail;

class SelectEmailQuery
{
    use InMemoryStorage;

    // public function __construct(Connection $connection)
    // {
    //     $this->connection = $connection;
    // }

    public function execute(CernEmail $email): QueryResult
    {
        foreach ($this->storage as $member) {
            if ($member->emailAddress()->isEqualTo($email)) {
                return new QueryResult([["EMAIL" => $email]]);
            }
        }

        return QueryResult::empty();
    }
}

class QueryResult
{
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function isEmpty(): bool
    {
        return empty($this->result);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function firstRow(): array
    {
        if ($this->isEmpty()) {
            throw new RuntimeException("Not results found.");
        }

        $result = $this->result;

        return array_shift($result);
    }
}
