<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController,
    PharmacieController,
    CommandeController,
    DocumentJointController,
    NotificationInterneController,
    RapportController,
    JournalActiviteController,
    UserController};

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data/commandes', [DashboardController::class, 'chartCommandes'])->name('dashboard.data.commandes');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::resource('pharmacies', PharmacieController::class);
    Route::resource('commandes', CommandeController::class);
    Route::resource('documents', DocumentJointController::class)->names('documents');
    Route::get('notifications', [NotificationInterneController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notificationInterne}/lue', [NotificationInterneController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('notifications/{notificationInterne}', [NotificationInterneController::class, 'destroy'])->name('notifications.destroy');

    Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('rapports/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
    Route::delete('rapports/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');

    Route::get('journal', [JournalActiviteController::class, 'index'])->name('journal.index');
    Route::delete('journal/{journalActivite}', [JournalActiviteController::class, 'destroy'])->name('journal.destroy');
});

require __DIR__.'/auth.php';
