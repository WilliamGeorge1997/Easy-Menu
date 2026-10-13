<?php

namespace Modules\Product\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Models\Admin;
use Modules\Product\Models\Attribute;
use Modules\Product\Models\Product;

class AttributePolicy
{
    use HandlesAuthorization;

    public function before(Authenticatable $user, string $ability): ?bool
    {
        if ($user instanceof Admin && $user->hasRole(config('product.roles.super_admin'))) {
            return true;
        }

        return null;
    }

    public function viewAny(Authenticatable $user, Product $product): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $product->branch_id;
    }

    public function view(Authenticatable $user, Attribute $attribute): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $attribute->branch_id;
    }

    public function create(Authenticatable $user, Product $product): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $product->branch_id;
    }

    public function update(Authenticatable $user, Attribute $attribute): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $attribute->branch_id;
    }

    public function delete(Authenticatable $user, Attribute $attribute): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $attribute->branch_id;
    }

    public function activate(Authenticatable $user, Attribute $attribute): bool
    {
        return $user instanceof Admin
            && $user->hasRole(config('product.roles.branch_manager'))
            && (int) $user->branch_id === (int) $attribute->branch_id;
    }
}
