<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'correo' => 'Debes iniciar sesión.',
            ]);
        }

        if (!in_array($user->rol, $roles, true)) {
            abort(403, 'No tienes permisos para esta sección.');
        }

        return $next($request);
    }
}
