<?php
namespace App\Http\Controllers;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index() {
        $fournisseurs = Fournisseur::where('boutique_id', auth()->user()->boutique_id)->latest()->get();
        return view('fournisseurs.index', compact('fournisseurs'));
    }
    public function create() {
        return view('fournisseurs.create');
    }
    public function store(Request $request) {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
        ]);
        Fournisseur::create([
            'boutique_id' => auth()->user()->boutique_id,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'solde_dette' => 0,
        ]);
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté avec succès.');
    }
    public function edit(Fournisseur $fournisseur) {
        abort_if($fournisseur->boutique_id !== auth()->user()->boutique_id, 403);
        return view('fournisseurs.edit', compact('fournisseur'));
    }
    public function update(Request $request, Fournisseur $fournisseur) {
        abort_if($fournisseur->boutique_id !== auth()->user()->boutique_id, 403);
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
        ]);
        $fournisseur->update($request->only(['nom', 'telephone', 'email', 'adresse']));
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur modifié avec succès.');
    }
    public function destroy(Fournisseur $fournisseur) {
        abort_if($fournisseur->boutique_id !== auth()->user()->boutique_id, 403);
        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
