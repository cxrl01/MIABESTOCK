<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Depense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $boutiqueId = auth()->user()->boutique_id;

        // Ventes du jour (count)
        $ventesToday = Commande::where('boutique_id', $boutiqueId)
            ->where('type', 'vente')
            ->where('statut', '!=', 'annulee')
            ->whereDate('created_at', today())
            ->count();

        // CA du jour = somme des paiements reçus aujourd'hui
        $caToday = Commande::where('boutique_id', $boutiqueId)
            ->where('type', 'vente')
            ->where('statut', '!=', 'annulee')
            ->whereDate('created_at', today())
            ->with('paiements')
            ->get()
            ->sum(fn($v) => $v->paiements->sum('montant'));

        // CA du mois
        $caMois = Commande::where('boutique_id', $boutiqueId)
            ->where('type', 'vente')
            ->where('statut', '!=', 'annulee')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('paiements')
            ->get()
            ->sum(fn($v) => $v->paiements->sum('montant'));

        // Total clients
        $totalClients = Client::where('boutique_id', $boutiqueId)->count();

        // Alertes stock critique
        $alertesStock = Produit::where('boutique_id', $boutiqueId)
            ->with('categorie')
            ->get()
            ->filter(fn($p) => $p->estEnStockCritique())
            ->take(5);

        // 5 dernières ventes
        $dernieresVentes = Commande::where('boutique_id', $boutiqueId)
            ->where('type', 'vente')
            ->with(['client', 'paiements'])
            ->latest()
            ->take(5)
            ->get();

        // Dépenses du mois
        $depensesMois = Depense::where('boutique_id', $boutiqueId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('montant');

        return view('dashboard', compact(
            'ventesToday',
            'caToday',
            'caMois',
            'totalClients',
            'alertesStock',
            'dernieresVentes',
            'depensesMois'
        ));
    }
}
