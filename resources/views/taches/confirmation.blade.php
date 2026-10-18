@extends('layouts.app')

@section('titre', 'Confirmation')

@section('contenu')
    <h1>Tâches reçue</h1>

    <p>Titre: {{ $titre }}</p>
    <p>description: {{$desc}}</p>
    <p>Cette tâches n'est pas encore enregistrée.</p>
    <a href="{{ route('taches.create') }}">
        Revenir au formulaire
    </a>
@endsection