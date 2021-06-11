<?php

declare(strict_types=1);

namespace Atlas\DDD\Application;

use Fence\BaseUser;

final class AuthenticatedUser extends BaseUser
{
    public function authenticate($aux_data = null)
    {
    }

    public function header_roles(): array
    {
        return [
            "Most Expensive Player",
        ];
    }

    public function render_option_tab(): void
    {
        ?><ul class="nav navbar-nav navbar-right"><?php
            $this->render_login_tab();
        ?></ul><?php
    }
}