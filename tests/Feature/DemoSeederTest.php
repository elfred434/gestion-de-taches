<?php

namespace Tests\Feature;

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder(): void
    {
        $this->seed(DemoSeeder::class);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('taches', 6);

        $utilisateurs = User::withCount('taches')->get();

        foreach ($utilisateurs as $utilisateur) {
            $this->assertSame(3, $utilisateur->taches_count);
        }

        
    }
}
