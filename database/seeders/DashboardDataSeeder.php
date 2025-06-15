<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pharmacie;
use App\Models\Commande;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DashboardDataSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(10)->create();

        $commerciaux = User::inRandomOrder()->take(5)->pluck('id');

        // Créer 100 pharmacies
        Pharmacie::factory()->count(100)->create()->each(function ($pharmacie) use ($commerciaux) {
            $pharmacie->update([
                'commercial_id' => $commerciaux->random(),
                'created_at' => now()->subDays(rand(0, 180)),
            ]);

            // Créer entre 1 et 5 commandes
            Commande::factory()->count(rand(1, 5))->create([
                'pharmacie_id' => $pharmacie->id,
                'date_commande' => now()->subDays(rand(0, 180)),
            ]);
        });
    }
}
