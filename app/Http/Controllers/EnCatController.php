<?php

namespace App\Http\Controllers;

use App\Models\TourCategory;
use Illuminate\Http\Request;

class EnCatController extends Controller
{
    public function index()
    {
        $categorias = TourCategory::all();
        return view('admin.tours.categorias.index', compact('categorias'));
    }
    public function create()
    {
        return view('admin.tours.categorias.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $categoria = new TourCategory();
        $categoria->nombre = $validatedData['nombre'];
        $categoria->slug = $validatedData['slug'];
        $categoria->save();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }


    public function edit($id)
    {
        $categoria = TourCategory::findOrFail($id);
        return view('admin.tours.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $registro = TourCategory::findOrFail($id);
        $registro->update($validatedData);
        return redirect()->route('categories.index')->with('success', '¡Registro actualizado exitosamente!');
    }
    public function show($slug)
    {
        $categoria = TourCategory::where('slug', $slug)->firstOrFail();
        $tours = $categoria->tours;
        $categorias= TourCategory::all();
        return view('admin.tours.categorias.show', compact('categoria', 'tours', 'categorias'));
    }

    public function destroy($id)
    {
        $registro = TourCategory::findOrFail($id);
        $registro->delete();
        return redirect()->route('categories.index')->with('success', '¡Registro eliminado exitosamente!');
    }
}
