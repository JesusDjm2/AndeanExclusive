<?php

namespace App\Http\Controllers;

use App\Models\EsCategoria;
use App\Models\Estour;
use App\Models\Tour;
use App\Support\SubidaImagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EsTourController extends Controller
{
    public function index()
    {
        $tours = Estour::with(['categorias', 'tour'])->get();
        $totalTours = Estour::count();
        $categorias = EsCategoria::orderBy('nombre')->get();

        return view('admin.estours.index', compact('tours', 'totalTours', 'categorias'));
    }

    public function create()
    {
        $categorias = EsCategoria::pluck('nombre', 'id');
        $entours = Tour::all();

        return view('admin.estours.create', compact('categorias', 'entours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:estours',
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
            'slug' => 'required|unique:estours',
            'keywords' => 'required',
            'relacionado_id' => 'required|exists:tours,id',
        ], SubidaImagenes::mensajes(['imgThumb', 'imgFull']));

        $tour = new Estour;
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
            $tour->mapa = null;
        } */

        $tour->descripcionCorta = $request->input('descripcionCorta');
        $tour->presentacion = $request->input('presentacion');
        $tour->itinerario = $request->input('itinerario');
        $tour->incluye = $request->input('incluye');
        $tour->importante = $request->input('importante');
        $tour->keywords = $request->input('keywords');
        $tour->slug = $request->input('slug');
        $tour->relacionado_id = $request->input('relacionado_id');
        $tour->save();

        $categorias = $request->input('categorias');
        $tour->categorias()->attach($categorias);

        return redirect()->route('estours.index')->with('success', 'Tour creado exitosamente!');
    }

    public function edit($id)
    {
        $entours = Tour::all();
        $tour = Estour::findOrFail($id);
        $categorias = EsCategoria::pluck('nombre', 'id');

        return view('admin.estours.edit', compact('tour', 'categorias', 'entours'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'recorrido' => 'required',
            'dias' => 'required|integer',
            'precio' => 'required',
            'imgThumb' => 'sometimes|required|' . SubidaImagenes::regla(),
            'imgFull' => 'sometimes|required|' . SubidaImagenes::regla(),
            'descripcionCorta' => 'required',
            'presentacion' => 'required',
            'itinerario' => 'required',
            'incluye' => 'required',
            'mapa' => 'nullable',
            'importante' => 'required',
            'slug' => 'required|unique:tours,slug,'.$id,
            'keywords' => 'required',
            'relacionado_id' => 'required|exists:tours,id',
        ], SubidaImagenes::mensajes(['imgThumb', 'imgFull']));

        $tour = Estour::findOrFail($id);
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
        }
 */
        $tour->descripcionCorta = $request->input('descripcionCorta');
        $tour->presentacion = $request->input('presentacion');
        $tour->itinerario = $request->input('itinerario');
        $tour->incluye = $request->input('incluye');
        $tour->importante = $request->input('importante');
        $tour->keywords = $request->input('keywords');
        $tour->slug = $request->input('slug');
        $tour->relacionado_id = $request->input('relacionado_id');

        $tour->save();

        $categorias = $request->input('categorias');
        $tour->categorias()->sync($categorias);

        return redirect()->route('estours.index')->with('success', 'Tour actualizado exitosamente!');
    }

    /**
     * Público: /{slug}. Admin resource: /tours-espanol/{id} — ambos comparten este método.
     */
    public function show($slugOrId)
    {
        $tour = Estour::where('slug', $slugOrId)->first();
        if (! $tour && ctype_digit((string) $slugOrId)) {
            $tour = Estour::find($slugOrId);
        }
        abort_if(! $tour, 404);

        $tour->load('categorias');
        $tours = Estour::query()
            ->where('id', '!=', $tour->id)
            ->with('categorias')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('tours.show', [
            'locale' => 'es',
            'tour' => $tour,
            'tours' => $tours,
            'altTour' => $tour->relacionado_id ? Tour::find($tour->relacionado_id) : null,
            'categorias' => EsCategoria::all(),
        ]);
    }

    public function destroy($id)
    {
        $tour = Estour::findOrFail($id);
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

        return redirect()->route('estours.index')->with('success', 'Tour eliminado exitosamente!');
    }
}
