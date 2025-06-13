<?php

namespace Database\Factories;

use App\Models\Rapport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RapportFactory extends Factory
{
    protected $model = Rapport::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titre' => 'Rapport ' . $this->faker->word,
            'type' => $this->faker->randomElement(['pdf', 'csv']),
            'filtres' => ['zone' => 'Nord', 'statut' => 'client_actif'],
            'chemin_fichier' => 'rapports/' . $this->faker->uuid . '.csv',
        ];
    }
}

