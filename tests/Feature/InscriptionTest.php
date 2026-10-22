<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_peut_creer_un_compte(): void
    {
        $motDePasse = 'MotDePasseDeTest123!';

        $reponse = $this->post(route('register.store'), [
            'name' => 'Elfred Test',
            'email' => 'elfred@exemple.test',
            'password' => $motDePasse,
            'password_confirmation' => $motDePasse,
        ]);

        $reponse->assertRedirect(route('register'));
        $reponse->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Elfred Test',
            'email' => 'elfred@exemple.test',
        ]);

        $this->assertDatabaseCount('users', 1);

        $utilisateur = User::where(
            'email',
            'elfred@exemple.test'
        )->firstOrFail();

        $this->assertNotSame($motDePasse, $utilisateur->password);

        $this->assertTrue(
            Hash::check(($motDePasse), $utilisateur->password)
        );

        $this->assertGuest();

        /*
        assertSessionHasNoErrors()	Aucune erreur de validation dans la session
        assertNotSame(...)	La valeur enregistrée n’est pas le mot de passe en clair
        assertTrue(Hash::check(...))	Le hachage enregistré correspond bien au mot de passe saisi
        assertGuest()	L’inscription n’a pas connecté automatiquement le compte     */
    }

    public function test_email_déjà_utilisé(): void
    {
        $utilisateurExistant = User::factory()->create([
            'email' => 'deja-pris@exemple.test',
        ]);

        $motDePasse = 'MotDePasseDeTest123!';

        $reponse = $this->post(route('register.store'), [
            'name' => 'Elfred Test',
            'email' => 'deja-pris@exemple.test',
            'password' => $motDePasse,
            'password_confirmation' => $motDePasse,
        ]);
        $reponse->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $utilisateurExistant->id,
            'name' => $utilisateurExistant->name,
            'email' => $utilisateurExistant->email,
        ]);

        $this->assertGuest();

    }

    public function test_confirmation_differente(): void
    {
        $motDePasse = 'MotDePasseDeTest123!';
        $differentpassword = 'MotDePasseDeTest1234!';
        $reponse = $this->post(route('register.store'), [
            'name' => 'Elfred Test',
            'email' => 'elfred434@exemple.test',
            'password' => $motDePasse,
            'password_confirmation' => $differentpassword,
        ]);

        $reponse->assertSessionHasErrors('password');
        $this->assertDatabaseCount('users', 0);

        $this->assertGuest();

    }
}
