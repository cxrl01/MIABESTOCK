<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\MouvementStock;
use App\Models\Paiement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    /**
     * Affiche l'historique des ventes.
     */
    public function index()
    {
        $ventes = Commande::where('boutique_id', auth()->user()->boutique_id)
            ->where('type', 'vente')
            ->with('client', 'paiements')
            ->latest()
            ->get();

        return view('ventes.index', compact('ventes'));
    }

    /**
     * Affiche le point de vente (création).
     */
    public function create()
    {
        abort_if(auth()->user()->estGestionnaire(), 403);

        $clients = Client::where('boutique_id', auth()->user()->boutique_id)->get();
        $produits = Produit::where('boutique_id', auth()->user()->boutique_id)
            ->where('quantite_stock', '>', 0)
            ->get();

        return view('ventes.create', compact('clients', 'produits'));
    }

    /**
     * Enregistre une nouvelle vente.
     */
    public function store(Request $request)
    {
        abort_if(auth()->user()->estGestionnaire(), 403);

        $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'produits' => ['required', 'array', 'min:1'],
            'produits.*.id' => ['required', 'exists:produits,id'],
            'produits.*.quantite' => ['required', 'integer', 'min:1'],
            'produits.*.prix_unitaire' => ['required', 'numeric', 'min:0'],
            'montant_paye' => ['required', 'numeric', 'min:0'],
        ]);

        // Vérifier la disponibilité du stock avant de continuer
        foreach ($request->produits as $ligne) {
            $produit = Produit::find($ligne['id']);
            if ($produit->quantite_stock < $ligne['quantite']) {
                return back()->withErrors([
                    'produits' => "Stock insuffisant pour le produit \"{$produit->nom}\" (disponible : {$produit->quantite_stock}).",
                ])->withInput();
            }
        }

        $vente = DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->produits as $ligne) {
                $total += $ligne['quantite'] * $ligne['prix_unitaire'];
            }

            $montantPaye = min($request->montant_paye, $total);
            $statut = $montantPaye >= $total ? 'soldee' : 'en_cours';

            $vente = Commande::create([
                'boutique_id' => auth()->user()->boutique_id,
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'numero' => 'CMD-' . now()->format('Ymd') . '-' . str_pad(Commande::count() + 1, 5, '0', STR_PAD_LEFT),
                'type' => 'vente',
                'total_ttc' => $total,
                'statut' => $statut,
            ]);

            foreach ($request->produits as $ligne) {
                $produit = Produit::find($ligne['id']);

                LigneCommande::create([
                    'commande_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'sous_total' => $ligne['quantite'] * $ligne['prix_unitaire'],
                ]);

                $produit->decrement('quantite_stock', $ligne['quantite']);

                MouvementStock::create([
                    'produit_id' => $produit->id,
                    'type' => 'sortie',
                    'quantite' => $ligne['quantite'],
                    'motif' => 'Vente ' . $vente->numero,
                    'date' => now(),
                ]);
            }

            // Enregistrer le paiement initial (total ou partiel)
            if ($montantPaye > 0) {
                Paiement::create([
                    'commande_id' => $vente->id,
                    'numero_facture' => $vente->numero . '-P01',
                    'montant' => $montantPaye,
                    'mode' => 'especes',
                ]);
            }

            // Mettre à jour la dette du client si paiement partiel
            if ($request->client_id && $montantPaye < $total) {
                $client = Client::find($request->client_id);
                $client->increment('solde_dette', $total - $montantPaye);
            }

            return $vente;
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'vente_id' => $vente->id,
                'pdf_url' => route('ventes.pdf', $vente),
                'show_url' => route('ventes.show', $vente),
            ]);
        }

        return redirect()->route('ventes.show', $vente)->with('success', 'Vente enregistrée avec succès.');
    }

    /**
     * Affiche le détail d'une vente.
     */
    public function show(Commande $vente)
    {
        abort_if($vente->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($vente->type !== 'vente', 404);

        $vente->load('client', 'lignes.produit', 'paiements');
        $soldeRestant = $vente->total_ttc - $vente->paiements->sum('montant');

        return view('ventes.show', compact('vente', 'soldeRestant'));
    }

    /**
     * Enregistre un paiement supplémentaire sur une vente existante.
     */
    public function recordPayment(Request $request, Commande $vente)
    {
        abort_if(auth()->user()->estGestionnaire(), 403);
        abort_if($vente->boutique_id !== auth()->user()->boutique_id, 403);

        $soldeRestant = $vente->total_ttc - $vente->paiements->sum('montant');

        $request->validate([
            'montant' => ['required', 'numeric', 'min:0.01', 'max:' . $soldeRestant],
        ]);

        $paiement = DB::transaction(function () use ($request, $vente, $soldeRestant) {
            $numeroFacture = $vente->numero . '-P' . str_pad($vente->paiements()->count() + 1, 2, '0', STR_PAD_LEFT);

            $p = Paiement::create([
                'commande_id' => $vente->id,
                'numero_facture' => $numeroFacture,
                'montant' => $request->montant,
                'mode' => 'especes',
            ]);

            if ($request->montant >= $soldeRestant) {
                $vente->update(['statut' => 'soldee']);
            }

            if ($vente->client_id) {
                $vente->client->decrement('solde_dette', $request->montant);
            }

            return $p;
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès.',
                'paiement' => [
                    'numero_facture' => $paiement->numero_facture,
                    'montant' => number_format($paiement->montant, 0, ',', ' ') . ' F',
                    'mode' => 'Espèces',
                    'created_at' => $paiement->created_at->format('d/m/Y H:i'),
                    'recu_url' => route('paiements.recu', $paiement),
                ],
                'statuts' => [
                    'total_ttc' => number_format($vente->total_ttc, 0, ',', ' ') . ' F',
                    'total_paye' => number_format($vente->paiements()->sum('montant'), 0, ',', ' ') . ' F',
                    'solde_restant' => $vente->total_ttc - $vente->paiements()->sum('montant'),
                    'solde_restant_formatted' => number_format($vente->total_ttc - $vente->paiements()->sum('montant'), 0, ',', ' ') . ' F',
                    'statut' => $vente->statut,
                ]
            ]);
        }

        return redirect()->route('ventes.show', $vente)->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Annule une vente et restitue le stock.
     */
    public function cancel(Commande $vente)
    {
        abort_if(!auth()->user()->estGerant(), 403);
        abort_if($vente->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($vente->type !== 'vente', 404);

        DB::transaction(function () use ($vente) {
            foreach ($vente->lignes as $ligne) {
                $ligne->produit->increment('quantite_stock', $ligne->quantite);

                MouvementStock::create([
                    'produit_id' => $ligne->produit_id,
                    'type' => 'entree',
                    'quantite' => $ligne->quantite,
                    'motif' => 'Annulation vente ' . $vente->numero,
                    'date' => now(),
                ]);
            }

            // Si le client avait une dette liée à cette vente, on l'annule aussi
            $soldeRestant = $vente->total_ttc - $vente->paiements->sum('montant');
            if ($vente->client_id && $soldeRestant > 0) {
                $vente->client->decrement('solde_dette', $soldeRestant);
            }

            $vente->update(['statut' => 'annulee']);
        });

        return redirect()->route('ventes.index')->with('success', 'Vente annulée, stock restitué.');
    }

    /**
     * Génère la facture d'une vente pour impression.
     */
    public function exportPdf(Commande $vente)
    {
        abort_if($vente->boutique_id !== auth()->user()->boutique_id, 403);
        abort_if($vente->type !== 'vente', 404);

        $vente->load(['client', 'lignes.produit', 'paiements', 'boutique']);
        $montantPaye = $vente->paiements->sum('montant');
        $reste = $vente->total_ttc - $montantPaye;

        return view('ventes.facture', compact('vente', 'montantPaye', 'reste'));
    }

    /**
     * Génère le reçu d'un paiement pour impression.
     */
    public function exportRecu(Paiement $paiement)
    {
        $vente = $paiement->commande;
        abort_if($vente->boutique_id !== auth()->user()->boutique_id, 403);

        $paiement->load(['commande.client', 'commande.boutique']);

        return view('paiements.recu', compact('paiement'));
    }
}