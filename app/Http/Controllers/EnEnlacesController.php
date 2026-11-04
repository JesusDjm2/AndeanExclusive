<?php

namespace App\Http\Controllers;

use App\Models\Enblog;
use App\Models\Esblog;
use App\Models\Estour;
use App\Models\Tour;

class EnEnlacesController extends Controller
{
    public function index()
    {
        $tours = Tour::latest()->take(4)->get();
        $tours2 = Tour::whereNotIn('id', $tours->pluck('id'))->latest()->get();
        $blogs = Enblog::latest()->take(10)->get();

        return view('index', compact('blogs', 'tours', 'tours2'));
    }

    public function around()
    {
        $tours = Tour::whereHas('categorias', function ($query) {
            $query->where('nombre', 'Around Perú');
        })->get();

        return view('around', compact('tours'));
    }

    public function experiences()
    {
        $tours = Tour::whereHas('categorias', function ($query) {
            $query->where('nombre', 'Exclusive Tours');
        })->get();

        return view('experiences', compact('tours'));
    }

    public function adventures()
    {
        $tours = Tour::whereHas('categorias', function ($query) {
            $query->where('nombre', 'Adventure tours');
        })->get();

        return view('adventures', compact('tours'));
    }

    public function blog()
    {
        $lastBlog = Enblog::latest('updated_at')->first();
        $blogs = Enblog::where('updated_at', '<', $lastBlog->updated_at)
            ->latest('updated_at')
            ->get();

        return view('blog', compact('lastBlog', 'blogs'));
    }

    public function about()
    {
        return view('pages.about', ['locale' => 'en']);
    }

    public function testimonials()
    {
        return view('pages.testimonials', ['locale' => 'en']);
    }

    public function terms()
    {
        return view('pages.terms', ['locale' => 'en']);
    }

    public function faqs()
    {
        return view('pages.faqs', ['locale' => 'en']);
    }

    // Tours en español
    public function esIndex()
    {
        $tours = Estour::latest()->take(4)->get();
        $tours2 = Estour::whereNotIn('id', $tours->pluck('id'))->latest()->get();
        $blogs = Esblog::latest()->take(10)->get();

        return view('index-castellano', compact('blogs', 'tours', 'tours2'));
    }

    public function experiencias()
    {
        $tours = Estour::query()
            ->whereHas('categorias', function ($query) {
                $query->whereIn('nombre', [
                    'Tours Exclusivos',
                    'Exclusive Tours',
                    'Exclusivos',
                ]);
            })
            ->get();

        if ($tours->isEmpty()) {
            $tours = Estour::query()->latest()->get();
        }

        return view('experiencias', compact('tours'));
    }

    public function alrededor()
    {
        $tours = Estour::query()
            ->whereHas('categorias', function ($query) {
                $query->whereIn('nombre', [
                    'Tours en Perú',
                    'Around Perú',
                    'Alrededor de Perú',
                    'Alrededor Peru',
                ]);
            })
            ->get();

        if ($tours->isEmpty()) {
            $tours = Estour::query()->latest()->get();
        }

        return view('alrededor-de-peru', compact('tours'));
    }

    public function caminatas()
    {
        $tours = Estour::query()
            ->whereHas('categorias', function ($query) {
                $query->whereIn('nombre', [
                    'Tours de Aventura',
                    'Aventuras',
                    'Adventure tours',
                ]);
            })
            ->get();

        if ($tours->isEmpty()) {
            $tours = Estour::query()->latest()->get();
        }

        return view('caminatas-peru', compact('tours'));
    }

    public function bloges()
    {
        $lastBlog = Esblog::query()->latest('updated_at')->first();

        if (! $lastBlog) {
            return view('blog-castellano', [
                'lastBlog' => null,
                'blogs' => collect(),
            ]);
        }

        $blogs = Esblog::query()
            ->where('updated_at', '<', $lastBlog->updated_at)
            ->latest('updated_at')
            ->get();

        return view('blog-castellano', compact('lastBlog', 'blogs'));
    }

    public function preguntas()
    {
        return view('pages.faqs', ['locale' => 'es']);
    }

    public function nosotros()
    {
        return view('pages.about', ['locale' => 'es']);
    }

    public function testimonios()
    {
        return view('pages.testimonials', ['locale' => 'es']);
    }

    public function terminos()
    {
        return view('pages.terms', ['locale' => 'es']);
    }
}
