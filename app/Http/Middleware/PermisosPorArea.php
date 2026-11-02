<?php

namespace App\Http\Middleware;

use App\Models\Programa;
use App\Support\Permisos;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Cada área consulta todo y modifica solo lo suyo (ver App\Support\Permisos). */
class PermisosPorArea
{
    private function tocaUnProgramaCerrado(Request $request): bool
    {
        if ($request->isMethodSafe() || $request->routeIs('programas.reabrir', 'programas.destroy')) {
            return false;
        }

        $ruta = $request->route();
        $programa = $ruta?->parameter('programa') ?? $ruta?->parameter('servicio')?->programa ?? $ruta?->parameter('pax')?->programa;

        return $programa instanceof Programa && $programa->cerrado_at !== null;
    }

    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user() && Permisos::permiteRuta($request->user(), $request->method(), $request->route()?->getName()),
            403,
            'Esta acción corresponde a otra área. Puedes consultar la información, pero no modificarla.'
        );

        if ($this->tocaUnProgramaCerrado($request)) {
            return back()->with('error', 'Este programa está cerrado. Para cambiarlo, Administración debe reabrirlo.');
        }

        return $next($request);
    }
}
