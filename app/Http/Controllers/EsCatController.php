<?php

namespace App\Http\Controllers;

use App\Models\EsCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EsCatController extends Controller
{
    public function index()
    {
        $categorias = EsCategoria::withCount('tours')->orderBy('nombre')->get();
        return view('admin.estours.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.estours.categorias.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:escategorias,slug',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otra categoría.',
        ]);

        $categoria = EsCategoria::create($validatedData);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría "' . $categoria->nombre . '" creada exitosamente.');
    }

    public function edit($id)
    {
        $categoria = EsCategoria::withCount('tours')->findOrFail($id);
        return view('admin.estours.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = EsCategoria::findOrFail($id);

        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:escategorias,slug,' . $categoria->id,
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otra categoría.',
        ]);

        $categoria->update($validatedData);

        return redirect()->route('categorias.index')
            ->with('success', '¡Categoría "' . $categoria->nombre . '" actualizada exitosamente!');
    }

    public function show($slug)
    {
        $categoria = EsCategoria::where('slug', $slug)->firstOrFail();
        $tours = $categoria->tours;
        $categorias = EsCategoria::all();
        return view('admin.estours.categorias.show', compact('categoria', 'tours', 'categorias'));
    }

    public function destroy($id)
    {
        $categoria = EsCategoria::findOrFail($id);
        $nombre = $categoria->nombre;
        $categoria->tours()->detach();
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', '¡Categoría "' . $nombre . '" eliminada exitosamente!');
    }
}
