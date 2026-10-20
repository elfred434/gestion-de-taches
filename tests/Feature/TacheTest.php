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

        // vérifie que les détails de la tâche supprimé renvoie un status

        $this->get(
            route('taches.show', ['tache' => $tacheASupprimer->id])
        )->assertNotFound();
    }

    private function creerTache(string $titre, bool $terminee = false): Tache
    {
        $tache = new Tache;
        $tache->titre = $titre;
        $tache->terminee = $terminee;
        $tache->save();

        return $tache;
    }

    public function test_le_filtre_a_faire_exclut_les_taches_terminees(): void
    {
        $aFaire = $this->creerTache('Apprendre Laravel');
        $this->creerTache('Réviser Laravel', true);

        $reponse = $this->get(route('taches.index', [
            'etat' => 'a_faire',
        ]));

        $reponse->assertOk();

        $reponse->assertViewHas('taches', function ($taches) use ($aFaire) {
            return $taches->total() === 1
            && $taches->getCollection()->modelKeys() === [$aFaire->id];
        });
    }
    public function test_le_filtre_terminee_exclut_les_taches_non_terminees(): void
    {
        $terminees = $this->creerTache('Testez le filtre terminee', true);
        $this->creerTache('Voir le filtre');

        $reponse = $this->get(route('taches.index', [
            'etat' => 'terminees',
        ]));

        $reponse->assertOk();

        $reponse->assertViewHas('taches', function ($taches) use ($terminees) {
            return $taches->total() === 1
            && $taches->getCollection()->modelKeys() === [$terminees->id];
        });
    }

    public function test_une_recherche_sans_resultat_affiche_un_message_vide(): void
    {
        $this->creerTache('Apprendre PHP');

        $reponse = $this->get(route('taches.index', [
            'q' => 'Laravel',
        ]));
        //réponse 200
        $reponse->assertOk();
        //total de 0 ; collection vide ;
        $reponse->assertViewHas('taches', function ($taches) {
            return $taches->total() === 0
                && $taches->getCollection()->isEmpty();
        });
        //message
        $reponse->assertSee('Aucune tâche ne correspond à ces critères.');
    }

    public function test_la_pagination_affiche_les_taches_sur_deux_pages_sans_chevauchement(): void
    {
        for ($numero = 1; $numero <= 8; $numero++) {
            $this->creerTache("Tâche pagination $numero");
        }

        $reponsePage1 = $this->get(route('taches.index'));
        $reponsePage1->assertOk();

        $identifiantsPage1 = [];
        $reponsePage1->assertViewHas('taches', function ($taches) use (&$identifiantsPage1) {
            $identifiantsPage1 = $taches->getCollection()->modelKeys();

            return $taches->count() === 5
                && $taches->total() === 8
                && $taches->currentPage() === 1;
        });

        $reponsePage2 = $this->get(route('taches.index', ['page' => 2]));
        $reponsePage2->assertOk();

        $identifiantsPage2 = [];
        $reponsePage2->assertViewHas('taches', function ($taches) use (&$identifiantsPage2) {
            $identifiantsPage2 = $taches->getCollection()->modelKeys();

            return $taches->count() === 3
                && $taches->total() === 8
                && $taches->currentPage() === 2;
        });

        $this->assertSame([], array_intersect($identifiantsPage1, $identifiantsPage2));
    }

    public function test_la_pagination_conserve_les_criteres_de_recherche(): void
    {
        for ($numero = 1; $numero <= 6; $numero++) {
            $this->creerTache("Tâche Laravel $numero");
        }
        $this->creerTache('Tâche Laravel terminée', true);
        $this->creerTache('Tâche PHP');

        $reponse = $this->get(route('taches.index', [
            'etat' => 'a_faire',
            'q' => 'Laravel',
        ]));

        $reponse->assertOk();
        $reponse->assertViewHas('taches', function ($taches) {
            return $taches->total() === 6
                && $taches->count() === 5;
        });
        $reponse->assertViewHas('taches', function ($taches) {
            $url = $taches->nextPageUrl();

            if ($url === null) {
                return false;
            }

            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $parametres);

            return ($parametres['etat'] ?? null) === 'a_faire'
                && ($parametres['q'] ?? null) === 'Laravel'
                && ($parametres['page'] ?? null) === '2';
        });
    }
}
