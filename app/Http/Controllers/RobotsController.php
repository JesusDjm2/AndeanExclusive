<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $sitemap = route('sitemap', [], true);
        if (! config('seo.indexar')) {
            return response("User-agent: *\nDisallow: /\n", 200)->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /password/',
            'Disallow: /home',
            'Disallow: /users',
            'Disallow: /tours-en-ingles',
            'Disallow: /tours-espanol',
            'Disallow: /categorias-en-ingles',
            'Disallow: /categorias-espanol',
            'Disallow: /blogs-en-ingles',
            'Disallow: /esblogs',
            'Disallow: /entags',
            'Disallow: /estags',
            'Disallow: /imagenes',
            'Disallow: /proveedors',
            'Disallow: /categorias-Proveedores',
            'Disallow: /agentes',
            'Disallow: /paxs',
            'Disallow: /hoteles',
            'Disallow: /habitaciones',
            'Disallow: /itinerario/',
            'Disallow: /verificacion',
            'Disallow: /notificaciones',
            'Disallow: /movimientos',
            'Disallow: /ayuda',
            'Disallow: /reportes',
            'Disallow: /consultas',
            'Disallow: /programas',
            'Disallow: /clientes',
            'Disallow: /reservas',
            'Disallow: /operaciones',
            'Disallow: /cuentas-por-pagar',
            'Disallow: /tarifario',
            'Disallow: /servicios',
            'Disallow: /copias-de-seguridad',
            '',
            'Sitemap: '.$sitemap,
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
