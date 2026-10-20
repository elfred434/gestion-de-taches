<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class AccesTacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_est_redirige_vers_la_connexion(): void {
        $reponse = $this->get(route('taches.index'));

        $reponse->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_un_visiteur_ne_peut_pas_creer_de_tache(): void {
         $reponse = $this->post(route('taches.store'), [
            'titre' => 'Apprendre les tests Laravel',
            'description' => 'Vérifier la création automatique.',
        ]);

        $reponse->assertRedirect(route('login'));

        $this->assertDatabaseCount('taches', 0);
    }
}
