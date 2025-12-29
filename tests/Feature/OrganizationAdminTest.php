<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;

class OrganizationAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_admin_can_edit_organization()
    {
        // create organization and admin user
        $org = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $org->id]);

        // ensure role exists and assign
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization_admin', 'guard_name' => 'web']);
        }
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('organization_admin');
        }

        $this->actingAs($admin)
            ->put(route('admin.organization.update'), [
                'name' => 'New Name',
                'contact_email' => 'new@contact.test',
            ])
            ->assertRedirect(route('admin.organization.edit'));

        $this->assertDatabaseHas('organizations', ['id' => $org->id, 'name' => 'New Name']);
    }
}
