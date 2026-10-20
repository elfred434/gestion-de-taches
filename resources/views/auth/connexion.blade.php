@extends('layouts.app')

@section('titre', 'Connexion')

@section('contenu')
    <h1>Se connecter</h1>

    <form action="{{ route('login.store') }}" method="post">
        @csrf
        
        
        <div>
            
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
         value="{{ old('email') }}"
         autocomplete="email">

        @error('email')
            <p>{{  $message }}</p>
        @enderror
        </div>

        <div>
            <label for="password"> Mot de passe</label>
            <input type="password" name="password" id="password"
            autocomplete="current-password">
            @error('password')
            <p>{{ $message }}</p>
        @enderror

        </div>
        <button type="submit">Se connecter</button>
    </form>
@endsection