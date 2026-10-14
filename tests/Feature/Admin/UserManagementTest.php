<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_a_new_user_account(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Created User',
            'email' => 'created-user@example.com',
            'password' => 'password123',
            'role' => 'Employee',
            'job_title' => 'Staff',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'created-user@example.com',
            'name' => 'Created User',
            'job_title' => 'Staff',
        ]);

        $this->assertTrue(User::where('email', 'created-user@example.com')->first()->hasRole('Employee'));
    }
}
