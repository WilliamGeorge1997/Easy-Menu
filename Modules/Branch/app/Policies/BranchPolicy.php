<?php

namespace Modules\Branch\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Models\Admin;
use Modules\Branch\Models\Branch;

class BranchPolicy
{
    use HandlesAuthorization;

    /**
     * Super Admin bypasses all policy checks.
     * Use Authenticatable so Laravel always calls this regardless of guard.
     */
    public function before(Authenticatable $user, string $ability): bool|null
    {
        if ($user instanceof Admin && $user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->hasRole('branch_manager');
    }

    public function view(Authenticatable $user, Branch $branch): bool
    {
        return $user instanceof Admin && (int) $user->branch_id === (int) $branch->id;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->hasRole('branch_manager');
    }

    public function update(Authenticatable $user, Branch $branch): bool
    {
        return $user instanceof Admin
            && $user->hasRole('branch_manager')
            && (int) $user->branch_id === (int) $branch->id;
    }

    public function delete(Authenticatable $user, Branch $branch): bool
    {
        return $user instanceof Admin && (int) $user->branch_id === (int) $branch->id;
    }

    public function activate(Authenticatable $user, Branch $branch): bool
    {
        return $user instanceof Admin && (int) $user->branch_id === (int) $branch->id;
    }
}
