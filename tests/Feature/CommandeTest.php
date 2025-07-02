<?php

use function Pest\Laravel\{get, post, put, delete};
use App\Models\User;
use App\Models\Commande;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Enums\StatutCommande;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('admin');
    Role::findOrCreate('commercial');
    Role::findOrCreate('logistique');

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);
});

it('permet d\'accéder à la liste des commandes', function () {
    get('/commandes')->assertOk();
});

it('enregistre une nouvelle commande', function () {
    $produit = Produit::factory()->create(['stock' => 50]);
    $pharmacie = Pharmacie::factory()->create();

    $data = [
        'pharmacie_id' => $pharmacie->id,
        'statut' => StatutCommande::VALIDEE->value,
        'date_commande' => now()->format('Y-m-d'),
        'produits' => [
            [
                'id' => $produit->id,
                'quantite' => 5,
                'prix_unitaire' => $produit->tarif_unitaire,
            ],
        ],
    ];

    $response = post('/commandes', $data);
    $response->assertRedirect();

    $commande = Commande::where('pharmacie_id', $pharmacie->id)->first();

    expect($commande)->not()->toBeNull();
    expect($commande->produits()->where('produit_id', $produit->id)->exists())->toBeTrue();
    expect($commande->produits()->first()->pivot->quantite)->toBe(5);
});

it('met à jour une commande', function () {
    $produit = Produit::factory()->create(['stock' => 100]);
    $pharmacie = Pharmacie::factory()->create();

    $commande = Commande::factory()->create([
        'pharmacie_id' => $pharmacie->id,
        'date_commande' => now(),
    ]);

    $commande->produits()->attach($produit->id, [
        'quantite' => 5,
        'prix_unitaire' => $produit->tarif_unitaire,
    ]);

    $response = put('/commandes/' . $commande->id, [
        'pharmacie_id' => $commande->pharmacie_id,
        'date_commande' => now()->format('Y-m-d'),
        'statut' => $commande->statut->value ?? StatutCommande::VALIDEE->value,
        'observations' => $commande->observations,
        'produits' => [
            [
                'id' => $produit->id,
                'quantite' => 11,
                'prix_unitaire' => $produit->tarif_unitaire,
            ],
        ],
    ]);

    $response->assertRedirect();
    $commande->refresh();
    expect($commande->produits()->first()->pivot->quantite)->toBe(11);
});

it('supprime une commande', function () {
    $commande = Commande::factory()->create();

    $response = delete('/commandes/' . $commande->id);

    $response->assertRedirect();
    expect(Commande::find($commande->id))->toBeNull();
});
