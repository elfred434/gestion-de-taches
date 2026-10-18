<?php

use App\Http\Controllers\BibliothequeController;
use App\Http\Controllers\PresentationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bonjour', function () {
    return 'Bonjour Elfred, bienvenue dans Laravel';
});

Route::get('/presentation', [PresentationController::class, 'index'])
    ->name('presentation.show');

Route::get('/bibliotheque',
    [BibliothequeController::class,
        'index'])->name('bibliotheque.index');

Route::get('/salutation/{prenom}', [
    PresentationController::class,
    'saluer',
]);

Route::get('/profil/{prenom}/{ville}', [
    PresentationController::class,
    'profil',
])->name('profil.show');

Route::get('/accueil', [
    PresentationController::class,
    'accueil',
])->name('accueil');

Route::get('/taches/creer', [
    PresentationController::class,
    'creerTache',
])->name('taches.create');

Route::post('/taches', [
    PresentationController::class,
    'recevoirTache',
])->name('taches.store');