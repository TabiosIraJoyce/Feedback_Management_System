<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage branches',
            'manage complaints',
            'manage feedback',
            'manage reports',
            'manage settings',
            'manage forms',
            'view dashboard',
            'assign complaints',
            'close complaints',
            'reopen complaints',
            'hard delete records',
            'submit feedback',
            'submit complaints',
            'track complaints',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roles = [
            'Super Admin' => $permissions,
            'Admin' => [
                'manage users',
                'manage branches',
                'manage complaints',
                'manage feedback',
                'manage reports',
                'manage settings',
                'manage forms',
                'view dashboard',
                'assign complaints',
                'close complaints',
                'reopen complaints',
                'submit feedback',
                'submit complaints',
                'track complaints',
            ],
            'Department Head' => [
                'manage complaints',
                'manage feedback',
                'view dashboard',
                'assign complaints',
                'close complaints',
                'reopen complaints',
                'submit feedback',
                'track complaints',
            ],
            'Supervisor' => [
                'manage complaints',
                'manage feedback',
                'view dashboard',
                'assign complaints',
                'close complaints',
                'reopen complaints',
                'submit feedback',
                'track complaints',
            ],
            'Employee' => [
                'view dashboard',
                'submit feedback',
                'submit complaints',
                'track complaints',
            ],
            'Customer' => [
                'submit feedback',
                'submit complaints',
                'track complaints',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($rolePermissions);
        }
    }
}
