<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Testing;

use InvalidArgumentException;

trait InMemoryStorage
{
    /** @var array */
    private $storage;

    public function __construct()
    {
        $this->storage = [];
    }

    protected function set(string $key, $value): void
    {
        $this->storage[$key] = $value;
    }

    public function get(string $key)
    {
        if ($this->keyDoesNotExist($key)) {
            throw new InvalidArgumentException("Could not find key {$key}");
        }
        return $this->storage[$key];
    }

    protected function keyDoesNotExist(string $key): bool
    {
        return ! array_key_exists($key, $this->storage);
    }
}