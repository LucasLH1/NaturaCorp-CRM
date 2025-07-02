<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StatutCommande;

class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    public function definition(): array
    {
        return [
            'pharmacie_id' => Pharmacie::factory(),
            'user_id' => User::factory(),
            'statut' => StatutCommande::EN_COURS->value,
            'date_commande' => now(),
            'observations' => $this->faker->sentence(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Commande $commande) {
            $produit = Produit::factory()->create([
                'stock' => 50,
                'tarif_unitaire' => $this->faker->randomFloat(2, 10, 100),
            ]);

            $commande->produits()->attach($produit->id, [
                'quantite' => $this->faker->numberBetween(1, 5),
                'prix_unitaire' => $produit->tarif_unitaire,
            ]);

            // Mettre à jour le stock
            $produit->decrement('stock', $commande->produits()->first()->pivot->quantite);
        });
    }
}
