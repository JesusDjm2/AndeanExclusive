<?php

namespace App\Http\Controllers;

use App\Models\Entag;
use Illuminate\Http\Request;

class EntagController extends Controller
{
    public function index()
    {
        $tags = Entag::all();
        return view('admin.enblogs.tags.index', compact('tags'));
    }
    public function create()
    {
        return view('admin.enblogs.tags.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $tag = new Entag();
        $tag->nombre = $validatedData['nombre'];
        $tag->slug = $validatedData['slug'];
        $tag->save();

        return redirect()->route('entags.index')
            ->with('success', 'Tag creado exitosamente.');
    }
    public function edit($id)
    {
        $tag = Entag::findOrFail($id);
        return view('admin.enblogs.tags.edit', compact('tag'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $registro = Entag::findOrFail($id);
        $registro->update($validatedData);
        return redirect()->route('entags.index')->with('success', '¡Tag actualizado exitosamente!');
    }
    public function show($slug)
    {
        $tag = Entag::where('slug', $slug)->firstOrFail();
        $blogs = $tag->blogs;
        $tags = Entag::all();
        return view('admin.enblogs.tags.show', compact('tag', 'blogs', 'tags'));
    }
    public function destroy($id)
    {
        $registro = Entag::findOrFail($id);
        $registro->delete();
        return redirect()->route('entags.index')->with('success', '¡Tag eliminado exitosamente!');
    }
}
