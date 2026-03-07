<?php

namespace Modules\Category\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Models\Admin;
use Modules\Category\Models\Category;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Super Admin bypasses all policy checks.
     * Uses Authenticatable so Laravel always calls this regardless of guard.
     */
    public function before(Authenticatable $user, string $ability): bool|null
    {
        if ($user instanceof Admin && $user->hasRole(config('category.roles.super_admin'))) {
            return true;
        }
        return null;
    }

    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->hasRole(config('category.roles.branch_manager'));
    }

    public function view(Authenticatable $user, Category $category): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('category.roles.branch_manager'))
            && (int) $user->branch_id === (int) $category->branch_id;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->hasRole(config('category.roles.branch_manager'));
    }

    public function update(Authenticatable $user, Category $category): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('category.roles.branch_manager'))
            && (int) $user->branch_id === (int) $category->branch_id;
    }

    public function delete(Authenticatable $user, Category $category): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('category.roles.branch_manager'))
            && (int) $user->branch_id === (int) $category->branch_id;
    }

    public function activate(Authenticatable $user, Category $category): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('category.roles.branch_manager'))
            && (int) $user->branch_id === (int) $category->branch_id;
    }
}
