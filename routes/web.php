<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Models\User;

// Redirection vers login par défaut
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');
    Route::post('/depot', [TransactionController::class, 'depot'])->name('depot');
    Route::post('/retrait', [TransactionController::class, 'retrait'])->name('retrait');
    Route::post('/transfert', [TransactionController::class, 'transfert'])->name('transfert');
});

// Espace administrateur (optionnel)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

// ✅ Nouvelle route AJAX pour vérifier si un numéro existe déjà
Route::get('/check-user/{telephone}', function ($telephone) {
    $user = User::where('telephone', $telephone)->first();

    if ($user) {
        // Si le destinataire existe → renvoie son nom ou prénom
        return response()->json([
            'exists' => true,
            'name' => $user->prenom ?? $user->nom ?? 'Utilisateur'
        ]);
    } else {
        // Si aucun utilisateur trouvé → indique qu’il sera créé automatiquement
        return response()->json(['exists' => false]);
    }
});
