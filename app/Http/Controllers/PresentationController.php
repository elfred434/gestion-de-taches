<?php

namespace App\Http\Controllers;



class PresentationController extends Controller
{
    public function index(){
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
    }

    public function saluer(string $prenom){
        return view('salutation', [
            'prenom' => $prenom
        ]);
    }
    public function profil(string $prenom, string $ville){
        return view('profil', [
            'prenom' => $prenom,
            'ville' => $ville
        ]);
    }
}
