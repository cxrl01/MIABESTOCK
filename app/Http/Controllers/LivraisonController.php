<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\LigneCommande;
use App\Models\MouvementStock;
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
            ->with('fournisseur')
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
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->produits as $ligne) {
                $total += $ligne['quantite'] * $ligne['prix_unitaire'];
            }

            $livraison = Commande::create([
                'boutique_id' => auth()->user()->boutique_id,
                'fournisseur_id' => $request->fournisseur_id,
                'user_id' => auth()->id(),
                'numero' => 'LIV-' . now()->format('Ymd') . '-' . str_pad(Commande::count() + 1, 5, '0', STR_PAD_LEFT),
                'type' => 'livraison',
                'total_ttc' => $total,
                'statut' => 'soldee',
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

                // Augmente le stock du produit
                $produit->increment('quantite_stock', $ligne['quantite']);

                // Trace le mouvement de stock
                MouvementStock::create([
                    'produit_id' => $produit->id,
                    'type' => 'entree',
                    'quantite' => $ligne['quantite'],
                    'motif' => 'Livraison ' . $livraison->numero,
                    'date' => now(),
                ]);
            }
        });

        return redirect()->route('livraisons.index')->with('success', 'Livraison enregistrée avec succès.');
    }

    /**
     * Affiche le détail d'une livraison.
     */
    public function show(Commande $livraison)
    {
        abort_if($livraison->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($livraison->type !== 'livraison', 404);

        $livraison->load('fournisseur', 'lignes.produit');

        return view('livraisons.show', compact('livraison'));
    }

    /**
     * Supprime une livraison (et retire le stock ajouté).
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

            $livraison->lignes()->delete();
            $livraison->delete();
        });

        return redirect()->route('livraisons.index')->with('success', 'Livraison annulée, stock ajusté.');
    }
}