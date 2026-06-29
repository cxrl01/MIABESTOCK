<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForcePasswordChangeController extends Controller
{
    /**
     * Affiche le formulaire de changement de mot de passe obligatoire.
     */
    public function edit()
    {
        return view('auth.force-password-change');
    }

    /**
     * Met à jour le mot de passe et lève le verrou.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
            'mot_de_passe_a_changer' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Mot de passe changé avec succès. Bienvenue !');
    }
}