<?php

namespace App\Http\Controllers;



class BibliothequeController extends Controller
{
    public function index(){
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
    }
}
