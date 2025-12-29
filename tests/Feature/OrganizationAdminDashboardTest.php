<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class OrganizationAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_admin_redirected_to_dashboard_and_can_manage_clients()
    {
        // Register a new organization via the onboarding flow
        $response = $this->post(route('organizations.register.store'), [
            'name' => 'Agence Test',
            'slug' => 'agence-test',
            'contact_email' => 'contact@agence.test',
            'admin_name' => 'Admin Societe',
            'admin_email' => 'admin@agence.test',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();

        $admin = auth()->user();
        $this->assertEquals('admin@agence.test', $admin->email);
        $this->assertNotNull($admin->organization_id);

        // Ensure dashboard shows the users management link for org admin (label changed)
        $dashboard = $this->get(route('admin.dashboard'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Gérer mes clients');

        // Create users in same org and another org
        $sameOrgUser = User::factory()->create(['organization_id' => $admin->organization_id, 'name' => 'Client A', 'email' => 'clienta@test']);
        $otherOrg = Organization::factory()->create();
        $otherUser = User::factory()->create(['organization_id' => $otherOrg->id, 'name' => 'Client B', 'email' => 'clientb@test']);

        // Visit users list - should see only the client from the same organization
        $usersIndex = $this->get(route('admin.users.index'));
        $usersIndex->assertStatus(200);
        $usersIndex->assertSee('Client A');
        $usersIndex->assertDontSee('Client B');
    }

    public function test_dashboard_handles_missing_payments_org_column_gracefully()
    {
        $this->post(route('organizations.register.store'), [
            'name' => 'Agence Test 2',
            'slug' => 'agence-test-2',
            'contact_email' => 'contact2@agence.test',
            'admin_name' => 'Admin Societe 2',
            'admin_email' => 'admin2@agence.test',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();

        // Simulate schema lacking the organization_id column on payments
        Schema::shouldReceive('hasColumn')->with('payments', 'organization_id')->andReturn(false);

        $resp = $this->get(route('admin.dashboard'));
        $resp->assertStatus(200);
        $resp->assertSee('Tableau de Bord Administrateur');
    }
}
