<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization\Roles;

use Atlas\DDD\Authorization\AuthenticatedUser;
use Atlas\DDD\Authorization\Roles\Specifications\SecretariatSpecification;

final class Roles
{
    /** @var  RoleSpecification[] */
    private $configuration;

    public function __construct()
    {
        $this->configuration = [
            Secretariat::ROLE_NAME => new SecretariatSpecification(),
            TeamLeader::ROLE_NAME => new TeamLeaderSpecification(),
            AuthorshipCommitteeMember::ROLE_NAME => new AuthorshipCommitteeMemberSpecification(),
            AppointmentsManager::ROLE_NAME => new AppointmentsManagerSpecification(),
        ];
    }

    public static function for(AuthenticatedUser $authenticatedUser): self
    {
        $roles = new self();

        foreach ($roles->configuration as $title => $specification) {
            if ($specification->isSatisfiedBy($authenticatedUser)) {
                $roles->add($title);
            }
        }

        if ($roles->empty()) {
            $roles->add(UnprivilegedMember::ROLE_NAME);
        }

        return $roles;
    }

    public function add(string $role): void
    {
        $this->roles[] = $role;
    }

    public function contains(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function toArray(): array
    {
        return $this->roles;
    }

    private function empty(): bool
    {
        return empty($this->roles);
    }
}

interface RoleSpecification
{
    public function isSatisfiedBy(AuthenticatedUser $authenticatedUser): bool;
}
