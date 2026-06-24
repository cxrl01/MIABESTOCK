<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\LigneCommande;
use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère le premier Gérant trouvé (ta boutique)
        $gerant = User::where('role', 'gerant')->first();

        if (!$gerant) {
            $this->command->error('Aucun Gérant trouvé. Crée d\'abord un compte via /register.');
            return;
        }

        $boutiqueId = $gerant->boutique_id;

        // ===== CATÉGORIES =====
        $categories = [
            'Boissons' => 'Sodas, jus, eau minérale',
            'Alimentation' => 'Produits alimentaires courants',
            'Hygiène' => 'Produits de soin et hygiène',
            'Électronique' => 'Petits appareils électroniques',
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

        // ===== PRODUITS =====
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

        // ===== CLIENTS =====
        $clients = [
            ['nom' => 'Kofi Mensah', 'tel' => '90112233', 'email' => 'kofi.mensah@example.com'],
            ['nom' => 'Akossiwa Lawson', 'tel' => '91223344', 'email' => 'akossiwa.lawson@example.com'],
            ['nom' => 'Yawo Dossou', 'tel' => '92334455', 'email' => null],
            ['nom' => 'Esther Agbeko', 'tel' => '93445566', 'email' => 'esther.agbeko@example.com'],
        ];

        $clientIds = [];
        foreach ($clients as $c) {
            $client = Client::create([
                'boutique_id' => $boutiqueId,
                'nom_complet' => $c['nom'],
                'telephone' => $c['tel'],
                'email' => $c['email'],
                'adresse' => 'Lomé, Togo',
                'solde_dette' => 0,
            ]);
            $clientIds[] = $client->id;
        }

        // ===== FOURNISSEURS =====
        $fournisseurs = [
            ['nom' => 'Grossiste Togo SARL', 'tel' => '70112233', 'email' => 'contact@grossistetogo.tg'],
            ['nom' => 'Import Express CI', 'tel' => '71223344', 'email' => 'ventes@importexpress.ci'],
            ['nom' => 'Distributeur Lomé Plus', 'tel' => '72334455', 'email' => null],
        ];

        $fournisseurIds = [];
        foreach ($fournisseurs as $f) {
            $fournisseur = Fournisseur::create([
                'boutique_id' => $boutiqueId,
                'nom' => $f['nom'],
                'telephone' => $f['tel'],
                'email' => $f['email'],
                'adresse' => 'Zone industrielle, Lomé',
                'solde_dette' => 0,
            ]);
            $fournisseurIds[] = $fournisseur->id;
        }

        // ===== LIVRAISONS (avec mise à jour du stock) =====
        $livraison1 = Commande::create([
            'boutique_id' => $boutiqueId,
            'fournisseur_id' => $fournisseurIds[0],
            'user_id' => $gerant->id,
            'numero' => 'LIV-' . now()->format('Ymd') . '-00001',
            'type' => 'livraison',
            'total_ttc' => 0,
            'statut' => 'soldee',
        ]);

        $totalLivraison1 = 0;
        $lignesLivraison1 = [
            ['produit_id' => $produitIds[0], 'qte' => 50, 'prix' => 250],
            ['produit_id' => $produitIds[1], 'qte' => 30, 'prix' => 200],
        ];

        foreach ($lignesLivraison1 as $ligne) {
            $sousTotal = $ligne['qte'] * $ligne['prix'];
            $totalLivraison1 += $sousTotal;

            LigneCommande::create([
                'commande_id' => $livraison1->id,
                'produit_id' => $ligne['produit_id'],
                'quantite' => $ligne['qte'],
                'prix_unitaire' => $ligne['prix'],
                'sous_total' => $sousTotal,
            ]);

            MouvementStock::create([
                'produit_id' => $ligne['produit_id'],
                'type' => 'entree',
                'quantite' => $ligne['qte'],
                'motif' => 'Livraison ' . $livraison1->numero,
                'date' => now()->subDays(5),
            ]);
        }

        $livraison1->update(['total_ttc' => $totalLivraison1]);

        $livraison2 = Commande::create([
            'boutique_id' => $boutiqueId,
            'fournisseur_id' => $fournisseurIds[1],
            'user_id' => $gerant->id,
            'numero' => 'LIV-' . now()->format('Ymd') . '-00002',
            'type' => 'livraison',
            'total_ttc' => 0,
            'statut' => 'soldee',
        ]);

        $totalLivraison2 = 0;
        $lignesLivraison2 = [
            ['produit_id' => $produitIds[3], 'qte' => 20, 'prix' => 3500],
            ['produit_id' => $produitIds[8], 'qte' => 10, 'prix' => 1500],
        ];

        foreach ($lignesLivraison2 as $ligne) {
            $sousTotal = $ligne['qte'] * $ligne['prix'];
            $totalLivraison2 += $sousTotal;

            LigneCommande::create([
                'commande_id' => $livraison2->id,
                'produit_id' => $ligne['produit_id'],
                'quantite' => $ligne['qte'],
                'prix_unitaire' => $ligne['prix'],
                'sous_total' => $sousTotal,
            ]);

            MouvementStock::create([
                'produit_id' => $ligne['produit_id'],
                'type' => 'entree',
                'quantite' => $ligne['qte'],
                'motif' => 'Livraison ' . $livraison2->numero,
                'date' => now()->subDays(2),
            ]);
        }

        $livraison2->update(['total_ttc' => $totalLivraison2]);

        $this->command->info('Données de démonstration insérées avec succès !');
        $this->command->info('- 4 catégories');
        $this->command->info('- 10 produits');
        $this->command->info('- 4 clients');
        $this->command->info('- 3 fournisseurs');
        $this->command->info('- 2 livraisons');
    }
}