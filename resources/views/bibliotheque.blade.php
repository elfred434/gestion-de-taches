<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>La bibliothèque de {{$prenom}}</title>
</head>
<body>
    <h1>Nombre totale de livres {{ count($livres) }}</h1>
    <h2>Liste des livres</h2>
    <ul>
        @forelse ($livres as $livre )
            <li> {{ $livre["Titre"]  }} de l'Auteur
                 "{{ $livre["Auteur"] }}"
            @if ($livre['lu'])
                -- Déjà lu
            @else 
                -- A lire
            @endif
            </li>
        @empty
            <p>Aucun livre dans ta bibliothèque</p>       
        @endforelse
    </ul>
    
</body>
</html>