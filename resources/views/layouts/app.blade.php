<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titre', 'Mon application')</title>
    <style>
        .navbar{
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .right{
            margin-left: auto
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('presentation.show') }}">Présentation</a>
        <a href="{{ route('bibliotheque.index') }}">Bibliothèque</a>
        <a href="{{ route('taches.index') }}">Mes tâches</a>
        <a href="{{ route('register') }}">Inscription</a>

        <div class="right">
            @auth
                <span>Connecté : {{auth()->user()->name}}</span>

                <form action="{{ route('logout') }}" method="post">
                    @csrf

                    <button type="submit">Se déconnecter</button>
                </form>
            @endauth

            @guest
            <a href="{{ route('register') }}">Inscription</a>
                <a href="{{ route('login') }}"> Se connecter</a>
            @endguest
        </div>
    </nav>

    <hr>

    <main>
        @if (session('succes'))
            <p role="status">{{ session('succes')}}</p>
        @endif
        @yield('contenu')
    </main>

    <footer>
        <p>Mon application Laravel - Elfred</p>
    </footer>
</body>
</html>