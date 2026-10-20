<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function create(){
        return view('auth.inscription');

    }

    public function store(Request $request){
        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ],
        [
            'name.required' => 'Le champs name est requis',
            'name.string' => 'le champs name ne doit contenir que des caractères',
            'name.max' => 'le champs name ne doit pas dépassez les :max caractères',
            'email.required' => 'le champs email est requis',
            'email.email' => 'les information à l\'intérieur du champ email ne respecte par le format d\'email',
            'email.max' => 'le champs email ne doit pas excédez les :max caractères',
            'email.unique' => 'L\'email existe déjà ',
            'password.required' => 'le mot de passe est requis',
            'password.string' => 'le mot de passe doit contenir des caractères',
            'password.min' => 'le mot de passe doit au moins contenir 12 caractères',
            'password.confirmed' => 'le mot doit être confirmé',
        ]);

        $utilisateur = new User();
        $utilisateur->name = $donnees['name'];
        $utilisateur->email = $donnees['email'];
        $utilisateur->password = Hash::make($donnees['password']);
        $utilisateur->save();

        return redirect()
        ->route('register')
        ->with('succes', 'Ton compte a été créé.');


    }
}
