@extends('layouts.app')

@section('titre', 'Mes taches')

@section('contenu')
    <h1>Mes Taches enregistrées</h1>

    <p>Nombre de tâches {{ count($taches) }}</p>

    <a href="{{ route('taches.create') }}"> Créer une tâche</a>

    <form action="{{ route('taches.index') }}" method="get">
        <label for="etat">Afficher</label>

        <select name="etat" id="etat">
            <option value="toutes" @selected($etat === 'toutes')>
                Toutes les tâches
            </option>
            <option value="a_faire" @selected($etat === 'a_faire')>
                A faire
            </option>
            <option value="terminees" @selected($etat === 'termineed')>
                Terminées
            </option>
        </select>

        <button type="submit">Filtrez</button>
    </form>
    <ul>
        @forelse ($taches as $tache)
            <li>
                <h2>
                    <a href="{{ route('taches.show', ['tache' => $tache->id]) }}">
                        {{ $tache->titre }}
                    </a>
                </h2>
                @if ($tache->description)
                    <p>{{ $tache->description }}</p>
                @endif
                @if ($tache->terminee)
                    <p>Terminéé</p>
                @else
                    <p>A faire</p>
                @endif
            </li>
        @empty
            <li>Aucune tâche enregistrée</li>
        @endforelse
    </ul>
@endsection
