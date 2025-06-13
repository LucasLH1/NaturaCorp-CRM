<?php

namespace Database\Factories;

use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalActiviteFactory extends Factory
{
    protected $model = JournalActivite::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['création', 'modification', 'suppression']),
            'description' => $this->faker->sentence(),
            'ip' => $this->faker->ipv4,
        ];
    }
}

