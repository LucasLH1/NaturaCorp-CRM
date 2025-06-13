<?php

use App\Models\DocumentJoint;
use App\Models\Pharmacie;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('enregistre un document pour une pharmacie', function () {
    $pharmacie = Pharmacie::factory()->create();

    $document = DocumentJoint::create([
        'pharmacie_id' => $pharmacie->id,
        'nom_fichier' => 'contrat.pdf',
        'chemin' => 'documents/contrat.pdf',
        'type' => 'pdf',
    ]);

    expect($document->pharmacie_id)->toBe($pharmacie->id);
});

