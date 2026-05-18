<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;

// 1. Page de garde
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PARTIE QUIZ ---

    // IMPORTANT : On place "create" AVANT les routes avec {poll}
    Route::get('/polls/create', [PollController::class, 'create'])->name('polls.create');
    Route::post('/polls', [PollController::class, 'store'])->name('polls.store');

    // Liste et détails
    Route::get('/polls', [PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}', [PollController::class, 'show'])->name('polls.show');
    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');
    
// Ajoute cette ligne parmi tes autres routes "polls"
Route::get('/polls/{poll}/results', [PollController::class, 'results'])->name('polls.results');
});

require __DIR__.'/auth.php';