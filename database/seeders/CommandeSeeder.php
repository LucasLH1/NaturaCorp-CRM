<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\Pharmacie;
use App\Models\User;
use App\Enums\StatutCommande;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CommandeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::inRandomOrder()->get();
        $pharmacies = Pharmacie::inRandomOrder()->get();

        if ($users->isEmpty() || $pharmacies->isEmpty()) {
            $this->command->warn('Aucun utilisateur ou pharmacie trouvé, seed ignoré.');
            return;
        }

        foreach (range(1, 10) as $i) {
            Commande::create([
                'pharmacie_id'   => $pharmacies->random()->id,
                'user_id'        => $users->random()->id,
                'date_commande'  => Carbon::now()->subDays(rand(0, 60)),
                'statut'         => collect(StatutCommande::cases())->random()->value,
                'quantite'       => rand(5, 100),
                'tarif_unitaire' => rand(1000, 5000) / 100,
                'observations'   => fake()->optional()->sentence(),
            ]);
        }
    }
}
