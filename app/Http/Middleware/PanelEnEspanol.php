<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** El equipo trabaja en español: el idioma del sitio público (inglés por defecto) no debe alcanzar al panel. */
class PanelEnEspanol
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('es');

        return $next($request);
    }
}
