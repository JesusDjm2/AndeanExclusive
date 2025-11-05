<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\App;

class SitemapController extends Controller
{
    public function sitemap () {

        // creamos el nuevo objeto sitemap
        $sitemap_contents = App::make("sitemap");
        // establecer cache
        $sitemap_contents->setCache('laravel.sitemap_contents', 3600);
        // obtener todos los posts de la base de datos
        $blogs = App::where('published', 1)->orderBy('created_at', 'desc')->get();
        // agregar todos los posts al sitemap
        foreach ($blogs as $blog)
        {
            $url = url('/'.$blog->id);
            $sitemap_contents->add($url, $blog->updated_at,'1.0','daily');
        }
        // mostrar el sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap_contents->render('xml');
    }
}