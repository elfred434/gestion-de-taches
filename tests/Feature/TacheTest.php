<?php

namespace Tests\Feature;

use App\Models\Tache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_liste_vide_est_accessible(): void
    {
        $reponse = $this->get(route('taches.index'));

        $reponse->assertOk();
        $reponse->assertSee(
            'Aucune tâche ne correspond à ces critères'
        );
    }

    public function test_le_formulare_de_création_est_accessible(): void
    {
        $reponse = $this->get(route('taches.create'));

        $reponse->assertOk();
        $reponse->assertViewIs('taches.creer');

    }

    public function test_une_tâche_valide_est_enregistree(): void
    {
        // $this->post(...)	Envoie une requête POST avec les données indiquées
        $reponse = $this->post(route('taches.store'), [
            'titre' => 'Apprendre les tests Laravel',
            'description' => 'Vérifier la création automatique.',
        ]);

        // Vérifier la réponse http
        // assertRedirect(...)	Vérifie que la réponse redirige vers l’adresse attendue
        $reponse->assertRedirect(route('taches.index'));

        // vérfier les donéées enregistrées
        // assertDatabaseHas(...)	Vérifie qu’une ligne correspond aux valeurs données
        $this->assertDatabaseHas('taches', [
            'titre' => 'Apprendre les tests Laravel',
            'description' => 'Vérifier la création automatique.',
            'terminee' => 0,
        ]);
        // assertDatabaseCount(...)	Vérifie le nombre de lignes de la table
        $this->assertDatabaseCount('taches', 1);

    }

    public function test_une_tâche_invalide_est_non_enregistree(): void
    {
        $reponse = $this->post(route('taches.store'), [
            'titre' => 'ab',
            'description' => 'Vérifier la non création d\'une tâche non valide .',
        ]);

        $reponse->assertSessionHasErrors('titre');

        $this->assertDatabaseCount('taches', 0);

    }

    public function test_une_tache_peut_être_modifiee(): void
    {

        // Péparez une tâche dans la base de test
        $tache = new Tache;
        $tache->titre = 'Ancien titre';
        $tache->description = 'Ancienne description';
        $tache->terminee = false;
        $tache->save();

        // envoyer la modification
        $reponse = $this->patch(
            route('taches.update', ['tache' => $tache->id]),
            [
                'titre' => 'Nouveau titre',
                'description' => 'Nouvelle description',
                'terminee' => 1,
            ]
        );

        // vérifier la destination
        $reponse->assertRedirect(
            route('taches.show', ['tache' => $tache->id])
        );

        // Vérifier que la ligne initiale est modifiée.
        $this->assertDatabaseHas('taches', [
            'id' => $tache->id,
            'titre' => 'Nouveau titre',
            'description' => 'Nouvelle description',
            'terminee' => 1,
        ]);

        // Vérifier que aucune ligne supplémentaire n'a été ajouté
        $this->assertDatabaseCount('taches', 1);

    }

    public function test_une_tache_invalide_ne_peut_être_modifiee(): void
    {
        $tache = new Tache;
        $tache->titre = 'Titre à conserver';
        $tache->description = 'test description';
        $tache->terminee = false;
        $tache->save();

        $reponse = $this->patch(
            route('taches.update', ['tache' => $tache->id]),
            [
                'titre' => 'ab',
                'description' => 'Nouvelle description',
                'terminee' => 1,
            ]
        );

        $reponse->assertSessionHasErrors('titre');

        $this->assertDatabaseHas('taches', [
            'id' => $tache->id,
            'titre' => 'Titre à conserver',
            'description' => 'test description',
            'terminee' => 0,
        ]);

        $this->assertDatabaseCount('taches', 1);
    }

    public function test_supprimer_une_tache_conserve_les_autres(): void
    {
        $tacheASupprimer = new Tache;
        $tacheASupprimer->titre = 'Tâche à supprimer';
        $tacheASupprimer->terminee = false;
        $tacheASupprimer->save();

        $tacheAConserver = new Tache;
        $tacheAConserver->titre = 'Tâche à conserver';
        $tacheAConserver->terminee = false;
        $tacheAConserver->save();

        $reponse = $this->delete(
            route('taches.destroy', ['tache' => $tacheASupprimer->id])
        );
        $reponse->assertRedirect(
            route('taches.index')
        );

        // la tâche ciblé n'existe plus
        $this->assertDatabaseMissing('taches', [
            'id' => $tacheASupprimer->id,
        ]);

        $this->assertDatabaseHas('taches', [
            'id' => $tacheAConserver->id,
            'titre' => 'Tâche à conserver',
            'description' => null,
            'terminee' => 0,
        ]);

        $this->assertDatabaseCount('taches', 1);

        //vérifie que les détails de la tâche supprimé renvoie un status

        $this->get(
            route('taches.show', ['tache' => $tacheASupprimer->id])
        )->assertNotFound();
    }
}
