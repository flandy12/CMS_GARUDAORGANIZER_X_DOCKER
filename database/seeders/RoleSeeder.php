<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = Permission::query()->where('guard_name', 'web')->get();
        $viewPermissions = $permissions->filter(fn (Permission $permission) => str_starts_with($permission->name, 'view '));
        $editorPermissions = $permissions->reject(fn (Permission $permission) => str_starts_with($permission->name, 'delete '));

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'])->syncPermissions($permissions);
        Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web'])->syncPermissions($editorPermissions);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web'])->syncPermissions($viewPermissions);
        Role::firstOrCreate(['name' => 'api-user', 'guard_name' => 'web'])->syncPermissions([]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
