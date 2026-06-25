<?php

use App\Http\Controllers\Admin\BoutiqueController;
use App\Http\Controllers\BoutiqueSettingsController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\RapportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');

    Route::resource('categories', CategorieController::class);
    Route::resource('produits', ProduitController::class);
    Route::resource('clients', ClientController::class);

    Route::middleware('gerant')->group(function () {
        Route::patch('/equipe/{equipe}/toggle-status', [EquipeController::class, 'toggleStatus'])->name('equipe.toggle-status');
        Route::resource('equipe', EquipeController::class);

        Route::get('/administration', [BoutiqueSettingsController::class, 'edit'])->name('administration.edit');
        Route::patch('/administration', [BoutiqueSettingsController::class, 'update'])->name('administration.update');
    });

    Route::post('/ventes/{vente}/paiement', [VenteController::class, 'recordPayment'])->name('ventes.paiement.store');
    Route::post('/ventes/{vente}/cancel', [VenteController::class, 'cancel'])->name('ventes.cancel');
    Route::get('/ventes/{vente}/pdf', [VenteController::class, 'exportPdf'])->name('ventes.pdf');
    Route::get('/paiements/{paiement}/recu', [VenteController::class, 'exportRecu'])->name('paiements.recu');
    Route::resource('ventes', VenteController::class);

    Route::resource('fournisseurs', FournisseurController::class);
    Route::resource('livraisons', LivraisonController::class);

    Route::resource('depenses', DepenseController::class);
});

Route::middleware(['auth', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/boutiques', [BoutiqueController::class, 'index'])->name('boutiques.index');
    Route::get('/boutiques/{boutique}', [BoutiqueController::class, 'show'])->name('boutiques.show');
    Route::patch('/boutiques/{boutique}/suspend', [BoutiqueController::class, 'suspend'])->name('boutiques.suspend');
    Route::patch('/boutiques/{boutique}/reactivate', [BoutiqueController::class, 'reactivate'])->name('boutiques.reactivate');
    Route::delete('/boutiques/{boutique}', [BoutiqueController::class, 'destroy'])->name('boutiques.destroy');
    Route::get('/journal', [BoutiqueController::class, 'journal'])->name('journal.index');
    Route::get('/statistiques', [BoutiqueController::class, 'statistiques'])->name('statistiques.index');
});

require __DIR__.'/auth.php';