<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsConsultant
{
    /**
     * Libera a rota apenas para usuários autenticados com role = consultant.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isConsultant()) {
            abort(403, 'Acesso restrito a consultores.');
        }

        return $next($request);
    }
}
