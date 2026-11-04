<?php

namespace App\Http\Controllers;

use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnCatController extends Controller
{
    public function index()
    {
        $categorias = TourCategory::withCount('tours')->orderBy('nombre')->get();
        return view('admin.tours.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.tours.categorias.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otra categoría.',
        ]);

        $categoria = TourCategory::create($validatedData);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría "' . $categoria->nombre . '" creada exitosamente.');
    }

    public function edit($id)
    {
        $categoria = TourCategory::withCount('tours')->findOrFail($id);
        return view('admin.tours.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = TourCategory::findOrFail($id);

        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $categoria->id,
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otra categoría.',
        ]);

        $categoria->update($validatedData);

        return redirect()->route('categories.index')
            ->with('success', '¡Categoría "' . $categoria->nombre . '" actualizada exitosamente!');
    }

    public function show($slug)
    {
        $categoria = TourCategory::where('slug', $slug)->firstOrFail();
        $tours = $categoria->tours;
        $categorias = TourCategory::all();
        return view('admin.tours.categorias.show', compact('categoria', 'tours', 'categorias'));
    }

    public function destroy($id)
    {
        $categoria = TourCategory::findOrFail($id);
        $nombre = $categoria->nombre;
        $categoria->tours()->detach();
        $categoria->delete();

        return redirect()->route('categories.index')
            ->with('success', '¡Categoría "' . $nombre . '" eliminada exitosamente!');
    }
}
