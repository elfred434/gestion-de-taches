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
            [
                'titre' => 'Apprendre les routes',
                'terminée' => true,
            ],
            [
                'titre' => 'créer une vue',
                'terminée' => false
            ],
            [
                'titre' => 'avoir un bon niveau en laravel',
                'terminée' => false
            ],
        ]
    ]);
});

Route::get('/bibliotheque', function() {
    return view('bibliotheque', [
        'prenom' => 'Elfred',
        'livres' => [
            [
                'Titre' => 'Le petit prince',
                'Auteur' => 'Antoine de Saint-Exupéry',
                'lu' => true
            ],
            [
                'Titre' => "L'enfant noir",
                'Auteur' => 'Camara Laye',
                'lu' => false
            ],
            [
                'Titre' => "Une si longue lettre",
                'Auteur' => "Mariame Bâ",
                'lu' => false
            ],

        ]
    ]);
});
