<?php

namespace App\Http\Controllers;

use App\Models\Enblog;
use App\Models\Esblog;
use App\Models\Estag;
use App\Models\Estour;
use App\Support\SubidaImagenes;
use Illuminate\Http\Request;

class EsblogController extends Controller
{
    public function index()
    {
        $blogs = Esblog::with(['tags', 'blog'])->latest()->get();
        $tags = Estag::orderBy('nombre')->get();
        return view('admin.esblogs.index', compact('blogs', 'tags'));
    }
    public function create()
    {
        $tags = Estag::pluck('nombre', 'id');
        $enblogs = Enblog::all();
        return view('admin.esblogs.create', compact('tags', 'enblogs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'relacionado_id' => 'required|exists:enblogs,id',
            'imgThumb' => 'required|' . SubidaImagenes::regla(),
            'imgFull' => 'required|' . SubidaImagenes::regla(),
            'descripcionCorta' => 'required|string',
            'descripcion' => 'required|string',
            'tags' => 'nullable|array',
            'keywords' => 'nullable|string',
            'slug' => 'required|string|unique:esblogs,slug',
        ], SubidaImagenes::mensajes(['imgThumb', 'imgFull']));

        $imageNameThumb = '';
        $imageNameFull = '';

        if ($request->hasFile('imgThumb')) {
            $imageThumb = $request->file('imgThumb');
            $imageNameThumb = SubidaImagenes::guardar($imageThumb, 'img/thumb');
        }

        if ($request->hasFile('imgFull')) {
            $imageFull = $request->file('imgFull');
            $imageNameFull = SubidaImagenes::guardar($imageFull, 'img/full');
        }

        $blog = new Esblog;
        $blog->nombre = $request->nombre;
        $blog->relacionado_id = $request->relacionado_id;
        $blog->imgThumb = 'img/thumb/' . $imageNameThumb;
        $blog->imgFull = 'img/full/' . $imageNameFull;
        $blog->descripcionCorta = $request->descripcionCorta;
        $blog->descripcion = $request->descripcion;
        $blog->keywords = $request->keywords;
        $blog->slug = $request->slug;

        $blog->save();

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return redirect()->route('esblogs.index')->with('success', 'Blog creado exitosamente.');
    }


    public function edit($id)
    {
        $item = Esblog::findOrFail($id);
        $enblogs = Enblog::all();
        $tags = Estag::pluck('nombre', 'id');

        return view('admin.esblogs.edit', compact('item', 'enblogs', 'tags'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'relacionado_id' => 'nullable|exists:enblogs,id',
            'imgThumb' => 'nullable|' . SubidaImagenes::regla(),
            'imgFull' => 'nullable|' . SubidaImagenes::regla(),
            'descripcionCorta' => 'required|string',
            'descripcion' => 'required|string',
            'tags' => 'nullable|array',
            'keywords' => 'nullable|string',
            'slug' => 'required|string|unique:esblogs,slug,' . $id,
        ], SubidaImagenes::mensajes(['imgThumb', 'imgFull']));

        $blog = Esblog::findOrFail($id);

        $imageNameThumb = $blog->imgThumb;
        $imageNameFull = $blog->imgFull;

        if ($request->hasFile('imgThumb')) {
            $imageThumb = $request->file('imgThumb');
            $imageNameThumb = SubidaImagenes::guardar($imageThumb, 'img/thumb');
            $blog->imgThumb = 'img/thumb/' . $imageNameThumb;
        }

        if ($request->hasFile('imgFull')) {
            $imageFull = $request->file('imgFull');
            $imageNameFull = SubidaImagenes::guardar($imageFull, 'img/full');
            $blog->imgFull = 'img/full/' . $imageNameFull;
        }

        $blog->nombre = $request->nombre;
        $blog->relacionado_id = $request->relacionado_id;
        $blog->descripcionCorta = $request->descripcionCorta;
        $blog->descripcion = $request->descripcion;
        $blog->keywords = $request->keywords;
        $blog->slug = $request->slug;

        $blog->save();

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        } else {
            $blog->tags()->sync([]);
        }

        return redirect()->route('esblogs.index')->with('success', 'Blog actualizado exitosamente.');
    }


    public function show($slug) 
    {
        $blog = Esblog::where('slug', $slug)->with('tags')->firstOrFail();
        $blogs = Esblog::where('slug', '!=', $slug)->latest('updated_at')->take(4)->get();
        $tours = Estour::inRandomOrder()->take(4)->get();
        $enblog = $blog->blog;
        return view('admin.esblogs.show', compact('blog', 'blogs', 'tours', 'enblog'));
    }

    public function destroy($id)
    {
        $blog = Esblog::findOrFail($id);

        if ($blog->imgThumb) {
            if (file_exists(public_path('img/thumb/' . $blog->imgThumb))) {
                unlink(public_path('img/thumb/' . $blog->imgThumb));
            }
        }

        if ($blog->imgFull) {
            if (file_exists(public_path('img/full/' . $blog->imgFull))) {
                unlink(public_path('img/full/' . $blog->imgFull));
            }
        }

        $blog->delete();

        return redirect()->route('esblogs.index')->with('success', 'Blog eliminado exitosamente.');
    }
}
