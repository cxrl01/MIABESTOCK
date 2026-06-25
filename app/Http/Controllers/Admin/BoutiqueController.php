<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Commande;
use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    /**
     * Affiche la liste de toutes les boutiques.
     */
    public function index()
    {
        $boutiques = Boutique::withCount('users', 'produits', 'clients')->latest()->get();

        $totalBoutiques = $boutiques->count();
        $totalBoutiquesActives = $boutiques->where('statut', 'active')->count();
        $totalUtilisateurs = User::where('role', '!=', 'super_admin')->count();
        $totalVentes = Commande::where('type', 'vente')->where('statut', '!=', 'annulee')->sum('total_ttc');

        return view('admin.boutiques.index', compact(
            'boutiques',
            'totalBoutiques',
            'totalBoutiquesActives',
            'totalUtilisateurs',
            'totalVentes'
        ));
    }

    /**
     * Affiche le détail d'une boutique.
     */
    public function show(Boutique $boutique)
    {
        $boutique->load('users');

        $nombreProduits = $boutique->produits()->count();
        $nombreClients = $boutique->clients()->count();
        $nombreVentes = Commande::where('boutique_id', $boutique->id)
            ->where('type', 'vente')
            ->where('statut', '!=', 'annulee')
            ->count();
        $chiffreAffaires = Commande::where('boutique_id', $boutique->id)
            ->where('type', 'vente')
            ->where('statut', '!=', 'annulee')
            ->sum('total_ttc');

        return view('admin.boutiques.show', compact(
            'boutique',
            'nombreProduits',
            'nombreClients',
            'nombreVentes',
            'chiffreAffaires'
        ));
    }

    /**
     * Suspend une boutique.
     */
    public function suspend(Boutique $boutique)
    {
        $boutique->update(['statut' => 'suspendue']);

        JournalActivite::log(
            'boutique_suspendue',
            "La boutique \"{$boutique->nom}\" a été suspendue.",
            $boutique->id
        );

        return redirect()->route('admin.boutiques.index')->with('success', "La boutique \"{$boutique->nom}\" a été suspendue.");
    }

    /**
     * Réactive une boutique.
     */
    public function reactivate(Boutique $boutique)
    {
        $boutique->update(['statut' => 'active']);

        JournalActivite::log(
            'boutique_reactivee',
            "La boutique \"{$boutique->nom}\" a été réactivée.",
            $boutique->id
        );

        return redirect()->route('admin.boutiques.index')->with('success', "La boutique \"{$boutique->nom}\" a été réactivée.");
    }

    /**
     * Supprime définitivement une boutique.
     */
    public function destroy(Boutique $boutique)
    {
        $nom = $boutique->nom;

        JournalActivite::log(
            'boutique_supprimee',
            "La boutique \"{$nom}\" a été supprimée définitivement."
        );

        $boutique->delete();

        return redirect()->route('admin.boutiques.index')->with('success', "La boutique \"{$nom}\" a été supprimée définitivement.");
    }

    /**
     * Affiche le journal d'activité de la plateforme.
     */
    public function journal()
    {
        $activites = JournalActivite::with('user', 'boutique')
            ->latest()
            ->paginate(30);

        return view('admin.journal', compact('activites'));
    }

    /**
     * Affiche les statistiques globales de la plateforme.
     */
    public function statistiques(Request $request)
    {
    $periode = $request->input('periode', 'mois');
    $mois = (int) $request->input('mois', now()->month);
    $annee = (int) $request->input('annee', now()->year);
    $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
    $dateFin = $request->input('date_fin', now()->format('Y-m-d'));

    $ventesQuery = Commande::where('type', 'vente')->where('statut', '!=', 'annulee');

    if ($periode === 'mois') {
        $ventesQuery->whereMonth('created_at', $mois)->whereYear('created_at', $annee);
    } elseif ($periode === 'annee') {
        $ventesQuery->whereYear('created_at', $annee);
    } else {
        $ventesQuery->whereDate('created_at', '>=', $dateDebut)->whereDate('created_at', '<=', $dateFin);
    }

    $ventes = $ventesQuery->get();

    $chiffreAffairesGlobal = $ventes->sum('total_ttc');
    $nombreVentesTotal = $ventes->count();
    $panierMoyenGlobal = $nombreVentesTotal > 0 ? $chiffreAffairesGlobal / $nombreVentesTotal : 0;

    // Évolution du CA global
    $evolution = [];
    if ($periode === 'mois') {
        $nbJours = \Carbon\Carbon::createFromDate($annee, $mois, 1)->daysInMonth;
        for ($j = 1; $j <= $nbJours; $j++) {
            $total = Commande::where('type', 'vente')->where('statut', '!=', 'annulee')
                ->whereDate('created_at', \Carbon\Carbon::createFromDate($annee, $mois, $j))
                ->sum('total_ttc');
            $evolution[] = ['label' => $j, 'total' => $total];
        }
    } elseif ($periode === 'annee') {
        for ($m = 1; $m <= 12; $m++) {
            $total = Commande::where('type', 'vente')->where('statut', '!=', 'annulee')
                ->whereMonth('created_at', $m)->whereYear('created_at', $annee)
                ->sum('total_ttc');
            $evolution[] = ['label' => \Carbon\Carbon::create()->month($m)->translatedFormat('M'), 'total' => $total];
        }
    } else {
        $debut = \Carbon\Carbon::parse($dateDebut);
        $fin = \Carbon\Carbon::parse($dateFin);
        $diffJours = $debut->diffInDays($fin);

        if ($diffJours <= 60) {
            for ($date = $debut->copy(); $date->lte($fin); $date->addDay()) {
                $total = Commande::where('type', 'vente')->where('statut', '!=', 'annulee')
                    ->whereDate('created_at', $date)
                    ->sum('total_ttc');
                $evolution[] = ['label' => $date->format('d/m'), 'total' => $total];
            }
        } else {
            for ($date = $debut->copy(); $date->lte($fin); $date->addWeek()) {
                $finSemaine = $date->copy()->addDays(6)->min($fin);
                $total = Commande::where('type', 'vente')->where('statut', '!=', 'annulee')
                    ->whereDate('created_at', '>=', $date)
                    ->whereDate('created_at', '<=', $finSemaine)
                    ->sum('total_ttc');
                $evolution[] = ['label' => $date->format('d/m'), 'total' => $total];
            }
        }
    }

    // Classement des boutiques par CA (sur la période)
    $classementBoutiques = Boutique::with([])->get()->map(function ($boutique) use ($periode, $mois, $annee, $dateDebut, $dateFin) {
        $q = Commande::where('boutique_id', $boutique->id)->where('type', 'vente')->where('statut', '!=', 'annulee');

        if ($periode === 'mois') {
            $q->whereMonth('created_at', $mois)->whereYear('created_at', $annee);
        } elseif ($periode === 'annee') {
            $q->whereYear('created_at', $annee);
        } else {
            $q->whereDate('created_at', '>=', $dateDebut)->whereDate('created_at', '<=', $dateFin);
        }

        return [
            'boutique' => $boutique,
            'ca' => $q->sum('total_ttc'),
            'ventes' => $q->count(),
        ];
    })->sortByDesc('ca')->values();

    $anneesDisponibles = range(now()->year, now()->year - 4);

    return view('admin.statistiques', compact(
        'chiffreAffairesGlobal',
        'nombreVentesTotal',
        'panierMoyenGlobal',
        'evolution',
        'classementBoutiques',
        'periode',
        'mois',
        'annee',
        'anneesDisponibles',
        'dateDebut',
        'dateFin'
    ));
    }
}