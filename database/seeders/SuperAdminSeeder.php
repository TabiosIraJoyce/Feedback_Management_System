<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the role exists (RolesAndPermissionsSeeder should have run, but be safe)
        Role::firstOrCreate(['name' => 'Super Admin'], ['guard_name' => 'web']);

        $user = User::updateOrCreate(
            ['email' => 'iratbios@gmail.com'],
            [
                'name' => 'Ira Joyce',
                'password' => Hash::make('gl3dc0MC2002'),
                'is_active' => true,
                'job_title' => 'Super Administrator',
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('Super Admin')) {
            $user->assignRole('Super Admin');
        }
    }
}
