<?php

namespace Database\Seeders;

use App\Models\Tache;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $utilisateurs = User::factory()
        ->count(2)
        ->create();

        foreach ($utilisateurs as $utilisateur) {
            Tache::factory()
            ->count(3)
            ->for($utilisateur)
            ->create();
        }
    }
}
