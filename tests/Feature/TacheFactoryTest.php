<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tache;
use App\Models\User;


use Tests\TestCase;

class TacheFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_tâches(): void {
        $utilisateur = User::factory()->create();

        $taches = Tache::factory()
        ->count(3)
        ->for($utilisateur)
        ->create();

        $this->assertCount(3, $taches);
        $this->assertDatabaseCount('taches', 3);
        $this->assertDatabaseCount('users', 1);
        
        //vérifier que toutes les tâches appartiennent a l'users
        foreach ($taches as $tache) {
            $this->assertSame($utilisateur->id , $tache->user_id);
        }
        
    }
}
