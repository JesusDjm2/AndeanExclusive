<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Cabeceras de seguridad de todas las respuestas, y la orden a los buscadores de no indexar lo privado. */
class CabecerasDeSeguridad
{
    public function handle(Request $request, Closure $next): Response
    {
        $respuesta = $next($request);
        $cabeceras = $respuesta->headers;

        $cabeceras->set('X-Content-Type-Options', 'nosniff');
        $cabeceras->set('X-Frame-Options', 'SAMEORIGIN');
        $cabeceras->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        // Sin scripts ni estilos: esas fuentes vienen de varios servicios y bloquearlas rompería páginas. Esto limita lo más riesgoso.
        $cabeceras->set('Content-Security-Policy', "base-uri 'self'; object-src 'none'; frame-ancestors 'self'");

        // Los enlaces privados llevan una clave en la dirección: no deben filtrarse a otros sitios.
        $cabeceras->set('Referrer-Policy', $request->routeIs('itinerario*') ? 'no-referrer' : 'strict-origin-when-cross-origin');

        if ($request->isSecure()) {
            $cabeceras->set('Strict-Transport-Security', 'max-age=31536000');
        }

        if ($request->routeIs('itinerario*') || ! config('seo.indexar')) {
            $cabeceras->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $respuesta;
    }
}
