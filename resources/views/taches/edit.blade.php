@extends('layouts.app')

@section('titre', 'Modifier une tâche')

@section('contenu')
    <h1>Modifier la tâche</h1>


    <form action="{{ route('taches.update', ['tache' => $tache->id]) }}" method="post">
        @csrf
        @method('PATCH')
        @error('titre')
            <p>{{ $message }}</p>
        @enderror
        @error('description')
            <p>{{ $message }}</p>
        @enderror
        @error('priorite')
            <p>{{ $message }}</p>
        @enderror
        <div>
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="{{ old('titre', $tache->titre) }}">
        </div>

        <div>
            <label for="description">Description</label>
           <textarea id="description" name="description" rows="5">{{ old('description', $tache->description) }}</textarea>
        </div>
        <div>
            <input type="hidden" name="terminee" value="0">

            <input type="checkbox" name="terminee" id="terminee"
            value="1" @checked( old('terminee' , $tache->terminee))>
            <label for="terminee">Cette tâche est terminée ?</label>

            @error('terminee')
                <p>{{ $message}}</p>
            @enderror
        </div>
        <div>
            <label for="priorite">Priorité</label>
            <select name="priorite" id="priorite">
                <option value="basse" @selected(old('priorite', $tache->priorite) === 'basse')>
                    Basse
                </option>
                <option value="normale" @selected(old('priorite', $tache->priorite) === 'normale')>
                    Normale
                </option>
                <option value="haute" @selected(old('priorite', $tache->priorite) === 'haute')>
                    Haute
                </option>
            </select>
        </div>
        <button type="submit">Enregistrez les modifications</button>
    </form>
    

    <a href="{{ route('taches.show', ['tache' => $tache->id]) }}">
        Annuler et revenir au détail
    </a>
@endsection
