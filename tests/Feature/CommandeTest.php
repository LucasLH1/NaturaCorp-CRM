<?php

use App\Models\Commande;
use App\Models\Pharmacie;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('peut créer une commande liée à une pharmacie', function () {
    $pharmacie = Pharmacie::factory()->create();

    $commande = Commande::create([
        'pharmacie_id' => $pharmacie->id,
        'date_commande' => now(),
        'statut' => 'validée',
        'quantite' => 10,
        'tarif_unitaire' => 19.90,
    ]);

    expect($commande)->toBeInstanceOf(Commande::class)
        ->and($commande->pharmacie->id)->toBe($pharmacie->id);
});
