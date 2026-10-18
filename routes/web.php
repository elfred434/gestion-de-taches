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
                'titre' => 'Le petit prince',
                'auteur' => 'Antoine de Saint-Exupéry',
                'lu' => true
            ],
            [
                'titre' => "L'enfant noir",
                'auteur' => 'Camara Laye',
                'lu' => false
            ],
            [
                'titre' => "Une si longue lettre",
                'auteur' => "Mariama Bâ",
                'lu' => false
            ],

        ]
    ]);
});
