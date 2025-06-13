<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\Pharmacie;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    public function definition(): array
    {
        return [
            'pharmacie_id' => Pharmacie::factory(),
            'date_commande' => $this->faker->date(),
            'statut' => $this->faker->randomElement(['validée', 'en_cours', 'livrée']),
            'quantite' => $this->faker->numberBetween(1, 50),
            'tarif_unitaire' => $this->faker->randomFloat(2, 5, 100),
            'observations' => $this->faker->optional()->sentence(),
        ];
    }
}

