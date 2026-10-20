<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnexionController extends Controller
{
    public function create(){
        return view('auth.connexion');
    }

    public function store(Request $request) {
        $identifiants = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        //Auth::attempt() vérifie les identifiants et établit la connexion s’ils sont corrects
        if (! Auth::attempt($identifiants)) {
             return back()
             ->withErrors([
                'email' => 'Email ou mot de passe incorrect.'
             ])
            //onlyInput('email') conserve uniquement l’email dans l’ancienne saisie, pas le mot de passe.
             ->onlyInput('email');
        }
        //session()->regenerate() renouvelle l’identifiant de session après la connexion pour prévenir la fixation de session
        $request->session()->regenerate();

        return redirect()
        ->route('taches.index')
        ->with('succes', 'Tu es maintenant connecté');
    }
}
