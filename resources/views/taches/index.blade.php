@extends('layouts.app')

@section('titre', 'Mes taches')

@section('contenu')
    <h1>Mes Taches enregistrées</h1>

    <p>Nombre de tâches affichées sur cette page  {{ count($taches) }},
        sur {{ $taches->total() }} résultat(s) </p>
    

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
            <option value="terminees" @selected($etat === 'terminees')>
                Terminées
            </option>
        </select>

        <label for="q"> Rechercher par titre</label>

        <input type="search" id="q" name="q" value="{{ $q }}">

        @error('q')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Rechercher</button>
        <a href="{{ route('taches.index') }}">Réinitialiser</a>
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

                @if ($tache->priorite === 'basse')
                <p>Priorité basse</p>
                @elseif ($tache->priorite === 'normale')
                <p>Priorité normale</p>
                @else
                <p>Priorité haute</p>
                    
                @endif

            </li>
        @empty
            <li>Aucune tâche ne correspond à ces critères.</li>
        @endforelse
    </ul>
    {{ $taches->links() }}
@endsection
