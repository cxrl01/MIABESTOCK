<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Commande;
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

        // Statistiques globales
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

        return redirect()->route('admin.boutiques.index')->with('success', "La boutique \"{$boutique->nom}\" a été suspendue.");
    }

    /**
     * Réactive une boutique.
     */
    public function reactivate(Boutique $boutique)
    {
        $boutique->update(['statut' => 'active']);

        return redirect()->route('admin.boutiques.index')->with('success', "La boutique \"{$boutique->nom}\" a été réactivée.");
    }

    /**
     * Supprime définitivement une boutique.
     */
    public function destroy(Boutique $boutique)
    {
        $nom = $boutique->nom;
        $boutique->delete();

        return redirect()->route('admin.boutiques.index')->with('success', "La boutique \"{$nom}\" a été supprimée définitivement.");
    }
}