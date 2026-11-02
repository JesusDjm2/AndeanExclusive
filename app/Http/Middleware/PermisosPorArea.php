<?php

namespace App\Http\Middleware;

use App\Support\Permisos;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Cada área consulta todo y modifica solo lo suyo (ver App\Support\Permisos). */
class PermisosPorArea
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user() && Permisos::permiteRuta($request->user(), $request->method(), $request->route()?->getName()),
            403,
            'Esta acción corresponde a otra área. Puedes consultar la información, pero no modificarla.'
        );

        return $next($request);
    }
}
