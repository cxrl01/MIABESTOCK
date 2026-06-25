<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Depense;
use App\Models\Fournisseur;
use App\Models\LigneCommande;
use App\Models\MouvementStock;
use App\Models\Paiement;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $gerant = User::where('role', 'gerant')->first();

        if (!$gerant) {
            $this->command->error("Aucun Gérant trouvé. Crée d'abord un compte via /register.");
            return;
        }

        $boutiqueId = $gerant->boutique_id;

        /*
        |--------------------------------------------------------------------------
        | CATÉGORIES
        |--------------------------------------------------------------------------
        */
        $categories = [
            'Boissons' => 'Sodas, jus, eau minérale',
            'Alimentation' => 'Produits alimentaires',
            'Hygiène' => 'Produits d’hygiène',
            'Électronique' => 'Appareils électroniques',
        ];

        $categorieIds = [];

        foreach ($categories as $nom => $description) {
            $cat = Categorie::create([
                'boutique_id' => $boutiqueId,
                'nom' => $nom,
                'description' => $description,
            ]);

            $categorieIds[$nom] = $cat->id;
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUITS
        |--------------------------------------------------------------------------
        */
        $produits = [
            ['code' => 'BOI-001', 'nom' => 'Coca-Cola 50cl', 'cat' => 'Boissons', 'achat' => 250, 'vente' => 400, 'stock' => 120, 'seuil' => 20],
            ['code' => 'BOI-002', 'nom' => 'Eau minérale 1.5L', 'cat' => 'Boissons', 'achat' => 200, 'vente' => 350, 'stock' => 80, 'seuil' => 15],
            ['code' => 'BOI-003', 'nom' => 'Jus Tampico 1L', 'cat' => 'Boissons', 'achat' => 500, 'vente' => 750, 'stock' => 5, 'seuil' => 10],

            ['code' => 'ALI-001', 'nom' => 'Riz parfumé 5kg', 'cat' => 'Alimentation', 'achat' => 3500, 'vente' => 4500, 'stock' => 40, 'seuil' => 10],
            ['code' => 'ALI-002', 'nom' => 'Huile végétale 1L', 'cat' => 'Alimentation', 'achat' => 1200, 'vente' => 1600, 'stock' => 8, 'seuil' => 10],
            ['code' => 'ALI-003', 'nom' => 'Lait en poudre Bambino', 'cat' => 'Alimentation', 'achat' => 2800, 'vente' => 3500, 'stock' => 25, 'seuil' => 5],

            ['code' => 'HYG-001', 'nom' => 'Savon Lux', 'cat' => 'Hygiène', 'achat' => 300, 'vente' => 500, 'stock' => 60, 'seuil' => 15],
            ['code' => 'HYG-002', 'nom' => 'Dentifrice Signal', 'cat' => 'Hygiène', 'achat' => 600, 'vente' => 900, 'stock' => 3, 'seuil' => 10],

            ['code' => 'ELE-001', 'nom' => 'Lampe torche LED', 'cat' => 'Électronique', 'achat' => 1500, 'vente' => 2500, 'stock' => 18, 'seuil' => 5],
            ['code' => 'ELE-002', 'nom' => 'Chargeur téléphone universel', 'cat' => 'Électronique', 'achat' => 1000, 'vente' => 1800, 'stock' => 22, 'seuil' => 8],

            ['code' => 'ELE-003', 'nom' => 'Samsung Galaxy S24 Ultra', 'cat' => 'Électronique', 'achat' => 850000, 'vente' => 1150000, 'stock' => 5, 'seuil' => 1],
            ['code' => 'ELE-004', 'nom' => 'iPhone 15 Pro Max', 'cat' => 'Électronique', 'achat' => 900000, 'vente' => 1250000, 'stock' => 4, 'seuil' => 1],
            ['code' => 'ELE-005', 'nom' => 'PlayStation 5', 'cat' => 'Électronique', 'achat' => 450000, 'vente' => 650000, 'stock' => 3, 'seuil' => 1],
        ];

        $produitIds = [];

        foreach ($produits as $p) {
            $produit = Produit::create([
                'boutique_id' => $boutiqueId,
                'categorie_id' => $categorieIds[$p['cat']],
                'code' => $p['code'],
                'nom' => $p['nom'],
                'prix_achat' => $p['achat'],
                'prix_vente' => $p['vente'],
                'quantite_stock' => $p['stock'],
                'seuil_alerte' => $p['seuil'],
            ]);

            $produitIds[] = $produit->id;
        }

        /*
        |--------------------------------------------------------------------------
        | CLIENTS
        |--------------------------------------------------------------------------
        */
        $clientIds = [];

        foreach ([
            ['nom' => 'Kofi Mensah', 'tel' => '90112233'],
            ['nom' => 'Akossiwa Lawson', 'tel' => '91223344'],
            ['nom' => 'Yawo Dossou', 'tel' => '92334455'],
            ['nom' => 'Esther Agbeko', 'tel' => '93445566'],
        ] as $c) {

            $client = Client::create([
                'boutique_id' => $boutiqueId,
                'nom_complet' => $c['nom'],
                'telephone' => $c['tel'],
                'adresse' => 'Lomé, Togo',
                'solde_dette' => 0,
            ]);

            $clientIds[] = $client->id;
        }

        /*
        |--------------------------------------------------------------------------
        | FOURNISSEURS
        |--------------------------------------------------------------------------
        */
        $fournisseurIds = [];

        foreach ([
            'Grossiste Togo SARL',
            'Import Express CI',
            'Distributeur Lomé Plus',
        ] as $nom) {

            $fournisseur = Fournisseur::create([
                'boutique_id' => $boutiqueId,
                'nom' => $nom,
                'telephone' => '70000000',
                'adresse' => 'Zone industrielle',
                'solde_dette' => 0,
            ]);

            $fournisseurIds[] = $fournisseur->id;
        }

        /*
        |--------------------------------------------------------------------------
        | LIVRAISON
        |--------------------------------------------------------------------------
        */
        $livraison = Commande::create([
            'boutique_id' => $boutiqueId,
            'fournisseur_id' => $fournisseurIds[0],
            'user_id' => $gerant->id,
            'numero' => 'LIV-' . now()->format('Ymd') . '-001',
            'type' => 'livraison',
            'total_ttc' => 18500,
            'statut' => 'soldee',
        ]);

        LigneCommande::create([
            'commande_id' => $livraison->id,
            'produit_id' => $produitIds[0],
            'quantite' => 50,
            'prix_unitaire' => 250,
            'sous_total' => 12500,
        ]);

        LigneCommande::create([
            'commande_id' => $livraison->id,
            'produit_id' => $produitIds[1],
            'quantite' => 30,
            'prix_unitaire' => 200,
            'sous_total' => 6000,
        ]);

        /*
        |--------------------------------------------------------------------------
        | VENTE 1 SOLDÉE
        |--------------------------------------------------------------------------
        */
        $vente1 = Commande::create([
            'boutique_id' => $boutiqueId,
            'client_id' => $clientIds[0],
            'user_id' => $gerant->id,
            'numero' => 'VEN-' . now()->format('Ymd') . '-001',
            'type' => 'vente',
            'total_ttc' => 800,
            'statut' => 'soldee',
        ]);

        LigneCommande::create([
            'commande_id' => $vente1->id,
            'produit_id' => $produitIds[0],
            'quantite' => 2,
            'prix_unitaire' => 400,
            'sous_total' => 800,
        ]);

        Paiement::create([
            'commande_id' => $vente1->id,
            'numero_facture' => 'FAC-00001',
            'montant' => 800,
            'mode' => 'especes',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VENTE 2 PARTIELLE
        |--------------------------------------------------------------------------
        */
        $vente2 = Commande::create([
            'boutique_id' => $boutiqueId,
            'client_id' => $clientIds[1],
            'user_id' => $gerant->id,
            'numero' => 'VEN-' . now()->format('Ymd') . '-002',
            'type' => 'vente',
            'total_ttc' => 4500,
            'statut' => 'en_cours',
        ]);

        LigneCommande::create([
            'commande_id' => $vente2->id,
            'produit_id' => $produitIds[3],
            'quantite' => 1,
            'prix_unitaire' => 4500,
            'sous_total' => 4500,
        ]);

        Paiement::create([
            'commande_id' => $vente2->id,
            'numero_facture' => 'FAC-00002',
            'montant' => 2000,
            'mode' => 'mobile_money',
        ]);

        Client::find($clientIds[1])->update([
            'solde_dette' => 2500,
        ]);

        /*
        |--------------------------------------------------------------------------
        | MOUVEMENTS DE STOCK
        |--------------------------------------------------------------------------
        */
        MouvementStock::create([
            'produit_id' => $produitIds[0],
            'type' => 'sortie',
            'quantite' => 2,
            'motif' => 'Vente',
            'date' => now(),
        ]);

        MouvementStock::create([
            'produit_id' => $produitIds[3],
            'type' => 'sortie',
            'quantite' => 1,
            'motif' => 'Vente',
            'date' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | DÉPENSES
        |--------------------------------------------------------------------------
        */
        Depense::create([
            'boutique_id' => $boutiqueId,
            'user_id' => $gerant->id,
            'libelle' => 'Paiement loyer',
            'montant' => 150000,
            'categorie' => 'Loyer',
            'date' => now()->subDays(15),
        ]);

        Depense::create([
            'boutique_id' => $boutiqueId,
            'user_id' => $gerant->id,
            'libelle' => 'Facture électricité',
            'montant' => 35000,
            'categorie' => 'Charges',
            'date' => now()->subDays(10),
        ]);

        Depense::create([
            'boutique_id' => $boutiqueId,
            'user_id' => $gerant->id,
            'libelle' => 'Connexion Internet',
            'montant' => 25000,
            'categorie' => 'Internet',
            'date' => now()->subDays(5),
        ]);

        Depense::create([
            'boutique_id' => $boutiqueId,
            'user_id' => $gerant->id,
            'libelle' => 'Achat fournitures',
            'montant' => 18000,
            'categorie' => 'Bureau',
            'date' => now()->subDays(2),
        ]);

        $this->command->info('Données de démonstration insérées avec succès !');
    }
}