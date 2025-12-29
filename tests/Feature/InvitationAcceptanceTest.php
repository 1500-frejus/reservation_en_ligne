<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Organization;
use App\Models\AgentInvitation;

class InvitationAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_accept_invitation_and_create_account()
    {
        $org = Organization::factory()->create();

        $inv = AgentInvitation::create([
            'organization_id' => $org->id,
            'email' => 'newagent@org.test',
            'name' => 'New Agent',
            'token' => AgentInvitation::generateToken(),
        ]);

        $response = $this->get(route('invitations.accept', ['token' => $inv->token]));
        $response->assertStatus(200);

        $post = $this->post(route('invitations.accept.post', ['token' => $inv->token]), [
            'name' => 'New Agent',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $post->assertRedirect('/login');

        $this->assertDatabaseHas('users', ['email' => 'newagent@org.test', 'organization_id' => $org->id]);
        // accepted_at will have a timestamp
        $this->assertDatabaseMissing('agent_invitations', ['email' => 'newagent@org.test', 'accepted_at' => null]);
    }
}
