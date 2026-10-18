<?php

namespace App\Http\Controllers;

// request
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function index()
    {
        return view('presentation', [
            'prenom' => 'Alice',

            'objectif' => 'Créer mon premier gestionnaire de tâcjes',
            'taches' => [
                [
                    'titre' => 'Apprendre les routes',
                    'terminée' => true,
                ],
                [
                    'titre' => 'créer une vue',
                    'terminée' => false,
                ],
                [
                    'titre' => 'avoir un bon niveau en laravel',
                    'terminée' => false,
                ],
            ],
        ]);
    }

    public function saluer(string $prenom)
    {
        return view('salutation', [
            'prenom' => $prenom,
        ]);
    }

    public function profil(string $prenom, string $ville)
    {
        return view('profil', [
            'prenom' => $prenom,
            'ville' => $ville,
        ]);
    }

    // methode de récupération du prénom
    public function accueil(Request $request)
    {
        $prenom = $request->query('prenom', 'visiteur');
        $ville = $request->query('ville', 'ville inconnue');

        return view('accueil', [
            'prenom' => $prenom,
            'ville' => $ville,
        ]);
    }

    public function creerTache()
    {
        return view('taches.creer');
    }

    public function recevoirTache(Request $request)
    {
        /* version simple */
        // $titre = $request->input('titre');
        // $description = $request->input('description');

        // return view('taches.confirmation', [
        //     'titre' => $titre,
        //     'description' => $description,
        // ]);
        /* version avec validation */
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
        ],
        [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.string' => 'Le titre doit être un texte.',
            'titre.min' => 'Le titre doit contenir au moins :min caractères.',
            'titre.max' => 'Le titre ne doit pas dépasser :max caractères .',
        ],
        [
            'description.string' => 'La description ne doit contenir que des caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères',
        ]);
        return view('taches.confirmation', [
            'titre' => $donnees['titre'],
            'description' => $donnees['description'] ?? '',
        ]);
    }
}
