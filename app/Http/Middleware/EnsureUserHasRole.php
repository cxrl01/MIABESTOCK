<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Usage dans les routes : ->middleware('role:gerant,gestionnaire')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        abort_if(!in_array(auth()->user()->role, $roles), 403);

        return $next($request);
    }
}