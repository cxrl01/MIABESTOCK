<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->mot_de_passe_a_changer && !$request->routeIs('password.force.*') && !$request->routeIs('logout')) {
            return redirect()->route('password.force.edit');
        }

        return $next($request);
    }
}