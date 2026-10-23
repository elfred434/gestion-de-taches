@extends('layouts.app')

@section('titre', 'Détails de la tâche')

@section('contenu')
    <h1>{{$tache->titre}}</h1>

    <p>
       Description: 
       {{ $tache->description ?? 'Aucune description' }}
    </p>
    <p>
       @if ($tache->terminee)
           Terminée
        @else
             À faire
       @endif
    </p>
    <div>
        @if ($tache->priorite === 'basse')
                <p>Priorité basse</p>
                @elseif ($tache->priorite === 'normale')
                <p>Priorité normale</p>
                @else
                <p>Priorité haute</p>
                    
                @endif
    </d>

    <a href="{{ route('taches.index') }}">
        Retour aux tâches
    </a>
    <a href="{{ route('taches.edit', ['tache' => $tache->id]) }}">
        modifer cette tâche
    </a>
    <form action="{{ route('taches.destroy', ['tache' => $tache->id]) }}" method="post">
        @csrf
        @method('DELETE')

        <button type="submit">Supprimer définitevement cette tâche</button>
    </form>
@endsection