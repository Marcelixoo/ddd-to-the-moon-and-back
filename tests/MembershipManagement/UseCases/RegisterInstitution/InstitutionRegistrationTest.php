<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\UseCases\RegisterInstitution;

use Atlas\DDD\MembershipManagement\Model\Institution;
use Atlas\DDD\MembershipManagement\Model\InstitutionNotFoundException;
use Atlas\DDD\MembershipManagement\Model\InstitutionsRepositoryInterface;
use Atlas\DDD\MembershipManagement\UseCases\RegisterInstitution\RegisterInstitutionCommand;
use Atlas\DDD\MembershipManagement\UseCases\RegisterInstitution\RegisterInstitutionRequest;
use PHPUnit\Framework\TestCase;
use Predis\Client;

final class InstitutionRegistrationTest extends TestCase
{
    /** @test */
    public function it_saves_a_new_institution_and_returns_its_id(): void
    {
        $command = new RegisterInstitutionCommand($institutionsRepository = new InstitutionsRedisRepository());

        $newId = $command->execute(new RegisterInstitutionRequest(
            "Universidade Federal do Rio de Janeiro",
            "Brazil"
        ));

        $fromRepository = $institutionsRepository->findById($newId);
        $this->assertEquals("Universidade Federal do Rio de Janeiro", $fromRepository->name());
        $this->assertEquals("Brazil", $fromRepository->country());
    }
}

class InstitutionsInMemoryRepository implements InstitutionsRepositoryInterface
{
    /** @var array */
    private $storage;

    public function __construct()
    {
        $this->storage = [];
    }

    public function save(Institution $institution): void
    {
        $this->storage[$institution->id()] = $institution;
    }

    public function findById(string $institutionId): Institution
    {
        if (! array_key_exists($institutionId, $this->storage)) {
            throw new InstitutionNotFoundException(
                "Could not find institution with id {$institutionId}"
            );
        }

        return $this->storage[$institutionId];
    }
}

class InstitutionsRedisRepository implements InstitutionsRepositoryInterface
{
    /** @var Client */
    private $storage;

    public function __construct()
    {
        $this->storage = new Client('tcp://redis:6379');
    }

    public function save(Institution $institution): void
    {
        $this->storage->set($institution->id(), json_encode($institution));
    }

    public function findById(string $institutionId): Institution
    {
        $response = $this->storage->get($institutionId);

        if (is_null($response)) {
            throw new InstitutionNotFoundException(
                "Could not find institution with id {$institutionId}"
            );
        }

        return Institution::fromState($response);
    }
}