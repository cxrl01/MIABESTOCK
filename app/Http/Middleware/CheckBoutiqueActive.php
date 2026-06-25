<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBoutiqueActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            if ($user->estSuperAdmin()) {
                // Le Super Admin n'a pas le droit d'accéder aux pages "boutique"
                if (!$request->is('admin/*') && !$request->is('profile') && !$request->is('logout')) {
                    return redirect()->route('admin.boutiques.index');
                }
            } else {
                // Compte désactivé par le Gérant
                if (!$user->est_actif) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Votre compte a été désactivé. Contactez votre Gérant.',
                    ]);
                }

                // Boutique suspendue ou supprimée
                if ($user->boutique?->statut !== 'active') {
                    $statutBoutique = $user->boutique?->statut;

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $message = match ($statutBoutique) {
                        'suspendue' => 'Cette boutique a été suspendue par l\'administration. Veuillez contacter le support.',
                        'supprimee' => 'Cette boutique n\'existe plus.',
                        default => 'Impossible d\'accéder à la plateforme pour le moment.',
                    };

                    return redirect()->route('login')->withErrors([
                        'email' => $message,
                    ]);
                }
            }
        }

        return $next($request);
    }
}