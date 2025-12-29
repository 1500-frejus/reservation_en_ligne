<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationRegisterFormFrenchTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_register_form_is_french()
    {
        $response = $this->get(route('organizations.register'));

        $response->assertStatus(200);
        $response->assertSeeText("Créer une société");
        // The apostrophe may be HTML-encoded in the response; check components instead
        $response->assertSeeText('Nom de');
        $response->assertSeeText('organisation');
        $response->assertSeeText('Identifiant (slug)');
        $response->assertSeeText('Email de contact');
        $response->assertSeeText('Compte administrateur');
        $response->assertSeeText('Nom complet');
        $response->assertSeeText('Adresse email');
        $response->assertSeeText('Mot de passe');
        $response->assertSeeText('Confirmer le mot de passe');
        $response->assertSeeText('Créer la société');
    }
}
