<?php

namespace App\Http\Controllers;

use App\Models\Entag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EntagController extends Controller
{
    public function index()
    {
        $tags = Entag::withCount('blogs')->orderBy('nombre')->get();
        return view('admin.enblogs.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.enblogs.tags.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:entags,slug',
        ], [
            'nombre.required' => 'El nombre del tag es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otro tag.',
        ]);

        $tag = Entag::create($validatedData);

        return redirect()->route('entags.index')
            ->with('success', 'Tag "' . $tag->nombre . '" creado exitosamente.');
    }

    public function edit($id)
    {
        $tag = Entag::withCount('blogs')->findOrFail($id);
        return view('admin.enblogs.tags.edit', compact('tag'));
    }

    public function update(Request $request, $id)
    {
        $tag = Entag::findOrFail($id);

        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->nombre)
        ]);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:entags,slug,' . $tag->id,
        ], [
            'nombre.required' => 'El nombre del tag es obligatorio.',
            'slug.unique' => 'Este slug ya está en uso por otro tag.',
        ]);

        $tag->update($validatedData);

        return redirect()->route('entags.index')
            ->with('success', '¡Tag "' . $tag->nombre . '" actualizado exitosamente!');
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
        $tag = Entag::findOrFail($id);
        $nombre = $tag->nombre;
        $tag->blogs()->detach();
        $tag->delete();

        return redirect()->route('entags.index')
            ->with('success', '¡Tag "' . $nombre . '" eliminado exitosamente!');
    }
}
