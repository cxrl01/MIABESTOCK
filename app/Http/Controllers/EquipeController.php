<?php

namespace App\Http\Controllers;

use App\Mail\NouveauCompteEquipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EquipeController extends Controller
{
    public function index()
    {
        $membres = User::where('boutique_id', auth()->user()->boutique_id)
            ->where('id', '!=', auth()->id()) // Exclure soi-même
            ->get();

        return view('equipe.index', compact('membres'));
    }

    public function create()
    {
        return view('equipe.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:gestionnaire,commercial'],
        ]);

        // Génère un mot de passe temporaire aléatoire
        $motDePasseTemporaire = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($motDePasseTemporaire),
            'role' => $request->role,
            'boutique_id' => auth()->user()->boutique_id,
            'est_actif' => true,
            'mot_de_passe_a_changer' => true,
        ]);

        Mail::to($user->email)->send(new NouveauCompteEquipe($user, $motDePasseTemporaire));

        return redirect()->route('equipe.index')->with('success', 'Collaborateur ajouté avec succès. Ses identifiants lui ont été envoyés par email.');
    }

    public function edit(User $equipe)
    {
        abort_if($equipe->boutique_id !== auth()->user()->boutique_id, 403);

        return view('equipe.edit', ['membre' => $equipe]);
    }

    public function update(Request $request, User $equipe)
    {
        abort_if($equipe->boutique_id !== auth()->user()->boutique_id, 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $equipe->id],
            'role' => ['required', 'in:gestionnaire,commercial'],
        ]);

        $equipe->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('equipe.index')->with('success', 'Collaborateur modifié avec succès.');
    }

    public function destroy(User $equipe)
    {
        abort_if($equipe->boutique_id !== auth()->user()->boutique_id, 403);

        $equipe->delete();

        return redirect()->route('equipe.index')->with('success', 'Collaborateur supprimé avec succès.');
    }

    public function toggleStatus(User $equipe)
    {
        abort_if($equipe->boutique_id !== auth()->user()->boutique_id, 403);

        $equipe->update([
            'est_actif' => !$equipe->est_actif,
        ]);

        $status = $equipe->est_actif ? 'activé' : 'désactivé';
        return redirect()->route('equipe.index')->with('success', "Le compte du collaborateur a été {$status} avec succès.");
    }
}