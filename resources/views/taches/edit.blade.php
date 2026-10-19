@extends('layouts.app')

@section('titre', 'Modifier une tâche')

@section('contenu')
    <h1>Modifier la tâche</h1>

    <div>
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" value="{{ old('titre', $tache->titre) }}">
    </div>

    <div>
        <label for="description">Description</label>
        <textarea type="text" id="description" name="description" row="5">
        {{ old('description', $tache->description) }}
        </textarea>
    </div>
    <p>L'enregistrement des modifications sera ajouté à la prochaine étape</p>

    <a href="{{ route('taches.show', ['tache' => $tache->id]) }}">
        Annuler et revenir au détail
    </a>
@endsection