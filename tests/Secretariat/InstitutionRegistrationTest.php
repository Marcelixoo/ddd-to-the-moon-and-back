<?php

declare(strict_types=1);

namespace Atlas\DDD\Tests\Secretariat;

use Atlas\DDD\Secretariat\Model\Institution\Institution;
use Atlas\DDD\Secretariat\Model\Institution\InstitutionNotFoundException;
use Atlas\DDD\Secretariat\Model\Institution\InstitutionsRepositoryInterface;
use Atlas\DDD\Secretariat\Features\RegisterInstitution\InstitutionRegistrationService;
use Atlas\DDD\Secretariat\Features\RegisterInstitution\NewInstitutionRequest;
use Atlas\DDD\Secretariat\Notifiers\InstitutionRegistrationNotifier;
use Atlas\DDD\Tests\Testing\EmailTestCase;
use Predis\Client;

final class InstitutionRegistrationTest extends EmailTestCase
{

    protected function setUp(): void
    {
        $this->container = require 'tests/bootstrap.php';
        parent::setUp();
    }
    /** @test */
    public function it_saves_a_new_institution_and_returns_its_id(): void
    {
        $command = new InstitutionRegistrationService($institutionsRepository = new InstitutionsRedisRepository(), $this->container->get(InstitutionRegistrationNotifier::class));

        $newId = $command->execute(new NewInstitutionRequest(
            "Universidade Federal do Rio de Janeiro",
            "Brazil"
        ));

        $fromRepository = $institutionsRepository->findById($newId);
        $this->assertEquals("Universidade Federal do Rio de Janeiro", $fromRepository->name());
        $this->assertEquals("Brazil", $fromRepository->country());
    }

    /** @test */
    public function it_sends_an_email_notifying_the_registration_to_atlas_secretariat(): void
    {
        $command = new InstitutionRegistrationService(new InstitutionsRedisRepository(), $this->container->get(InstitutionRegistrationNotifier::class));

        $institutionId = $command->execute(new NewInstitutionRequest(
            "Universidade Federal do Rio de Janeiro",
            "Brazil"
        ));

        $lastMessage = $this->getLastMessage();

        $this->assertEmailSubjectContains('Institution registration', $lastMessage);
        $this->assertEmailHtmlContains('Universidade Federal do Rio de Janeiro', $lastMessage);
        $this->assertEmailHtmlContains("http://localhost:8000/institutions/{$institutionId}", $lastMessage);
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
        $this->storage = new Client(getenv('REDIS_HOST'));
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