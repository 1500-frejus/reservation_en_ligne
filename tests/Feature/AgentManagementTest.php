<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;

class AgentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_admin_can_create_agent()
    {
        $org = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $org->id]);

        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization_admin', 'guard_name' => 'web']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        }

        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('organization_admin');
        }

        $this->actingAs($admin)
            ->post(route('admin.agents.store'), [
                'name' => 'Agent One',
                'email' => 'agent1@org.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('admin.agents.index'));

        $this->assertDatabaseHas('users', ['email' => 'agent1@org.test', 'organization_id' => $org->id]);

        $agent = User::where('email', 'agent1@org.test')->first();
        $this->assertNotNull($agent);
        if (method_exists($agent, 'getRoleNames')) {
            $this->assertContains('agent', $agent->getRoleNames()->toArray());
        }

        // also test invitation create flow
        $this->actingAs($admin)
            ->post(route('admin.agents.invite.store'), [
                'email' => 'invitee@org.test',
                'name' => 'Invited Agent',
            ])
            ->assertRedirect(route('admin.agents.index'));

        $this->assertDatabaseHas('agent_invitations', ['email' => 'invitee@org.test', 'organization_id' => $org->id]);
    }
}
