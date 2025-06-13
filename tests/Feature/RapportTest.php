<?php

use App\Models\Rapport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('crée un rapport avec des filtres', function () {
    $user = User::factory()->create();

    $rapport = Rapport::create([
        'titre' => 'Rapport Q1',
        'type' => 'csv',
        'filtres' => ['statut' => 'client_actif'],
        'chemin_fichier' => 'rapports/rapport-q1.csv',
        'user_id' => $user->id,
    ]);

    expect($rapport->filtres)->toMatchArray(['statut' => 'client_actif']);
});

