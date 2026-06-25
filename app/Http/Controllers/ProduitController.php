<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits de la boutique.
     */
    public function index()
    {
        $produits = Produit::where('boutique_id', auth()->user()->boutique_id)
            ->with('categorie')
            ->latest()
            ->get();

        return view('produits.index', compact('produits'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        abort_if(auth()->user()->estCommercial(), 403);

        $categories = Categorie::where('boutique_id', auth()->user()->boutique_id)->get();

        return view('produits.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau produit.
     */
    public function store(Request $request)
    {
        abort_if(auth()->user()->estCommercial(), 403);

        $request->validate([
            'categorie_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', 'unique:produits,code'],
            'nom' => ['required', 'string', 'max:255'],
            'prix_achat' => ['required', 'numeric', 'min:0'],
            'prix_vente' => ['required', 'numeric', 'min:0'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'seuil_alerte' => ['required', 'integer', 'min:0'],
        ]);

        Produit::create([
            'boutique_id' => auth()->user()->boutique_id,
            'categorie_id' => $request->categorie_id,
            'code' => $request->code,
            'nom' => $request->nom,
            'prix_achat' => $request->prix_achat,
            'prix_vente' => $request->prix_vente,
            'quantite_stock' => $request->quantite_stock,
            'seuil_alerte' => $request->seuil_alerte,
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Produit $produit)
    {
        abort_if(auth()->user()->estCommercial(), 403);
        abort_if($produit->boutique_id !== auth()->user()->boutique_id, 403);

        $categories = Categorie::where('boutique_id', auth()->user()->boutique_id)->get();

        return view('produits.edit', ['produit' => $produit, 'categories' => $categories]);
    }

    public function update(Request $request, Produit $produit)
    {
        abort_if(auth()->user()->estCommercial(), 403);
        abort_if($produit->boutique_id !== auth()->user()->boutique_id, 403);

        $request->validate([
            'categorie_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', 'unique:produits,code,' . $produit->id],
            'nom' => ['required', 'string', 'max:255'],
            'prix_achat' => ['required', 'numeric', 'min:0'],
            'prix_vente' => ['required', 'numeric', 'min:0'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'seuil_alerte' => ['required', 'integer', 'min:0'],
        ]);

        $produit->update([
            'categorie_id' => $request->categorie_id,
            'code' => $request->code,
            'nom' => $request->nom,
            'prix_achat' => $request->prix_achat,
            'prix_vente' => $request->prix_vente,
            'quantite_stock' => $request->quantite_stock,
            'seuil_alerte' => $request->seuil_alerte,
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Produit $produit)
    {
        abort_if(auth()->user()->estCommercial(), 403);
        abort_if($produit->boutique_id !== auth()->user()->boutique_id, 403);

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }
}