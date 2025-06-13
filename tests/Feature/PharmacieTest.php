<?php

use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test de création
it('peut créer une pharmacie', function () {
    $pharmacie = Pharmacie::create([
        'nom' => 'Pharma Bleue',
        'adresse' => '12 rue Santé',
        'code_postal' => '75000',
        'ville' => 'Paris',
        'email' => 'contact@pharmableue.fr',
        'telephone' => '0102030405',
        'statut' => 'client_actif',
    ]);

    expect($pharmacie)->toBeInstanceOf(Pharmacie::class)
        ->and($pharmacie->nom)->toBe('Pharma Bleue');
});

// Test en base
it('est bien enregistré en base', function () {
    Pharmacie::factory()->create(['nom' => 'Test Pharmacie']);
    expect(Pharmacie::where('nom', 'Test Pharmacie')->exists())->toBeTrue();
});

