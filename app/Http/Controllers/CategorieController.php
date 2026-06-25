<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::where('boutique_id', auth()->user()->boutique_id)
            ->withCount('produits')
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(auth()->user()->estCommercial(), 403);

        return view('categories.create');
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->estCommercial(), 403);

        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Categorie::create([
            'boutique_id' => auth()->user()->boutique_id,
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Categorie $category)
    {
        abort_if(auth()->user()->estCommercial(), 403);
        abort_if($category->boutique_id !== auth()->user()->boutique_id, 403);

        return view('categories.edit', ['categorie' => $category]);
    }

    public function update(Request $request, Categorie $category)
    {
        abort_if(auth()->user()->estCommercial(), 403);
        abort_if($category->boutique_id !== auth()->user()->boutique_id, 403);

        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(Categorie $category)
    {
        abort_if(auth()->user()->estCommercial(), 403);
        abort_if($category->boutique_id !== auth()->user()->boutique_id, 403);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}