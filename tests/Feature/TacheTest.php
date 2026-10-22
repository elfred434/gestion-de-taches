<?php

namespace Tests\Feature;

use App\Models\Tache;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;


class TacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $utilisateur = User::factory()->create();
        $this->actingAs($utilisateur);
    }

    // Vérifie que la liste vide est accessible et affiche son message.
    public function test_la_liste_vide_est_accessible(): void
    {
        $reponse = $this->get(route('taches.index'));

        $reponse->assertOk();
        $reponse->assertSee(
            'Aucune tâche ne correspond à ces critères'
        );
    }

    // Vérifie que le formulaire de création est accessible et utilise la bonne vue.
    public function test_le_formulare_de_création_est_accessible(): void
    {
        $reponse = $this->get(route('taches.create'));

        $reponse->assertOk();
        $reponse->assertViewIs('taches.creer');

    }

    // Vérifie qu'une tâche valide est enregistrée puis que l'application redirige vers la liste.
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
            'user_id' => auth()->id(),
        ]);
        // assertDatabaseCount(...)	Vérifie le nombre de lignes de la table
        $this->assertDatabaseCount('taches', 1);

    }

    // Vérifie qu'une tâche invalide produit une erreur de validation et n'est pas enregistrée.
    public function test_une_tâche_invalide_est_non_enregistree(): void
    {
        $reponse = $this->post(route('taches.store'), [
            'titre' => 'ab',
            'description' => 'Vérifier la non création d\'une tâche non valide .',
        ]);

        $reponse->assertSessionHasErrors('titre');

        $this->assertDatabaseCount('taches', 0);

    }

    // Vérifie qu'une tâche existante peut être modifiée sans créer de ligne supplémentaire.
    public function test_une_tache_peut_être_modifiee(): void
    {

        // Péparez une tâche dans la base de test
        $tache = new Tache;
        $tache->titre = 'Ancien titre';
        $tache->description = 'Ancienne description';
        $tache->terminee = false;
        $tache->user_id = auth()->id();
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

    // Vérifie qu'une modification invalide est refusée et conserve les données d'origine.
    public function test_une_tache_invalide_ne_peut_être_modifiee(): void
    {
        $tache = new Tache;
        $tache->titre = 'Titre à conserver';
        $tache->description = 'test description';
        $tache->terminee = false;
        $tache->user_id = auth()->id();
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

    // Vérifie qu'une tâche peut être supprimée sans supprimer les autres.
    public function test_supprimer_une_tache_conserve_les_autres(): void
    {
        $tacheASupprimer = new Tache;
        $tacheASupprimer->titre = 'Tâche à supprimer';
        $tacheASupprimer->terminee = false;
        $tacheASupprimer->user_id = auth()->id();
        $tacheASupprimer->save();

        $tacheAConserver = new Tache;
        $tacheAConserver->titre = 'Tâche à conserver';
        $tacheAConserver->terminee = false;
        $tacheAConserver->user_id = auth()->id();
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

    // Crée et retourne une tâche de test avec son état terminé ou à faire.
    private function creerTache(string $titre, bool $terminee = false): Tache
    {
        $tache = new Tache;
        $tache->titre = $titre;
        $tache->terminee = $terminee;
        $tache->user_id = auth()->id();
        $tache->save();

        return $tache;
    }

    private function creerTachePourUtilisateur(User $utilisateur, string $titre): Tache
    {
        $tache = new Tache;
        $tache->titre = $titre;
        $tache->description = 'Description initiale';
        $tache->terminee = false;
        $tache->user_id = $utilisateur->id;
        $tache->save();

        return $tache;
    }

    // Vérifie que le filtre « à faire » exclut les tâches terminées.
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
    // Vérifie que le filtre « terminées » exclut les tâches à faire.
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

    // Vérifie qu'une recherche sans résultat renvoie une collection vide et affiche le message adapté.
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

    // Vérifie que plusieurs résultats de recherche sont affichés par identifiant décroissant.
    public function test_une_recherche_affiche_plusieurs_resultats_par_identifiant_decroissant(): void
    {
        $apprendreLaravel = $this->creerTache('Apprendre Laravel');
        $this->creerTache('Apprendre PHP');
        $reviserLaravel = $this->creerTache('Réviser Laravel');

        $reponse = $this->get(route('taches.index', [
            'q' => 'Laravel',
        ]));

        $reponse->assertOk();
        $reponse->assertViewHas('taches', function ($taches) use ($reviserLaravel, $apprendreLaravel) {
            return $taches->total() === 2
                && $taches->getCollection()->modelKeys() === [
                    $reviserLaravel->id,
                    $apprendreLaravel->id,
                ];
        });
    }

    // Vérifie que la recherche et le filtre d'état s'appliquent ensemble.
    public function test_la_recherche_et_le_filtre_detat_peuvent_etre_combines(): void
    {
        $apprendreLaravel = $this->creerTache('Apprendre Laravel');
        $this->creerTache('Réviser Laravel', true);
        $this->creerTache('Apprendre PHP');

        $reponse = $this->get(route('taches.index', [
            'etat' => 'a_faire',
            'q' => 'Laravel',
        ]));

        $reponse->assertOk();
        $reponse->assertViewHas('taches', function ($taches) use ($apprendreLaravel) {
            return $taches->total() === 1
                && $taches->getCollection()->modelKeys() === [$apprendreLaravel->id];
        });
    }

    // Vérifie le nombre de tâches sur chaque page et l'absence de chevauchement.
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

    // Vérifie que la pagination conserve les filtres et la recherche dans le lien suivant.
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

    public function test_la_creation_ignore_un_faux_propretaire(): void {
        $autreUtilisateur = User::factory()->create();

        $utilisateurConnecteId = auth()->id();
         $reponse = $this->post(route('taches.store'), [
        'titre' => 'Tester le propriétaire',
        'description' => 'Vérifier que le propriétaire est défini par le contrôleur.',
        'user_id' => $autreUtilisateur->id,
    ]);

    $reponse->assertRedirect(route('taches.index'));

    $this->assertDatabaseHas('taches', [
        'titre' => 'Tester le propriétaire',
        'user_id' => $utilisateurConnecteId,
    ]);

    $this->assertDatabaseMissing('taches', [
        'titre' => 'Tester le propriétaire',
        'user_id' => $autreUtilisateur->id,
    ]);

    $this->assertDatabaseCount('taches', 1);
    }

    public function test_la_liste_affiche_uniquement_mes_taches(): void{
        $maTache = $this->creerTache('Ma tâche personnelle');

        //créé un autre utilisateur
        $autreUtilisateur = User::factory()->create();
        
        $this->creerTachePourUtilisateur($autreUtilisateur, 'Tache de l\'autre utilisateur');

        $reponse = $this->get(route('taches.index'));
        $reponse->assertOk();
        $reponse->assertViewHas('taches', function ($taches) use ($maTache){
           

            return $taches->count() === 1
            && $taches->total() === 1
            && $taches->getCollection()->modelKeys() === [$maTache->id];
        });
        $this->assertDatabaseCount('taches', 2);
    }

    public function test_un_utilisateur_ne_peut_pas_voir_le_detail_dune_tache_dun_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $tache = $this->creerTachePourUtilisateur($autreUtilisateur, 'Tâche privée');

        $reponse = $this->get(route('taches.show', ['tache' => $tache->id]));

        $reponse->assertForbidden();
    }

    public function test_un_utilisateur_ne_peut_pas_ouvrir_le_formulaire_de_modification_dune_tache_dun_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $tache = $this->creerTachePourUtilisateur($autreUtilisateur, 'Tâche privée');

        $reponse = $this->get(route('taches.edit', ['tache' => $tache->id]));

        $reponse->assertForbidden();
    }

    public function test_un_utilisateur_ne_peut_pas_modifier_la_tache_dun_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $tache = $this->creerTachePourUtilisateur($autreUtilisateur, 'Tâche initiale');

        $reponse = $this->patch(route('taches.update', ['tache' => $tache->id]), [
            'titre' => 'Titre modifié par un autre utilisateur',
            'description' => 'Description modifiée',
            'terminee' => 1,
        ]);

        $reponse->assertForbidden();

        $this->assertDatabaseHas('taches', [
            'id' => $tache->id,
            'titre' => 'Tâche initiale',
            'description' => 'Description initiale',
            'terminee' => 0,
            'user_id' => $autreUtilisateur->id,
        ]);
        $this->assertDatabaseCount('taches', 1);
    }

    public function test_un_utilisateur_est_refuse_avant_la_validation_pour_modifier_la_tache_dun_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $tache = $this->creerTachePourUtilisateur($autreUtilisateur, 'Tâche initiale');

        $reponse = $this->patch(route('taches.update', ['tache' => $tache->id]), [
            'titre' => 'ab',
            'description' => 'Description modifiée',
            'terminee' => 1,
        ]);

        $reponse->assertForbidden();

        $this->assertDatabaseHas('taches', [
            'id' => $tache->id,
            'titre' => 'Tâche initiale',
            'description' => 'Description initiale',
            'terminee' => 0,
            'user_id' => $autreUtilisateur->id,
        ]);
        $this->assertDatabaseCount('taches', 1);
    }

    public function test_un_utilisateur_ne_peut_pas_supprimer_la_tache_dun_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $tache = $this->creerTachePourUtilisateur($autreUtilisateur, 'Tâche à conserver');

        $reponse = $this->delete(route('taches.destroy', ['tache' => $tache->id]));

        $reponse->assertForbidden();

        $this->assertDatabaseHas('taches', [
            'id' => $tache->id,
            'titre' => 'Tâche à conserver',
            'description' => 'Description initiale',
            'terminee' => 0,
            'user_id' => $autreUtilisateur->id,
        ]);
        $this->assertDatabaseCount('taches', 1);
    }

    public function test_un_proprietaire_peut_voir_le_detail_de_sa_tache(): void
    {
        $tache = $this->creerTache('Ma tâche personnelle');

        $reponse = $this->get(route('taches.show', ['tache' => $tache->id]));

        $reponse->assertOk();
    }

    public function test_les_relations_relient_la_tache_a_son_proprietaire(): void
    {
        $utilisateur = auth()->user();
        $maTache = $this->creerTache('Ma tâche personnelle');

        $autreUtilisateur = User::factory()->create();
        $this->creerTachePourUtilisateur($autreUtilisateur, 'Tâche de l’autre utilisateur');

        $this->assertSame(
            [$maTache->id],
            $utilisateur->taches()->get()->modelKeys()
        );

        $this->assertTrue(
            $maTache->user->is($utilisateur)
        );
    }
}
