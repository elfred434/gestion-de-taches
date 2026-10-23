<?php

namespace App\Http\Controllers;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreTacheRequest;
use App\Http\Requests\UpdateTacheRequest;
use App\Http\Requests\IndexTacheRequest;
class TacheController extends Controller

{
    public function create()
    {
        return view('taches.creer');
    }

    public function store(StoreTacheRequest $request)
    {
        /* version simple */
        // $titre = $request->input('titre');
        // $description = $request->input('description');

        // return view('taches.confirmation', [
        //     'titre' => $titre,
        //     'description' => $description,
        // ]);
        /* version avec validation */
        $donnees = $request->validated();
        $tache = new Tache();
        $tache->titre = $donnees['titre'];
        $tache->description = $donnees['description'] ?? null;
        $request->user()->taches()->save($tache);   
        return redirect()
        ->route('taches.index')
        //ajout du message de confirmation
        -> with('succes', 'La tâche a bien été enregistrées') ;    
            
        // return view('taches.confirmation', [
        //     'titre' => $donnees['titre'],
        //     'description' => $donnees['description'] ?? '',
        // ]);
    }

     public function index(IndexTacheRequest $request){

        $donnees = $request->validated();

        $etat = $donnees['etat'] ?? 'toutes';
        $q = $donnees['q'] ?? '';
        

        //Afficher les tâches en fonction de l'users
        /* Ancienne function
        $requete = Tache::where('user_id', $request->user()->id); */

        $requete = $request->user()->taches();
        /*
        Afficher toutes les tâche
        $requete = Tache::query();
        */
        if ($etat === 'a_faire') {
            $requete->where('terminee', false);
        } elseif ($etat === 'terminees'){
            $requete->where('terminee', true);
        }

        if ($q !== '') {
            $requete->where('titre', 'like', '%' . $q . '%');
        }
        
        // $taches = $requete->orderByDesc('id')->get(); simple 
        //avec pagination
        $taches = $requete
        -> orderByDesc('id')
        ->paginate(5)
        ->withQueryString();

        return view('taches.index', [
            'taches' => $taches,
            'etat' => $etat,
            'q' => $q,
        ]);
    }

    public function show(Tache $tache){
        Gate::authorize('view', $tache);
        return view('taches.show', [
            'tache' => $tache,
        ]);
    }
    public function edit(Tache $tache){
        Gate::authorize('update', $tache);
        return view('taches.edit', [
            'tache' => $tache,
        ]);
    }
    public function update(UpdateTacheRequest $request, Tache $tache) {
        
        $donnees = $request->validated();
        $tache->titre = $donnees['titre'];
        $tache->description = $donnees['description'] ?? null;
        $tache->terminee = $request->boolean('terminee');
        $tache->save();

        return redirect()
        ->route('taches.show', ['tache' => $tache->id])
        -> with('succes', 'la tâche a bien été modifié');
    }

    public function destroy(Tache $tache){
        Gate::authorize('delete', $tache);
        $tache->delete();
        return redirect()
        ->route('taches.index')
        ->with('succes', 'La tâche a été supprimé');
    }
}
