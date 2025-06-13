<?php

use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('enregistre une activité utilisateur', function () {
    $user = User::factory()->create();

    $log = JournalActivite::create([
        'user_id' => $user->id,
        'action' => 'suppression',
        'description' => 'Suppression d\'une commande',
        'ip' => '127.0.0.1',
    ]);

    expect($log->user_id)->toBe($user->id);
});

