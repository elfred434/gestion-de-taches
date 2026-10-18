@extends('layouts.app')

@section('titre', 'Profil')

@section('contenu')
    <h1>Bonjour {{$prenom}}</h1>
    <p>Vous vivez à {{$ville}}</p>
@endsection