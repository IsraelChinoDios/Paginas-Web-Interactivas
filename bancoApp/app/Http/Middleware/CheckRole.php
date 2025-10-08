<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Acepta uno o varios roles: role:administrador,empleado
        if (! in_array($user->rol, $roles, true)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
