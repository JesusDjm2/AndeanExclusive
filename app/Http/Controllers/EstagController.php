<?php

namespace App\Http\Controllers;

use App\Models\Estag;
use Illuminate\Http\Request;

class EstagController extends Controller
{
    public function index()
    {
        $tags = Estag::all();
        return view('admin.esblogs.tags.index', compact('tags'));
    }
    public function create()
    {
        return view('admin.esblogs.tags.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $tag = new Estag();
        $tag->nombre = $validatedData['nombre'];
        $tag->slug = $validatedData['slug'];
        $tag->save();

        return redirect()->route('estags.index')
            ->with('success', 'Tag creado exitosamente.');
    }
    public function edit($id)
    {
        $tag = Estag::findOrFail($id);
        return view('admin.esblogs.tags.edit', compact('tag'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $registro = Estag::findOrFail($id);
        $registro->update($validatedData);
        return redirect()->route('estags.index')->with('success', '¡Tag actualizado exitosamente!');
    }
    public function show($slug)
    {
        $tag = Estag::where('slug', $slug)->firstOrFail();
        $blogs = $tag->blogs;
        $tags = Estag::all();        
        return view('admin.esblogs.tags.show', compact('tags', 'blogs', 'tag'));
    }
    public function destroy($id)
    {
        $registro = Estag::findOrFail($id);
        $registro->delete();
        return redirect()->route('estags.index')->with('success', '¡Tag eliminado exitosamente!');
    }
}
