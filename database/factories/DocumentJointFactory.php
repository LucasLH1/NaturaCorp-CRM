<?php

namespace Database\Factories;

use App\Models\DocumentJoint;
use App\Models\Pharmacie;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentJointFactory extends Factory
{
    protected $model = DocumentJoint::class;

    public function definition(): array
    {
        return [
            'pharmacie_id' => Pharmacie::factory(),
            'nom_fichier' => $this->faker->word . '.pdf',
            'chemin' => 'documents/' . $this->faker->uuid . '.pdf',
            'type' => 'pdf',
        ];
    }
}

