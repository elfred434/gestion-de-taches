<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>La bibliothèque de {{$prenom}}</title>
</head>
<body>
    <h1>La bibliothèque de {{$prenom}}</h1>
    <p>Nombre totale de livres {{ count($livres) }}</p>
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
    
</body>
</html>