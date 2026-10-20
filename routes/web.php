<?php

use App\Http\Controllers\BibliothequeController;
use App\Http\Controllers\PresentationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\ConnexionController;
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

//inscription
Route::get('/inscription', [
    InscriptionController::class,
    'create',
])->name('register');

Route::post('/inscription', [
    InscriptionController::class,
    'store',
])->name('register.store');

//connexion
Route::get('/connexion', [
    ConnexionController::class,
    'create',
])->name('login');

Route::post('/connexion', [
    ConnexionController::class,
    'store',
])->middleware('throttle:5,1')->name('login.store');

//déconnexion
Route::post('/deconnexion', [
    ConnexionController::class,
    'destroy'
])->middleware('auth')->name('logout');





