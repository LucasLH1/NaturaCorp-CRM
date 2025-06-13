<?php

use App\Models\NotificationInterne;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('crée une notification pour un utilisateur', function () {
    $user = User::factory()->create();

    $notif = NotificationInterne::create([
        'user_id' => $user->id,
        'titre' => 'Commande urgente',
        'contenu' => 'Commande non livrée depuis 7 jours',
    ]);

    expect($notif->fresh()->est_lu)->toBeFalse();
});

it('peut marquer une notification comme lue', function () {
    $notif = NotificationInterne::factory()->create(['est_lu' => false]);
    $notif->update(['est_lu' => true]);
    expect($notif->fresh()->est_lu)->toBeTrue();
});
