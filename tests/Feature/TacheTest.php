<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class TacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_liste_vide_est_accessible():void{
        $reponse = $this->get(route('taches.index'));

        $reponse->assertOk();
        $reponse->assertSee(
            'Aucune tâche ne correspond à ces critères'
        );
    }
    public function test_la_création_marche(): void{
        $reponse = $this->get(route('taches.create'));

        $reponse->assertok();
        $reponse->assertViewIs('taches.creer');
        
    }
}
