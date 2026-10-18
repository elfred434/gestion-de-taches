@extends('layouts.app')

@section('titre', 'Confirmation')

@section('contenu')
    <h1>Tâches reçue</h1>

    <p>Titre: {{ $titre }}</p>
    <p>description: {{$description}}</p>
    <p>Cette tâches est  enregistrée.</p>
    <a href="{{ route('taches.create') }}">
        Revenir au formulaire
    </a>
@endsection