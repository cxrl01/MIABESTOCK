<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    public function index()
    {
        $boutiqueId = auth()->user()->boutique_id;
        $depenses = Depense::where('boutique_id', $boutiqueId)
            ->with('user')
            ->latest('date')
            ->get();

        $totalMois = Depense::where('boutique_id', $boutiqueId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('montant');

        $totalAnnee = Depense::where('boutique_id', $boutiqueId)
            ->whereYear('date', now()->year)
            ->sum('montant');

        return view('depenses.index', compact('depenses', 'totalMois', 'totalAnnee'));
    }

    public function create()
    {
        return view('depenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle'   => ['required', 'string', 'max:255'],
            'montant'   => ['required', 'numeric', 'min:0'],
            'categorie' => ['nullable', 'string', 'max:100'],
            'date'      => ['required', 'date'],
        ]);

        Depense::create([
            'boutique_id' => auth()->user()->boutique_id,
            'user_id'     => auth()->id(),
            'libelle'     => $request->libelle,
            'montant'     => $request->montant,
            'categorie'   => $request->categorie,
            'date'        => $request->date,
        ]);

        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée avec succès.');
    }

    public function destroy(Depense $depense)
    {
        abort_if($depense->boutique_id !== auth()->user()->boutique_id, 403);
        $depense->delete();
        return redirect()->route('depenses.index')->with('success', 'Dépense supprimée.');
    }
}
