<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check()
            && ! auth()->user()->activo
            && ! $request->routeIs('cuenta.inactiva')
            && ! $request->routeIs('logout')
        ) {
            return redirect()->route('cuenta.inactiva');
        }

        return $next($request);
    }
}