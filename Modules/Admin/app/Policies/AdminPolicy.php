<?php

namespace Modules\Admin\Policies;

use Modules\Admin\Models\Admin;

class AdminPolicy
{
    /**
     * Only Super Admin can perform any action.
     */
    public function before(Admin $user, string $ability): bool|null
    {
        if ($user->hasRole(config('admin.roles.super_admin'))) {
            return true;
        }
        return false;
    }

    public function activate(Admin $user, Admin $model): bool
    {
        return false;
    }
}
