<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bonjour', function () {
    return 'Bonjour Elfred, bienvenue dans Laravel';
});

Route::get('/presentation', function() {
    return view('presentation', [
        'prenom' => 'Elfred',
        
        'objectif' => 'Créer mon premier gestionnaire de tâcjes',
        'taches' => [
            'Apprendre les routes',
            'Créer une vue',
            'Afficher des données',
            'Avoir un bon niveau en laravel',
        ]
    ]);
});
