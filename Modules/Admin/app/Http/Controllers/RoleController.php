<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $this->ensureSuperAdmin();
        $roles = Role::query()
            ->latest('id')
            ->paginate(10);

        return view('admin::roles.index', compact('roles'));
    }

    private function ensureSuperAdmin(): void
    {
        /** @var \Modules\Admin\Models\Admin|null $admin */
        $admin = auth('admin')->user();
        if (! $admin || ! $admin->hasRole(config('admin.roles.super_admin'))) {
            abort(403);
        }
    }
}
