<?php

namespace App\Http\Controllers;

use App\Models\Estag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EstagController extends Controller
{
    public function index()
    {
        $tags = Estag::withCount('blogs')->orderBy('nombre')->get();
        return view('admin.esblogs.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.esblogs.tags.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:estags,slug',
        ], [
            'nombre.required' => 'El nombre del tag es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otro tag.',
        ]);

        $tag = Estag::create($validatedData);

        return redirect()->route('estags.index')
            ->with('success', 'Tag "' . $tag->nombre . '" creado exitosamente.');
    }

    public function edit($id)
    {
        $tag = Estag::withCount('blogs')->findOrFail($id);
        return view('admin.esblogs.tags.edit', compact('tag'));
    }

    public function update(Request $request, $id)
    {
        $tag = Estag::findOrFail($id);

        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:estags,slug,' . $tag->id,
        ], [
            'nombre.required' => 'El nombre del tag es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otro tag.',
        ]);

        $tag->update($validatedData);

        return redirect()->route('estags.index')
            ->with('success', '¡Tag "' . $tag->nombre . '" actualizado exitosamente!');
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
        $tag = Estag::findOrFail($id);
        $nombre = $tag->nombre;
        $tag->blogs()->detach();
        $tag->delete();

        return redirect()->route('estags.index')
            ->with('success', '¡Tag "' . $nombre . '" eliminado exitosamente!');
    }
}
