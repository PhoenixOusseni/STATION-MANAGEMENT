<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminGestionnaireMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isGestionnaire())) {
            abort(403, 'Accès non autorisé. Vous devez être administrateur ou gestionnaire.');
        }

        return $next($request);
    }
}
