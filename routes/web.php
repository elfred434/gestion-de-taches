<?php

use App\Http\Controllers\BibliothequeController;
use App\Http\Controllers\PresentationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TacheController;
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
    TacheController::class,
    'create',
])->name('taches.create');

//recevoir une tâches
Route::post('/taches', [
    TacheController::class,
    'store',
])->name('taches.store');

//afficher les taches
Route::get('/taches', [
    TacheController::class,
    'index',
])->name('taches.index');

//afficher la tache via son id
Route::get('/taches/{tache}', [
    TacheController::class,
    'show',
])->name('taches.show');

//modifer une tâche via son id
Route::get('taches/{tache}/modifier', [
    TacheController::class,
    'edit',
])->name('taches.edit');

Route::patch('taches/{tache}', [
    TacheController::class,
    'update',
])->name('taches.update');
Route::delete('taches/{tache}', [
    TacheController::class,
    'destroy',
])->name('taches.destroy');