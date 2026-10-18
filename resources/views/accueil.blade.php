@extends('layouts.app')

@section('titre', 'Accueil personnalisé')

@section('contenu')
    <h1>Bonjour {{ $prenom }}</h1>
    <h3>Tu habites à {{ $ville }}</h3>
    <form action="{{ route('accueil') }}" method="get">
        <label for="prenom">Ton prénom</label>
        <input type="text" id="prenom" name="prenom">
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville">
        <button type="submit">Afficher mon prénom et ma ville</button>
    </form>
@endsection
