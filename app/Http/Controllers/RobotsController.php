<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $sitemap = route('sitemap', [], true);
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
            '',
            'Sitemap: '.$sitemap,
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
