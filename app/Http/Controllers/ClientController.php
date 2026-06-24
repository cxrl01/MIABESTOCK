<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients de la boutique.
     */
    public function index()
    {
        $clients = Client::where('boutique_id', auth()->user()->boutique_id)
            ->withCount('commandes')
            ->latest()
            ->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Enregistre un nouveau client.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_complet' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
        ]);

        Client::create([
            'boutique_id' => auth()->user()->boutique_id,
            'nom_complet' => $request->nom_complet,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'solde_dette' => 0,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client créé avec succès.');
    }

    /**
     * Affiche les détails d'un client, son historique de ventes et de paiements.
     */
    public function show(Client $client)
    {
        abort_if($client->boutique_id !== auth()->user()->boutique_id, 403);

        $client->loadCount(['commandes' => function ($q) {
            $q->where('type', 'vente');
        }]);

        $ventes = $client->commandes()
            ->where('type', 'vente')
            ->with(['paiements'])
            ->latest()
            ->get();

        $paiements = \App\Models\Paiement::whereHas('commande', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })
        ->with('commande')
        ->latest()
        ->get();

        $totalAchete = $ventes->sum('total_ttc');
        $totalPaye = $paiements->sum('montant');

        return view('clients.show', compact('client', 'ventes', 'paiements', 'totalAchete', 'totalPaye'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Client $client)
    {
        abort_if($client->boutique_id !== auth()->user()->boutique_id, 403);

        return view('clients.edit', compact('client'));
    }

    /**
     * Met à jour un client existant.
     */
    public function update(Request $request, Client $client)
    {
        abort_if($client->boutique_id !== auth()->user()->boutique_id, 403);

        $request->validate([
            'nom_complet' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
        ]);

        $client->update([
            'nom_complet' => $request->nom_complet,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client modifié avec succès.');
    }

    /**
     * Supprime un client.
     */
    public function destroy(Client $client)
    {
        abort_if($client->boutique_id !== auth()->user()->boutique_id, 403);

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
    }
}