<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;

class OrganizationOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_organization_and_admin_user()
    {
        $response = $this->post(route('organizations.register.store'), [
            'name' => 'Agence Test',
            // omit slug to test server-side generation
            'slug' => '',
            'contact_email' => 'contact@agence.test',
            'admin_name' => 'Admin Agence',
            'admin_email' => 'admin@agence.test',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.organization.edit'));

        // The admin should be authenticated and associated with the organization
        $admin = \App\Models\User::where('email', 'admin@agence.test')->first();
        $this->assertAuthenticatedAs($admin);

        $this->assertDatabaseHas('organizations', ['name' => 'Agence Test']);
        $this->assertDatabaseHas('organizations', ['slug' => 'agence-test']);

        $user = User::where('email', 'admin@agence.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Admin Agence', $user->name);
        $this->assertEquals(Organization::where('slug', 'agence-test')->first()->id, $user->organization_id);
    }
}
