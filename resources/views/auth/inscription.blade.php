@extends('layouts.app')

@section('titre', 'Créer un compte')

@section('contenu')
    <h1>Créer un compte</h1>

    <form action="{{ route('register.store') }}" method="post">
        @csrf
        @error('name')
            <p>{{$message}}</p>
        @enderror
        @error('email')
            <p>{{$message}}</p>
        @enderror
        @error('password')
            <p>{{$message}}</p>
        @enderror
        <div>
            <label for="name">Nom</label>
            <input type="text" id="name" name="name"
            value="{{ old('name') }}"
            autocomplete="name">
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
            value="{{ old('email') }}"
            autocomplete="email">
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password"
            value="{{ old('password') }}"
            autocomplete="new-password">
        </div>

        <div>
            <label for="password_confirmation"> Confirmer le password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
            autocomplete="new-password">
        </div>

        <button type="submit">Créer mon compte</button>
    </form>
@endsection