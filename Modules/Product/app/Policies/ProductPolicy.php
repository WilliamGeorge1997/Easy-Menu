<?php

namespace Modules\Product\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Models\Admin;
use Modules\Product\Models\Product;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Super Admin bypasses all policy checks.
     */
    public function before(Authenticatable $user, string $ability): bool|null
    {
        if ($user instanceof Admin && $user->hasRole(config('product.roles.super_admin'))) {
            return true;
        }
        return null;
    }

    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->hasRole(config('product.roles.branch_manager'));
    }

    public function view(Authenticatable $user, Product $product): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $product->branch_id;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->hasRole(config('product.roles.branch_manager'));
    }

    public function update(Authenticatable $user, Product $product): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $product->branch_id;
    }

    public function delete(Authenticatable $user, Product $product): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $product->branch_id;
    }

    public function activate(Authenticatable $user, Product $product): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $product->branch_id;
    }
}
