<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Support\SubidaImagenes;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ImagenController extends Controller
{
    private const CARPETA = 'img/galeria';

    private const MAX_MB = 10;

    private function yaExiste(string $nombre): bool
    {
        return Imagen::where('img', $nombre)->exists() || is_file(public_path(self::CARPETA . '/' . $nombre));
    }

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
        $request->validate(
            ['img' => 'required|array', 'img.*' => SubidaImagenes::regla(self::MAX_MB)],
            SubidaImagenes::mensajes(['img.*'], self::MAX_MB) + ['img.required' => 'Elige al menos una imagen.']
        );

        // Primero se revisan todas: así no queda una subida a medias si alguna ya existe.
        $archivos = collect($request->file('img'))->map(fn (UploadedFile $img) => [$img, SubidaImagenes::nombreSeguro($img)]);
        foreach ($archivos as [, $nombre]) {
            if ($this->yaExiste($nombre)) {
                return redirect()->route('imagenes.create')->withErrors("La imagen «{$nombre}» ya existe. Por favor, elige otra o cámbiale el nombre.");
            }
        }

        foreach ($archivos as [$img, $nombre]) {
            $img->move(public_path(self::CARPETA), $nombre);
            Imagen::create(['img' => $nombre]);
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
        $imagen = Imagen::findOrFail($id);
        $request->validate(['img' => 'nullable|' . SubidaImagenes::regla(self::MAX_MB)], SubidaImagenes::mensajes(['img'], self::MAX_MB));

        if ($img = $request->file('img')) {
            $nombre = SubidaImagenes::nombreSeguro($img);
            if ($this->yaExiste($nombre)) {
                return redirect()->route('imagenes.edit', ['imagene' => $id])->withErrors("La imagen «{$nombre}» ya existe. Por favor, elige otra o cámbiale el nombre.");
            }

            $img->move(public_path(self::CARPETA), $nombre);
            $imagen->img = $nombre;
            $imagen->save();
        }

        session()->flash('status', 'Imagen actualizada exitosamente!');

        return redirect()->route('imagenes.index');
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
