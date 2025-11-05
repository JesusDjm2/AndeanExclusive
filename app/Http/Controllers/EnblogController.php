<?php

namespace App\Http\Controllers;

use App\Models\Enblog;
use App\Models\Entag;
use App\Models\Tour;
use Illuminate\Http\Request;

class EnblogController extends Controller
{
    public function index()
    {
        $blogs = Enblog::all();
        return view('admin.enblogs.index', compact('blogs'));
    }
    public function create()
    {
        $tags = Entag::pluck('nombre', 'id');
        return view('admin.enblogs.create', compact('tags'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:enblogs',
            'imgThumb' => 'required|image|max:2048',
            'imgFull' => 'required|image|max:2048',
            'descripcionCorta' => 'required|string',
            'descripcion' => 'required|string',
            'slug' => 'required|string|unique:enblogs',
            'keywords' => 'nullable|string',
            'tags' => 'required|array',
        ]);

        $imageNameThumb = $request->file('imgThumb')->getClientOriginalName();
        $imageNameFull = $request->file('imgFull')->getClientOriginalName();

        $request->file('imgThumb')->move(public_path('img/thumb'), $imageNameThumb);
        $request->file('imgFull')->move(public_path('img/full'), $imageNameFull);

        $enblog = Enblog::create([
            'nombre' => $request->input('nombre'),
            'imgThumb' => $imageNameThumb,
            'imgFull' => $imageNameFull,
            'descripcionCorta' => $request->input('descripcionCorta'),
            'descripcion' => $request->input('descripcion'),
            'slug' => $request->input('slug'),
            'keywords' => $request->input('keywords'),
        ]);

        $enblog->tags()->attach($request->input('tags'));

        return redirect()->route('enblogs.index')->with('success', 'Registro creado correctamente');
    }
    public function edit($id)
    {
        $blog = Enblog::findOrFail($id);
        $tags = Entag::pluck('nombre', 'id');
        return view('admin.enblogs.edit', compact('blog', 'tags'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|unique:enblogs,nombre,' . $id,
            'imgThumb' => 'nullable|image|max:2048',
            'imgFull' => 'nullable|image|max:2048',
            'descripcionCorta' => 'required|string',
            'descripcion' => 'required|string',
            'slug' => 'required|string|unique:enblogs,slug,' . $id,
            'keywords' => 'nullable|string',
            'tags' => 'required|array',
        ]);

        $enblog = Enblog::findOrFail($id);

        if ($request->hasFile('imgThumb')) {
            $imageNameThumb = $request->file('imgThumb')->getClientOriginalName();
            $request->file('imgThumb')->move(public_path('img/thumb'), $imageNameThumb);
            $enblog->imgThumb = $imageNameThumb;
        }

        if ($request->hasFile('imgFull')) {
            $imageNameFull = $request->file('imgFull')->getClientOriginalName();
            $request->file('imgFull')->move(public_path('img/full'), $imageNameFull);
            $enblog->imgFull = $imageNameFull;
        }

        $enblog->nombre = $request->input('nombre');
        $enblog->descripcionCorta = $request->input('descripcionCorta');
        $enblog->descripcion = $request->input('descripcion');
        $enblog->slug = $request->input('slug');
        $enblog->keywords = $request->input('keywords');
        $enblog->save();

        $enblog->tags()->sync($request->input('tags'));

        return redirect()->route('enblogs.index')->with('success', 'Registro actualizado correctamente');
    }
    public function show($slug)
    {
        $blog = Enblog::where('slug', $slug)->with('tags')->firstOrFail();
        $blogs = Enblog::where('slug', '!=', $slug)->latest('updated_at')->take(4)->get();
        $tours = Tour::latest('updated_at')->take(3)->get();
        $esblog = $blog->esblog;
        return view('admin.enblogs.show', compact('blog', 'blogs', 'tours', 'esblog'));
    }
    public function destroy($id)
    {
        $blog = Enblog::find($id);
        $blog->delete();
        return redirect()->route('enblogs.index')->with('success', 'Blog eliminado exitosamente');
    }
}
