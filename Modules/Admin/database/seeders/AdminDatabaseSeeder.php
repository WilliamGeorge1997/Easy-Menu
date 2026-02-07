<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = $this->createSuperAdminRole();
        $superAdmin = $this->createSuperAdmin();
        $superAdmin->assignRole($superAdminRole);
        $this->createBranchManagerRole();
    }

    private function createSuperAdmin(): Admin{
        return Admin::create([
            'name' => 'Super Admin',
            'phone' => '0123456789',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123123'),
            'is_active' => true,
        ]);
    }

    private function createSuperAdminRole(): Role{
        return Role::create([
            'name' => 'super_admin',
            'guard_name' => 'admin',
            'display' => 'Super Admin',
        ]);
    }

    private function createBranchManagerRole(): Role{
        return Role::create([
            'name' => 'branch_manager',
            'guard_name' => 'admin',
            'display' => 'Branch Manager',
        ]);
    }
}
