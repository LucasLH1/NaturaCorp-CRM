<?php

namespace Database\Factories;

use App\Models\Pharmacie;
use Illuminate\Database\Eloquent\Factories\Factory;

class PharmacieFactory extends Factory
{
    protected $model = Pharmacie::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company,
            'adresse' => $this->faker->address,
            'code_postal' => $this->faker->postcode,
            'ville' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->phoneNumber,
            'statut' => $this->faker->randomElement(['prospect', 'client_actif', 'client_inactif']),
        ];
    }
}

