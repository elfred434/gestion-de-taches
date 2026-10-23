@extends('layouts.app')

@section('titre', 'Nouvelle tâches')

@section('contenu')
    <h1>Proposer une tâche</h1>
    @error('titre')
        <p>{{ $message }}</p>
    @enderror
    @error('description')
        <p>{{ $message}}</p>
    @enderror
    @error('priorite')
            <p>{{ $message }}</p>
        @enderror

<form action="{{ route('taches.store') }}" method="post">
    @csrf
    <label for="titre">Titre de la tâche</label>
    <input type="text" id="titre" name="titre" value="{{ old('titre') }}">
    <label for="description">description</label>
    <textarea name="description" id="description" cols="30" rows="10" >{{ old('description') }}</textarea>
    <div>
            <label for="priorite">Priorité</label>
            <select name="priorite" id="priorite">
            <option value="basse" @selected( old('priorite', 'normale') === 'basse' )>
             Basse
            </option>
            <option value="Normale" @selected(  old('priorite', 'normale') === 'normale'  )>
                Normale
            </option>
            <option value="Haute" @selected(old('priorite', 'normale' ) === 'haute')>
                Haute
            </option>
        </select>
        </div>
    <button type="submit">Envoyez</button>
</form>

@endsection
