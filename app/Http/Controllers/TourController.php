<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourCategory;
use App\Support\SubidaImagenes;
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
            'imgThumb' => 'required|' . SubidaImagenes::regla(),
            'imgFull' => 'required|' . SubidaImagenes::regla(),
            'descripcionCorta' => 'required',
            'presentacion' => 'required',
            'itinerario' => 'required',
            'incluye' => 'required',
            'mapa' => 'nullable',
            'importante' => 'required',
            'slug' => 'required|unique:tours',
            'keywords' => 'required',
        ], SubidaImagenes::mensajes(['imgThumb', 'imgFull']));

        $tour = new Tour;
        $tour->nombre = $request->input('nombre');
        $tour->recorrido = $request->input('recorrido');
        $tour->dias = $request->input('dias');
        $tour->precio = $request->input('precio');

        if ($request->hasFile('imgThumb')) {
            $tour->imgThumb = 'img/Thumbs/' . SubidaImagenes::guardar($request->file('imgThumb'), 'img/Thumbs');
        }

        if ($request->hasFile('imgFull')) {
            $tour->imgFull = 'img/Fondos/' . SubidaImagenes::guardar($request->file('imgFull'), 'img/Fondos');
        }
        $tour->mapa = $request->get('mapa');
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
            'imgThumb' => 'nullable|' . SubidaImagenes::regla(),
            'imgFull' => 'nullable|' . SubidaImagenes::regla(),
            'descripcionCorta' => 'required',
            'presentacion' => 'required',
            'itinerario' => 'required',
            'incluye' => 'required',
            'mapa' => 'nullable',
            'importante' => 'required',
            'slug' => 'required|unique:tours,slug,'.$id,
            'keywords' => 'required',
        ], SubidaImagenes::mensajes(['imgThumb', 'imgFull']));

        $tour = Tour::findOrFail($id);
        $tour->nombre = $request->input('nombre');
        $tour->recorrido = $request->input('recorrido');
        $tour->dias = $request->input('dias');
        $tour->precio = $request->input('precio');

        if ($request->hasFile('imgThumb')) {
            $tour->imgThumb = 'img/Thumbs/' . SubidaImagenes::guardar($request->file('imgThumb'), 'img/Thumbs');
        }

        if ($request->hasFile('imgFull')) {
            $tour->imgFull = 'img/Fondos/' . SubidaImagenes::guardar($request->file('imgFull'), 'img/Fondos');
        }
        $tour->mapa = $request->get('mapa');
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

    /**
     * Público: /en/{slug}. Admin resource: /tours-en-ingles/{id} — ambos comparten este método.
     */
    public function show($slugOrId)
    {
        $tour = Tour::where('slug', $slugOrId)->first();
        if (! $tour && ctype_digit((string) $slugOrId)) {
            $tour = Tour::find($slugOrId);
        }
        abort_if(! $tour, 404);

        $tour->load('categorias');
        $tours = Tour::query()
            ->where('id', '!=', $tour->id)
            ->with('categorias')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('tours.show', [
            'locale' => 'en',
            'tour' => $tour,
            'tours' => $tours,
            'altTour' => $tour->estour,
            'categorias' => TourCategory::all(),
        ]);
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
