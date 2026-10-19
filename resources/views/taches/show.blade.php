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

    <a href="{{ route('taches.index') }}">
        Retour aux tâches
    </a>
@endsection