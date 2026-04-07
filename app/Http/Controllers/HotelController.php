<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotelController extends Controller
{

    public function index()
    {
        $hoteles = Hotel::all();
        return view('programas.hoteles.index', compact('hoteles'));
    }


    public function create()
    {
        return view('programas.hoteles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'img' => 'nullable|image|max:2048',
            'detalles' => 'nullable|string',

            'habitaciones' => 'array',
            'habitaciones.*.tipo' => 'required|string|max:100',
            'habitaciones.*.capacidad' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $nombre = uniqid() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('img/programas/hoteles'),
                $nombre
            );

            $data['img'] = 'img/programas/hoteles/' . $nombre;
        }
        $hotel = Hotel::create($data);

        if ($request->filled('habitaciones')) {
            foreach ($request->habitaciones as $habitacion) {
                $hotel->habitaciones()->create($habitacion);
            }
        }

        Hotel::create($data);

        return redirect()->route('hotel.index')->with('success', 'Hotel creado correctamente.');
    }


    public function show(Hotel $hotel)
    {
        $hotel->load('habitaciones');
        return view('programas.hoteles.show', compact('hotel'));
    }


    public function edit(Hotel $hotel)
    {
        return view('programas.hoteles.edit', compact('hotel'));
    }


    /* public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'img' => 'nullable|image|max:2048',
            'detalles' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {

            // Borrar imagen anterior si existe
            if ($hotel->img && file_exists(public_path($hotel->img))) {
                unlink(public_path($hotel->img));
            }

            $file = $request->file('img');
            $nombre = uniqid() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('img/programas/hoteles'),
                $nombre
            );

            $data['img'] = 'img/programas/hoteles/' . $nombre;
        }

        $hotel->update($data);

        return redirect()
            ->route('hotel.index')
            ->with('success', 'Hotel actualizado correctamente.');
    } */

    public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'img' => 'nullable|image|max:2048',
            'detalles' => 'nullable|string',

            'habitacions' => 'array',
            'habitacions.*.id' => 'nullable|exists:habitaciones,id',
            'habitacions.*.tipo' => 'required|string|max:100',
            'habitacions.*.capacidad' => 'required|integer|min:1',
        ]);

        /* ==========================
       IMAGEN
    ========================== */
        if ($request->hasFile('img')) {

            if ($hotel->img && file_exists(public_path($hotel->img))) {
                unlink(public_path($hotel->img));
            }

            $file = $request->file('img');
            $nombre = uniqid() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('img/programas/hoteles'),
                $nombre
            );

            $data['img'] = 'img/programas/hoteles/' . $nombre;
        }

        /* ==========================
       UPDATE HOTEL
    ========================== */
        $hotel->update($data);

        /* ==========================
       HABITACIONES
    ========================== */
        $idsExistentes = $hotel->habitaciones->pluck('id')->toArray();
        $idsEnviados = [];

        if ($request->filled('habitaciones')) {

            foreach ($request->habitaciones as $habitacionData) {

                // ACTUALIZAR
                if (!empty($habitacionData['id'])) {

                    $idsEnviados[] = $habitacionData['id'];

                    $hotel->habitaciones()
                        ->where('id', $habitacionData['id'])
                        ->update([
                            'tipo' => $habitacionData['tipo'],
                            'capacidad' => $habitacionData['capacidad'],
                        ]);
                } else {
                    // CREAR NUEVA
                    $hotel->habitaciones()->create([
                        'tipo' => $habitacionData['tipo'],
                        'capacidad' => $habitacionData['capacidad'],
                    ]);
                }
            }
        }

        // ELIMINAR HABITACIONES BORRADAS EN EL FORM
        $idsAEliminar = array_diff($idsExistentes, $idsEnviados);

        if (count($idsAEliminar)) {
            $hotel->habitaciones()->whereIn('id', $idsAEliminar)->delete();
        }

        return redirect()
            ->route('hotel.index')
            ->with('success', 'Hotel actualizado correctamente.');
    }




    public function destroy(Hotel $hotel)
    {
        if ($hotel->img && file_exists(public_path($hotel->img))) {
            unlink(public_path($hotel->img));
        }

        $hotel->delete();

        return redirect()->route('hotel.index')->with('success', 'Hotel eliminado correctamente.');
    }
}
