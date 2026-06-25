<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\LigneCommande;
use App\Models\MouvementStock;
use App\Models\Paiement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivraisonController extends Controller
{
    /**
     * Affiche la liste des livraisons de la boutique.
     */
    public function index()
    {
        $livraisons = Commande::where('boutique_id', auth()->user()->boutique_id)
            ->where('type', 'livraison')
            ->with('fournisseur', 'paiements')
            ->latest()
            ->get();

        return view('livraisons.index', compact('livraisons'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::where('boutique_id', auth()->user()->boutique_id)->get();
        $produits = Produit::where('boutique_id', auth()->user()->boutique_id)->get();

        return view('livraisons.create', compact('fournisseurs', 'produits'));
    }

    /**
     * Enregistre une nouvelle livraison.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fournisseur_id' => ['required', 'exists:fournisseurs,id'],
            'produits' => ['required', 'array', 'min:1'],
            'produits.*.id' => ['required', 'exists:produits,id'],
            'produits.*.quantite' => ['required', 'integer', 'min:1'],
            'produits.*.prix_unitaire' => ['required', 'numeric', 'min:0'],
            'montant_paye' => ['required', 'numeric', 'min:0'],
        ]);

        $livraison = DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->produits as $ligne) {
                $total += $ligne['quantite'] * $ligne['prix_unitaire'];
            }

            $montantPaye = min($request->montant_paye, $total);
            $statut = $montantPaye >= $total ? 'soldee' : 'en_cours';

            $livraison = Commande::create([
                'boutique_id' => auth()->user()->boutique_id,
                'fournisseur_id' => $request->fournisseur_id,
                'user_id' => auth()->id(),
                'numero' => 'LIV-' . now()->format('Ymd') . '-' . str_pad(Commande::count() + 1, 5, '0', STR_PAD_LEFT),
                'type' => 'livraison',
                'total_ttc' => $total,
                'statut' => $statut,
            ]);

            foreach ($request->produits as $ligne) {
                $produit = Produit::find($ligne['id']);

                LigneCommande::create([
                    'commande_id' => $livraison->id,
                    'produit_id' => $produit->id,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'sous_total' => $ligne['quantite'] * $ligne['prix_unitaire'],
                ]);

                $produit->increment('quantite_stock', $ligne['quantite']);

                MouvementStock::create([
                    'produit_id' => $produit->id,
                    'type' => 'entree',
                    'quantite' => $ligne['quantite'],
                    'motif' => 'Livraison ' . $livraison->numero,
                    'date' => now(),
                ]);
            }

            // Enregistrer le paiement initial (total ou partiel)
            if ($montantPaye > 0) {
                Paiement::create([
                    'commande_id' => $livraison->id,
                    'numero_facture' => $livraison->numero . '-P01',
                    'montant' => $montantPaye,
                    'mode' => 'especes',
                ]);
            }

            // Mettre à jour la dette du fournisseur si paiement partiel
            if ($montantPaye < $total) {
                $fournisseur = Fournisseur::find($request->fournisseur_id);
                $fournisseur->increment('solde_dette', $total - $montantPaye);
            }

            return $livraison;
        });

        return redirect()->route('livraisons.show', $livraison)->with('success', 'Livraison enregistrée avec succès.');
    }

    /**
     * Affiche le détail d'une livraison.
     */
    public function show(Commande $livraison)
    {
        abort_if($livraison->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($livraison->type !== 'livraison', 404);

        $livraison->load('fournisseur', 'lignes.produit', 'paiements');
        $soldeRestant = $livraison->total_ttc - $livraison->paiements->sum('montant');

        return view('livraisons.show', compact('livraison', 'soldeRestant'));
    }

    /**
     * Enregistre un paiement complémentaire envers le fournisseur.
     */
    public function recordPayment(Request $request, Commande $livraison)
    {
        abort_if($livraison->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($livraison->type !== 'livraison', 404);

        $soldeRestant = $livraison->total_ttc - $livraison->paiements->sum('montant');

        $request->validate([
            'montant' => ['required', 'numeric', 'min:0.01', 'max:' . $soldeRestant],
        ]);

        DB::transaction(function () use ($request, $livraison, $soldeRestant) {
            $numeroFacture = $livraison->numero . '-P' . str_pad($livraison->paiements()->count() + 1, 2, '0', STR_PAD_LEFT);

            Paiement::create([
                'commande_id' => $livraison->id,
                'numero_facture' => $numeroFacture,
                'montant' => $request->montant,
                'mode' => 'especes',
            ]);

            if ($request->montant >= $soldeRestant) {
                $livraison->update(['statut' => 'soldee']);
            }

            $livraison->fournisseur->decrement('solde_dette', $request->montant);
        });

        return redirect()->route('livraisons.show', $livraison)->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Supprime une livraison (et retire le stock ajouté + ajuste la dette).
     */
    public function destroy(Commande $livraison)
    {
        abort_if($livraison->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($livraison->type !== 'livraison', 404);

        DB::transaction(function () use ($livraison) {
            foreach ($livraison->lignes as $ligne) {
                $ligne->produit->decrement('quantite_stock', $ligne->quantite);

                MouvementStock::create([
                    'produit_id' => $ligne->produit_id,
                    'type' => 'sortie',
                    'quantite' => $ligne->quantite,
                    'motif' => 'Annulation livraison ' . $livraison->numero,
                    'date' => now(),
                ]);
            }

            // Si une dette fournisseur était liée à cette livraison, on l'annule
            $soldeRestant = $livraison->total_ttc - $livraison->paiements->sum('montant');
            if ($soldeRestant > 0) {
                $livraison->fournisseur->decrement('solde_dette', $soldeRestant);
            }

            $livraison->lignes()->delete();
            $livraison->delete();
        });

        return redirect()->route('livraisons.index')->with('success', 'Livraison annulée, stock ajusté.');
    }
}