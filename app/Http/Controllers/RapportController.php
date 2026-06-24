<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Depense;
use App\Models\LigneCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = auth()->user()->boutique_id;

        $periode = $request->input('periode', 'mois'); // mois | annee | personnalise
        $mois = (int) $request->input('mois', now()->month);
        $annee = (int) $request->input('annee', now()->year);
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->format('Y-m-d'));

        // 1. Requête principale des ventes (avec eager loading des clients)
        $ventesQuery = Commande::with('client')
            ->where('boutique_id', $boutiqueId)
            ->where('type', 'vente')
            ->where('statut', '!=', 'annulee');

        if ($periode === 'mois') {
            $ventesQuery->whereMonth('created_at', $mois)->whereYear('created_at', $annee);
        } elseif ($periode === 'annee') {
            $ventesQuery->whereYear('created_at', $annee);
        } else {
            $ventesQuery->whereDate('created_at', '>=', $dateDebut)
                        ->whereDate('created_at', '<=', $dateFin);
        }

        $ventes = $ventesQuery->get();

        // 2. Calcul des indicateurs clés (KPI)
        $chiffreAffaires = $ventes->sum('total_ttc');
        $nombreVentes = $ventes->count();
        $panierMoyen = $nombreVentes > 0 ? $chiffreAffaires / $nombreVentes : 0;

        // Calcul du coût d'achat global pour la marge
        $coutAchatTotal = LigneCommande::whereIn('commande_id', $ventes->pluck('id'))
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->sum(DB::raw('ligne_commandes.quantite * produits.prix_achat'));

        $marge = $chiffreAffaires - $coutAchatTotal;
        $margePourcent = $chiffreAffaires > 0 ? ($marge / $chiffreAffaires) * 100 : 0;

        // 3. Calcul des dépenses
        $depensesQuery = Depense::where('boutique_id', $boutiqueId);
        if ($periode === 'mois') {
            $depensesQuery->whereMonth('date', $mois)->whereYear('date', $annee);
        } elseif ($periode === 'annee') {
            $depensesQuery->whereYear('date', $annee);
        } else {
            $depensesQuery->whereDate('date', '>=', $dateDebut)->whereDate('date', '<=', $dateFin);
        }
        $totalDepenses = $depensesQuery->sum('montant');

        // 4. Construction de l'évolution du CA
        $evolution = [];
        if ($periode === 'mois') {
            $nbJours = Carbon::createFromDate($annee, $mois, 1)->daysInMonth;
            for ($j = 1; $j <= $nbJours; $j++) {
                $targetDate = Carbon::createFromDate($annee, $mois, $j)->format('Y-m-d');
                $total = $ventes->filter(fn($v) => Carbon::parse($v->created_at)->format('Y-m-d') === $targetDate)->sum('total_ttc');
                $evolution[] = ['label' => $j, 'total' => $total];
            }
        } elseif ($periode === 'annee') {
            for ($m = 1; $m <= 12; $m++) {
                $total = $ventes->filter(fn($v) => Carbon::parse($v->created_at)->month === $m)->sum('total_ttc');
                $evolution[] = ['label' => Carbon::create()->month($m)->translatedFormat('M'), 'total' => $total];
            }
        } else {
            $debut = Carbon::parse($dateDebut);
            $fin = Carbon::parse($dateFin);
            $diffJours = $debut->diffInDays($fin);

            if ($diffJours <= 60) {
                for ($date = $debut->copy(); $date->lte($fin); $date->addDay()) {
                    $formattedDate = $date->format('Y-m-d');
                    $total = $ventes->filter(fn($v) => Carbon::parse($v->created_at)->format('Y-m-d') === $formattedDate)->sum('total_ttc');
                    $evolution[] = ['label' => $date->format('d/m'), 'total' => $total];
                }
            } else {
                for ($date = $debut->copy(); $date->lte($fin); $date->addWeek()) {
                    $finSemaine = $date->copy()->addDays(6)->min($fin);
                    $total = $ventes->filter(function($v) use ($date, $finSemaine) {
                        $itemDate = Carbon::parse($v->created_at);
                        return $itemDate->gte($date->startOfDay()) && $itemDate->lte($finSemaine->endOfDay());
                    })->sum('total_ttc');
                    $evolution[] = ['label' => $date->format('d/m'), 'total' => $total];
                }
            }
        }

        // 5. Top 5 Produits
        $topProduits = LigneCommande::whereIn('commande_id', $ventes->pluck('id'))
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->select('produits.nom', DB::raw('SUM(ligne_commandes.quantite) as total_quantite'), DB::raw('SUM(ligne_commandes.sous_total) as total_ventes'))
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('total_quantite')
            ->limit(5)
            ->get();

        // 6. Top 5 Clients
        $topClients = $ventes->whereNotNull('client_id')
            ->groupBy('client_id')
            ->map(function ($groupe) {
                return [
                    'client' => $groupe->first()->client,
                    'total' => $groupe->sum('total_ttc'),
                    'nombre' => $groupe->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        $anneesDisponibles = range(now()->year, now()->year - 4);

        return view('rapports.index', compact(
            'chiffreAffaires', 'nombreVentes', 'panierMoyen', 'marge', 'margePourcent',
            'totalDepenses', 'evolution', 'topProduits', 'topClients',
            'periode', 'mois', 'annee', 'anneesDisponibles', 'dateDebut', 'dateFin'
        ));
    }
}