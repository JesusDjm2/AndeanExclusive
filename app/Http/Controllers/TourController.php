<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Estour;
use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::all();
        $totalTours = Tour::count();
        return view('admin.tours.index', compact('tours', 'totalTours'));
    }
    public function create()
    {
        $categorias = TourCategory::pluck('nombre', 'id');
        return view('admin.tours.create', compact('categorias'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'recorrido' => 'required',
            'dias' => 'required|integer',
            'precio' => 'required',
            'imgThumb' => 'required|max:2048',
            'imgFull' => 'required|max:2048',
            'descripcionCorta' => 'required',
            'presentacion' => 'required',
            'itinerario' => 'required',
            'incluye' => 'required',
            'mapa' => 'nullable',
            'importante' => 'required',
            'slug' => 'required|unique:tours',
            'keywords' => 'required',
        ]);

        $tour = new Tour();
        $tour->nombre = $request->input('nombre');
        $tour->recorrido = $request->input('recorrido');
        $tour->dias = $request->input('dias');
        $tour->precio = $request->input('precio');

        if ($request->hasFile('imgThumb')) {
            $img = $request->file('imgThumb');
            $rutaImg = "img/Thumbs/";
            $imgThumb = $rutaImg . $img->getClientOriginalName();
            $img->move($rutaImg, $imgThumb);
            $tour->imgThumb = $imgThumb;
        }

        if ($request->hasFile('imgFull')) {
            $img = $request->file('imgFull');
            $rutaImg = "img/Fondos/";
            $imgFull = $rutaImg . $img->getClientOriginalName();
            $img->move($rutaImg, $imgFull);
            $tour->imgFull = $imgFull;
        }
        $tour->mapa=$request->get('mapa');
        /* if ($request->hasFile('mapa')) {
            $rutaMapa = "img/mapa/";
            $mapa = $request->file('mapa');
            $mapaTour = $rutaMapa . $mapa->getClientOriginalName();
            $mapa->move($rutaMapa, $mapaTour);
            $tour->mapa = $mapaTour;
        } else {
            $tour->mapa = null; // Asignar directamente null cuando no hay archivo cargado
        } */

        $tour->descripcionCorta = $request->input('descripcionCorta');
        $tour->presentacion = $request->input('presentacion');
        $tour->itinerario = $request->input('itinerario');
        $tour->incluye = $request->input('incluye');
        $tour->importante = $request->input('importante');
        $tour->keywords = $request->input('keywords');
        $tour->slug = $request->input('slug');

        $tour->save();

        $categorias = $request->input('categorias');
        $tour->categorias()->attach($categorias);

        return redirect()->route('tours.index')->with('success', 'Tour creado exitosamente!');
    }
    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        $categorias = TourCategory::pluck('nombre', 'id');
        return view('admin.tours.edit', compact('tour', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'recorrido' => 'required',
            'dias' => 'required|integer',
            'precio' => 'required',
            'imgThumb' => 'nullable|max:2048',
            'imgFull' => 'nullable|max:2048',
            'descripcionCorta' => 'required',
            'presentacion' => 'required',
            'itinerario' => 'required',
            'incluye' => 'required',
            'mapa' => 'nullable',
            'importante' => 'required',
            'slug' => 'required|unique:tours,slug,' . $id,
            'keywords' => 'required',
        ]);

        $tour = Tour::findOrFail($id);
        $tour->nombre = $request->input('nombre');
        $tour->recorrido = $request->input('recorrido');
        $tour->dias = $request->input('dias');
        $tour->precio = $request->input('precio');

        if ($request->hasFile('imgThumb')) {
            $img = $request->file('imgThumb');
            $rutaImg = "img/Thumbs/";
            $imgThumb = $rutaImg . $img->getClientOriginalName();
            $img->move($rutaImg, $imgThumb);
            $tour->imgThumb = $imgThumb;
        }

        if ($request->hasFile('imgFull')) {
            $img = $request->file('imgFull');
            $rutaImg = "img/Fondos/";
            $imgFull = $rutaImg . $img->getClientOriginalName();
            $img->move($rutaImg, $imgFull);
            $tour->imgFull = $imgFull;
        }
        $tour->mapa=$request->get('mapa');
       /*  if ($request->hasFile('mapa')) {
            $rutaMapa = "img/mapa/";
            $mapa = $request->file('mapa');
            $mapaTour = $rutaMapa . $mapa->getClientOriginalName();
            $mapa->move($rutaMapa, $mapaTour);
            $tour->mapa = $mapaTour;
        } else {
            $tour->mapa = $tour->mapa;
        } */

        $tour->descripcionCorta = $request->input('descripcionCorta');
        $tour->presentacion = $request->input('presentacion');
        $tour->itinerario = $request->input('itinerario');
        $tour->incluye = $request->input('incluye');
        $tour->importante = $request->input('importante');
        $tour->keywords = $request->input('keywords');
        $tour->slug = $request->input('slug');

        $tour->save();

        $categorias = $request->input('categorias');
        $tour->categorias()->sync($categorias);

        return redirect()->route('tours.index')->with('success', 'Tour actualizado exitosamente!');
    } 
    public function show($slug)
    {
        $tour = Tour::where('slug', $slug)->firstOrFail();
        $tours = Tour::where('id', '!=', $tour->id)->orderByDesc('updated_at')->take(3)->get();
        $estour = $tour->estour;
        $categorias = TourCategory::all();
        return view('admin.tours.show', compact('tour', 'tours', 'estour', 'categorias'));
    }
    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        if (Storage::exists($tour->imgThumb)) {
            Storage::delete($tour->imgThumb);
        }
        if (Storage::exists($tour->imgFull)) {
            Storage::delete($tour->imgFull);
        }
        if (Storage::exists($tour->mapa)) {
            Storage::delete($tour->mapa);
        }

        $tour->categorias()->detach();
        $tour->delete();

        return redirect()->route('tours.index')->with('success', 'Tour eliminado exitosamente!');
    }
}
