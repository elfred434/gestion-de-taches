<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Tache;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tache>
 */
class TacheFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'terminee' => false,
            'user_id' => User::factory(),

            /*
             fake: génère des données fictives
             sentences(4) génère une courte phrases 
             paragraph génère un paragraphe
             User::factory() prévoit la création d'un propriétaire si tu n'en fourni pas
             */
        ];
    }
}
