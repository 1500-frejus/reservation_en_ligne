<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;

class AdminAgentButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_admin_sees_manage_agents_button_on_dashboard_and_org_page()
    {
        $org = Organization::factory()->create();

        $admin = User::factory()->create(['organization_id' => $org->id]);

        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization_admin', 'guard_name' => 'web']);
        }

        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('organization_admin');
        }

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertStatus(200)
            ->assertSee('Gérer les agents')
            ->assertSee(route('admin.agents.index'));

        $this->actingAs($admin)->get(route('admin.organization.edit'))
            ->assertStatus(200)
            ->assertSee('Gérer les agents')
            ->assertSee(route('admin.agents.index'));
    }

    public function test_agent_does_not_see_manage_agents_button()
    {
        $org = Organization::factory()->create();

        $agent = User::factory()->create(['organization_id' => $org->id]);

        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        }

        if (method_exists($agent, 'assignRole')) {
            $agent->assignRole('agent');
        }

        $this->actingAs($agent)->get(route('admin.dashboard'))
            ->assertStatus(200)
            ->assertDontSee('Gérer les agents');
    }
}
