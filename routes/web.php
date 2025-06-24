<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProduitController,
    ProfileController,
    DashboardController,
    PharmacieController,
    CommandeController,
    DocumentJointController,
    NotificationInterneController,
    RapportController,
    CarteController,
    JournalActiviteController,
    UserController};

Route::get('/', fn() => view('welcome'));

// Authentification requise pour toutes les routes protégées
Route::middleware(['auth'])->group(function () {

    /**
     * Tableau de bord
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data/commandes', [DashboardController::class, 'chartCommandes'])->name('dashboard.data.commandes');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('produits', ProduitController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    /**
     * Profil
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Utilisateurs (réservé aux admins)
     */
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/logs', [JournalActiviteController::class, 'index'])->name('admin.logs');

    });

    /**
     * Gestion des pharmacies et commandes (CRM)
     */
    Route::middleware(['role:admin|commercial'])->group(function () {
        Route::resource('pharmacies', PharmacieController::class);
    });
    Route::resource('commandes', CommandeController::class);
    Route::resource('documents', DocumentJointController::class)->names('documents');

    /**
     * Carte interactive
     */
    Route::get('/carte', [CarteController::class, 'index'])->name('carte.index');

    /**
     * Rapports (réservé aux admins)
     */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('rapports/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
        Route::delete('rapports/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');
    });

    /**
     * Notifications internes (accessible à tous les utilisateurs authentifiés)
     */
    Route::prefix('notifications')->middleware(['auth'])->group(function () {
        Route::get('/fetch', [NotificationInterneController::class, 'fetch'])->name('notifications.fetch');
        Route::post('/read-all', [NotificationInterneController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::post('/{notification}/read', [NotificationInterneController::class, 'markAsRead'])->name('notifications.read');
        Route::delete('/{notification}', [NotificationInterneController::class, 'destroy'])->name('notifications.destroy');
    });

    Route::get('/debug-delete/{pharmacie}', function (\App\Models\Pharmacie $pharmacie) {
        dd($pharmacie);
    });


});

require __DIR__.'/auth.php';
