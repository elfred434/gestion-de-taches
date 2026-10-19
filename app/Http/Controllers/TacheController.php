<?php

namespace App\Http\Controllers;
use App\Models\Tache;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    public function create()
    {
        return view('taches.creer');
    }

    public function store(Request $request)
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
            'titre.string' => 'Le titre doit être des caractères.',
            'titre.min' => 'Le titre doit contenir au moins :min caractères.',
            'titre.max' => 'Le titre ne doit pas dépasser :max caractères .',
            'description.string' => 'La description ne doit contenir que des caractères.',
            'description.max' => 'La description ne doit pas dépasser :max caractères',
        ]);
        $tache = new Tache();
        $tache->titre = $donnees['titre'];
        $tache->description = $donnees['description'] ?? null;

        $tache->save();    
        return redirect()
        ->route('taches.index')
        //ajout du message de confirmation
        -> with('succes', 'La tâche a bien été enregistrées') ;    
            
        // return view('taches.confirmation', [
        //     'titre' => $donnees['titre'],
        //     'description' => $donnees['description'] ?? '',
        // ]);
    }

     public function index(){
        $taches = Tache::orderByDesc('id')->get();

        return view('taches.index', [
            'taches' => $taches,
        ]);
    }

    public function show(Tache $tache){
        return view('taches.show', [
            'tache' => $tache,
        ]);
    }
}
