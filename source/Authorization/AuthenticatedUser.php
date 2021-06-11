<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

use Fence\BaseUser;

abstract class AuthenticatedUser extends BaseUser
{
    /** @var string[] */
    private $roles = [];

    /** @var bool */
    private $isNotCleared;

    public function header_roles(): array
    {
        if (empty($this->roles)) {
            return ["Member"];
        }

        return $this->roles;
    }

    public function render_option_tab(): void
    {
        ?><ul class="nav navbar-nav navbar-right"><?php
            $this->render_login_tab();
        ?></ul><?php
    }

    public function notInEgroup(string $egroupName): bool
    {
        return ! $this->inEgroup($egroupName);
    }

    /** @inheritDoc */
    public function is_cleared(): bool
    {
        if ($this->isNotCleared) {
            return false;
        }

        return parent::is_cleared();
    }

    protected function isNotCleared(): void
    {
        $this->isNotCleared = true;
    }

    protected function isClearedAs(string ...$roles): void
    {
        $this->roles = $roles;
    }
}