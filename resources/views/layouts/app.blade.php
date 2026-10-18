<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titre', 'Mon application')</title>
</head>
<body>
    <nav>
        <a href="{{ route('presentation.show') }}">Présentation</a>
        <a href="{{ route('bibliotheque.index') }}">Bibliothèque</a>
    </nav>

    <hr>

    <main>
        @yield('contenu')
    </main>
</body>
</html>