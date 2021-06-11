<?php

declare(strict_types=1);

namespace Atlas\DDD\Authorization;

use Fence\BaseUser;

abstract class AuthenticatedUser extends BaseUser
{
    /** @var bool */
    private $isNotCleared = true;

    public function header_roles(): array
    {
        return Roles::for($this);
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

    public function allowAccess(): void
    {
        $this->isNotCleared = false;
    }

    /** @inheritDoc */
    public function is_cleared(): bool
    {
        if ($this->isNotCleared) {
            return false;
        }

        return parent::is_cleared();
    }
}