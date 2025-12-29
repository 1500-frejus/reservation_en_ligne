<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationRegisterLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_create_organization_link_in_nav()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Créer une société');
        $response->assertSee(route('organizations.register'));
        $response->assertSee('Nouveau');
    }
}
