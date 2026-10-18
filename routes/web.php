<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BibliothequeController;
use App\Http\Controllers\PresentationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/bonjour', function () {
    return 'Bonjour Elfred, bienvenue dans Laravel';
});

Route::get('/presentation', [PresentationController::class, 'index']);

Route::get('/bibliotheque', [BibliothequeController::class, 'index']);
