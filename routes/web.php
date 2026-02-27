<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\CarburantController;
use App\Http\Controllers\CuveController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EntreeController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionVenteController;
use App\Http\Controllers\JaugeageController;
use App\Http\Controllers\PompeController;
use App\Http\Controllers\PistoletController;

// Routes publiques
Route::get('/', [PageController::class, 'home'])->name('login');
Route::get('/register_users', [PageController::class, 'add_users'])->name('add_users');
Route::post('connexion', [AuthController::class, 'login'])->name('connexion');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Routes protégées (authentification requise)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile/{id}', [PageController::class, 'profile'])->name('profile');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Gestion des Stations (Admin uniquement)
    Route::resource('stations', StationController::class);

    // Gestion des Carburants
    Route::resource('carburants', CarburantController::class);

    // Gestion des Cuves
    Route::resource('cuves', CuveController::class);

    // Gestion des Pompes
    Route::resource('pompes', PompeController::class);

    // Gestion des Pistolets
    Route::resource('pistolets', PistoletController::class);

    // Gestion des Commandes
    Route::resource('commandes', CommandeController::class);
    Route::post('commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.updateStatut');

    // Gestion des Entrées (Réceptions)
    Route::resource('entrees', EntreeController::class);
    Route::get('entrees/{entree}/print', [EntreeController::class, 'print'])->name('entrees.print');

    // Gestion des Ventes
    Route::resource('ventes', VenteController::class);
    Route::get('ventes/{vente}/print', [VenteController::class, 'print'])->name('ventes.print');

    // Sessions de Vente
    Route::resource('session_ventes', SessionVenteController::class)->except(['edit', 'update']);
    Route::get('session_ventes/{session_vente}/cloture',  [SessionVenteController::class, 'cloture'])->name('session_ventes.cloture');
    Route::post('session_ventes/{session_vente}/cloturer', [SessionVenteController::class, 'cloturer'])->name('session_ventes.cloturer');

    // Jaugeages
    Route::resource('jaugeages', JaugeageController::class)->only(['index', 'create', 'store', 'show']);

    // Gestion des Utilisateurs (Admin et Gestionnaire)
    Route::resource('users', UserController::class);
});
