<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PharmacieController,
    CommandeController,
    DocumentJointController,
    NotificationInterneController,
    RapportController,
    JournalActiviteController
};
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
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
