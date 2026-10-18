@extends('layouts.app')

@section('titre', 'Ma présentation')

@section('contenu')
    <h1>Bonjour, je suis {{ $prenom }}</h1>
    <p>J'apprends à créer des applications avec Laravel.</p>
    <p>Mon objectif est de :{{ $objectif }}</p>
    <br>
    <h2>Mes tâches</h2>
    <p>Nombre de taches pour l'instant {{count($taches)}}</p>
    <ul>
        @forelse ($taches as $task )
            <li>{{ $task['titre'] }}
                @if ($task['terminée'])
                    -- terminée
                @else 
                    -- A faire
                @endif
            </li>
            
        @empty
            <li>Aucune tâche pour le moment</li>
        @endforelse
    </ul>
    
    <a href="{{ route('profil.show', [
    'prenom'=> $prenom,
    'ville' => 'Calavi']) }}">
    Voir mon profil</a>
@endsection