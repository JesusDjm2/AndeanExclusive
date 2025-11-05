<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imagenes = Imagen::latest()->get();
        return view('admin.imagenes.index', compact('imagenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.imagenes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imgFiles = $request->file('img');

        foreach ($imgFiles as $img) {
            $imgTour = $img->getClientOriginalName();
            $imagenExistente = Imagen::where('img', $imgTour)->first();
            if ($imagenExistente) {
                return redirect()->route('imagenes.create')->withErrors('La imagen ya existe en la base de datos. Por favor, elige una nueva imagen.');
            }
            $rutaImg = public_path("img/galeria/");
            $img->move($rutaImg, $imgTour);

            $imagen = new Imagen();
            $imagen->img = $imgTour;
            
            $imagen->save();
        }

        session()->flash('status', 'Imagen/Imágenes creadas exitosamente!');
        return redirect()->route('imagenes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Imagen  $imagen
     * @return \Illuminate\Http\Response
     */
    public function show(Imagen $imagen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Imagen  $imagen
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $imagen = Imagen::find($id);
        return view('admin.imagenes.edit')->with('imagen', $imagen);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Imagen  $imagen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $imagen = Imagen::find($id);
        if ($img = $request->file('img')) {
            $imgTour = $img->getClientOriginalName();
            $imagenExistente = Imagen::where('img', $imgTour)->first();
            if ($imagenExistente) {
                return redirect()->route('imagenes.edit', ['imagene' => $id])->withErrors('La imagen ya existe en la base de datos. Por favor, elige una nueva imagen.');
            }

            $rutaImg = public_path("img/galeria/");
            $img->move($rutaImg, $imgTour);
            $imagen['img'] = $imgTour;
        } else {
            unset($imagen['img']);
        }

        $imagen->save();
        session()->flash('status', 'Imagen actualizada exitosamente!');
        return view('admin.imagenes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Imagen  $imagen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imagen = Imagen::find($id);
        $imagen->delete();
        session()->flash('status', 'Imagen borrada exitosamente!');
        return redirect()->route('imagenes.index');
    }
}
