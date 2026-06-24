<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoutiqueSettingsController extends Controller
{
    /**
     * Affiche le formulaire des paramètres de la boutique.
     */
    public function edit()
    {
        $boutique = auth()->user()->boutique;

        return view('administration.edit', compact('boutique'));
    }

    /**
     * Met à jour les paramètres de la boutique.
     */
    public function update(Request $request)
    {
        $boutique = auth()->user()->boutique;

        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:boutiques,nom,' . $boutique->id],
            'adresse' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'devise' => ['required', 'string', 'max:10'],
            'tva' => ['required', 'numeric', 'min:0', 'max:100'],
            'mentions_facture' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'devise' => $request->devise,
            'tva' => $request->tva,
            'mentions_facture' => $request->mentions_facture,
        ];

        if ($request->hasFile('logo')) {
            if ($boutique->logo) {
                Storage::disk('public')->delete($boutique->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $boutique->update($data);

        return redirect()->route('administration.edit')->with('success', 'Paramètres de la boutique mis à jour avec succès.');
    }
}