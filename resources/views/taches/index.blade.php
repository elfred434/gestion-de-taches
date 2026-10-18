@extends('layouts.app');

@section('titre', 'Mes taches')

@section('contenu')
    <h1>Mes Taches enregistrées</h1>

    <p>Nombre de tâches  {{count($taches)}}</p>

    <a href="{{ route('taches.create') }}"> Créer une tâche</a>
    <ul>
        @forelse ($taches as $tache )
            <li>
                <h2>{{$tache->titre}}</h2>
                @if ($tache->description)
                    <p>{{$tache->description}}</p>
                @endif
                @if ($tache->terminee)
                    <p>Terminéé</p>
                @else
                    <p>A faire</p>
                @endif
            </li>
        @empty
            <li>Aucune tâche enregistrée</li>
        @endforelse
    </ul>
@endsection