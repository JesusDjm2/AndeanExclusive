<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Mes;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $anios = Anio::query()->orderByDesc('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();

        return view('home', compact('anios', 'meses'));
    }
}
