<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthentificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_peut_se_connecter(): void
    {
        $motDePasse = 'MotDePasseDeTest123!';

        $utilisateur = User::factory()->create([
            'password' => Hash::make($motDePasse),
        ]);

        $this->assertGuest();

        $reponse = $this->post(route('login.store'), [
            'email' => $utilisateur->email,
            'password' => $motDePasse,
        ]);

        $reponse->assertRedirect(route('taches.index'));

        $this->assertAuthenticatedAs($utilisateur);

    }

    public function test_un_utilisateur_ne_peut_se_connecter_avec_un_mauvais_mot_de_passe(): void {
        $motDePasse ='MotDePasseDeTest123!';
        $badpassword = 'MotDePasseDeTest12!';
        $utilisateur = User::factory()->create([
            'password' => Hash::make($motDePasse),
        ]);

        $this->assertGuest();

        $reponse = $this->post(route('login.store'), [
            'email' => $utilisateur->email,
            'password' => $badpassword,
        ]);

        $reponse->assertSessionHasErrors('email');
        $this->assertGuest();

    }

    public function test_la_déconnexion_d_un_utilisateur(): void {
        $motDePasse = 'MotDePasseDeTest123!';
        $utilisateur = User::factory()->create([
        'password' => Hash::make($motDePasse),
        ]);

        $this->actingAs($utilisateur);

        $reponse = $this->post(route('logout'));

        $reponse->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
