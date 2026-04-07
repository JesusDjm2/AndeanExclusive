<?php

namespace App\Http\Controllers;

use App\Models\Enblog;
use App\Models\Entag;
use App\Models\Esblog;
use App\Models\EsCategoria;
use App\Models\Estag;
use App\Models\Estour;
use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        $add = function (string $loc, ?string $lastmod = null, string $changefreq = 'weekly', string $priority = '0.8') use (&$urls) {
            $urls[] = [
                'loc' => $loc,
                'lastmod' => $lastmod,
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        };

        $staticEn = ['index', 'around', 'experiences', 'adventures', 'blog-en', 'about', 'testimonials', 'terms', 'faqs'];
        foreach ($staticEn as $name) {
            if (Route::has($name)) {
                $add(route($name, [], true), null, 'weekly', $name === 'index' ? '1.0' : '0.9');
            }
        }

        $staticEs = ['inicio', 'experiencias', 'alrededor-de-peru', 'caminatas', 'blog-es', 'nosotros', 'testimonios', 'preguntas-frecuentes'];
        foreach ($staticEs as $name) {
            if (Route::has($name)) {
                $add(route($name, [], true), null, 'weekly', $name === 'inicio' ? '1.0' : '0.9');
            }
        }

        Tour::query()->select('slug', 'updated_at')->orderBy('id')->chunk(200, function ($tours) use ($add) {
            foreach ($tours as $t) {
                $add(route('tour.show', $t->slug, true), $t->updated_at?->toAtomString(), 'weekly', '0.85');
            }
        });

        Estour::query()->select('slug', 'updated_at')->orderBy('id')->chunk(200, function ($tours) use ($add) {
            foreach ($tours as $t) {
                $add(route('estour.show', $t->slug, true), $t->updated_at?->toAtomString(), 'weekly', '0.85');
            }
        });

        TourCategory::query()->select('slug', 'updated_at')->orderBy('id')->chunk(100, function ($rows) use ($add) {
            foreach ($rows as $row) {
                $add(route('category.show', $row->slug, true), $row->updated_at?->toAtomString(), 'weekly', '0.75');
            }
        });

        EsCategoria::query()->select('slug', 'updated_at')->orderBy('id')->chunk(100, function ($rows) use ($add) {
            foreach ($rows as $row) {
                $add(route('categoria.show', $row->slug, true), $row->updated_at?->toAtomString(), 'weekly', '0.75');
            }
        });

        Enblog::query()->select('slug', 'updated_at')->chunk(100, function ($rows) use ($add) {
            foreach ($rows as $row) {
                $add(route('enblog.show', $row->slug, true), $row->updated_at?->toAtomString(), 'monthly', '0.7');
            }
        });

        Esblog::query()->select('slug', 'updated_at')->chunk(100, function ($rows) use ($add) {
            foreach ($rows as $row) {
                $add(route('esblog.show', $row->slug, true), $row->updated_at?->toAtomString(), 'monthly', '0.7');
            }
        });

        Entag::query()->select('slug', 'updated_at')->chunk(100, function ($rows) use ($add) {
            foreach ($rows as $row) {
                $add(route('entag.show', $row->slug, true), $row->updated_at?->toAtomString(), 'monthly', '0.45');
            }
        });

        Estag::query()->select('slug', 'updated_at')->chunk(100, function ($rows) use ($add) {
            foreach ($rows as $row) {
                $add(route('estag.show', $row->slug, true), $row->updated_at?->toAtomString(), 'monthly', '0.45');
            }
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.e($u['loc'])."</loc>\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>'.e(substr($u['lastmod'], 0, 10))."</lastmod>\n";
            }
            $xml .= '    <changefreq>'.e($u['changefreq'])."</changefreq>\n";
            $xml .= '    <priority>'.e($u['priority'])."</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
