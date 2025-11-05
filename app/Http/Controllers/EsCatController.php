<?php

namespace App\Http\Controllers;

use App\Models\EsCategoria;
use Illuminate\Http\Request;

class EsCatController extends Controller
{
    public function index()
    {
        $categorias = EsCategoria::all();
        return view('admin.estours.categorias.index', compact('categorias'));
    }
    public function create()
    {
        return view('admin.estours.categorias.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $categoria = new EsCategoria();
        $categoria->nombre = $validatedData['nombre'];
        $categoria->slug = $validatedData['slug'];
        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }


    public function edit($id)
    {
        $categoria = EsCategoria::findOrFail($id);
        return view('admin.estours.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $registro = EsCategoria::findOrFail($id);
        $registro->update($validatedData);
        return redirect()->route('categorias.index')->with('success', '¡Categoria actualizada exitosamente!');
    }
    public function show($slug)
    {
        $categoria = EsCategoria::where('slug', $slug)->firstOrFail();
        $tours = $categoria->tours;
        return view('admin.estours.categorias.show', compact('categoria', 'tours'));
    }

    public function destroy($id)
    {
        $registro = EsCategoria::findOrFail($id);
        $registro->delete();
        return redirect()->route('categorias.index')->with('success', '¡Categoria eliminada exitosamente!');
    }
}
