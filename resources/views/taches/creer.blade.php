@extends('layouts.app')

@section('titre', 'Nouvelle tâches')

@section('contenu')
    <h1>Proposer une tâche</h1>
    <form action="{{ route('taches.store') }}" method="post">
        @csrf
        <label for="titre">Titre de la tâche</label>
        <input type="text" id="titre" name="titre">
        <label for="description">description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>

        <button type="submit">Envoyez</button>
    </form>

@endsection