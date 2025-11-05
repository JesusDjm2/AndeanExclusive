<?php

namespace App\Http\Controllers;

use App\Models\Enblog;
use App\Models\Esblog;
use App\Models\Estour;
use App\Models\Tour;
use Illuminate\Http\Request;

class EnEnlacesController extends Controller
{
    public function index()
    {
        $tours = Tour::latest()->take(4)->get();
        $tours2 = Tour::whereNotIn('id', $tours->pluck('id'))->latest()->get();
        $blogs = Enblog::latest()->take(4)->get();
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
        return view('about');
    }
    public function testimonials()
    {
        return view('testimonials');
    }
    public function terms()
    {
        return view('terms-conditions-aet');
    }
    public function faqs()
    {
        return view('faqs');
    }

    //Tours en español
    public function esIndex()
    {
        $tours = Estour::latest()->take(4)->get();
        $tours2 = Estour::whereNotIn('id', $tours->pluck('id'))->latest()->get();
        $blogs = Esblog::latest()->take(4)->get();
        return view('index-castellano', compact('blogs', 'tours', 'tours2'));
    }
    public function experiencias()
    {
        $tours = Estour::all();
        return view('experiencias', compact('tours'));
    }
    public function alrededor()
    {
        $tours = Estour::all();
        return view('alrededor-de-peru', compact('tours'));
    }
    public function caminatas()
    {
        $tours = Estour::all();
        return view('caminatas-peru', compact('tours'));
    }
    public function bloges()
    {
        $lastBlog = Esblog::latest('updated_at')->first();
        $blogs = Esblog::where('updated_at', '<', $lastBlog->updated_at)
            ->latest('updated_at')
            ->get();
        return view('blog-castellano', compact('lastBlog', 'blogs'));
    }
    public function preguntas()
    {
        return view('preguntas-frecuentes');
    }
    public function nosotros()
    {
        return view('nosotros');
    }
    public function testimonios()
    {
        return view('testimonios');
    }
}
