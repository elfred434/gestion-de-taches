@extends('layouts.app')

@section('titre', 'Ma bibliothèque')

@section('contenu')
    <h1>La bibliothèque de {{$prenom}}</h1>
    <p>Nombre total de livres {{ count($livres) }}</p>
    <h2>Liste des livres</h2>
    <ul>
        @forelse ($livres as $livre )
            <li> {{ $livre["titre"]  }} de l'Auteur
                 "{{ $livre["auteur"] }}"
            @if ($livre['lu'])
                -- Déjà lu
            @else 
                -- À lire
            @endif
            </li>
        @empty
            <li>Aucun livre dans ta bibliothèque</li>       
        @endforelse
    </ul>
    <a href="{{ route('presentation.show') }}">
        Retour à ma présentation
    </a>
@endsection