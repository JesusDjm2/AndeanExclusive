<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Http\Controllers\Controller;
use App\Support\SubidaImagenes;
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
            'img' => 'nullable|' . SubidaImagenes::regla(),
            'detalles' => 'nullable|string',

            'habitaciones' => 'array',
            'habitaciones.*.tipo' => 'required|string|max:100',
            'habitaciones.*.capacidad' => 'required|integer|min:1',
        ], SubidaImagenes::mensajes(['img']));

        if ($request->hasFile('img')) {
            $nombre = SubidaImagenes::guardar($request->file('img'), 'img/programas/hoteles', uniqid() . '_');

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
            'img' => 'nullable|' . SubidaImagenes::regla(),
            'detalles' => 'nullable|string',
        ], SubidaImagenes::mensajes(['img']));

        if ($request->hasFile('img')) {

            // Borrar imagen anterior si existe
            if ($hotel->img && file_exists(public_path($hotel->img))) {
                unlink(public_path($hotel->img));
            }

            $nombre = SubidaImagenes::guardar($request->file('img'), 'img/programas/hoteles', uniqid() . '_');

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
            'img' => 'nullable|' . SubidaImagenes::regla(),
            'detalles' => 'nullable|string',

            'habitacions' => 'array',
            'habitacions.*.id' => 'nullable|exists:habitaciones,id',
            'habitacions.*.tipo' => 'required|string|max:100',
            'habitacions.*.capacidad' => 'required|integer|min:1',
        ], SubidaImagenes::mensajes(['img']));

        /* ==========================
       IMAGEN
    ========================== */
        if ($request->hasFile('img')) {

            if ($hotel->img && file_exists(public_path($hotel->img))) {
                unlink(public_path($hotel->img));
            }

            $nombre = SubidaImagenes::guardar($request->file('img'), 'img/programas/hoteles', uniqid() . '_');

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

        // Borrar una habitación borraría en cascada el alojamiento de los programas que la usan.
        $enUso = $hotel->habitaciones()->whereIn('id', $idsAEliminar)->whereHas('habitacionFechas')->pluck('tipo');
        $hotel->habitaciones()->whereIn('id', $idsAEliminar)->whereDoesntHave('habitacionFechas')->get()->each->delete();

        return redirect()
            ->route('hotel.index')
            ->with('success', 'Hotel actualizado correctamente.')
            ->with('error', $enUso->isNotEmpty() ? 'No se eliminaron porque hay programas que las usan: ' . $enUso->implode(', ') . '.' : null);
    }




    public function destroy(Hotel $hotel)
    {
        if ($hotel->habitaciones()->whereHas('habitacionFechas')->exists()) {
            return redirect()->route('hotel.index')->with('error', "No se puede eliminar «{$hotel->nombre}»: hay programas alojados en este hotel.");
        }

        if ($hotel->img && file_exists(public_path($hotel->img))) {
            unlink(public_path($hotel->img));
        }

        $hotel->delete();

        return redirect()->route('hotel.index')->with('success', 'Hotel eliminado correctamente.');
    }

    /**
     * Registro rápido de hotel y habitación inicial desde modal en programas.
     */
    public function rapido(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'habitacion_tipo' => 'nullable|string|max:100',
            'habitacion_capacidad' => 'nullable|integer|min:1|max:20',
        ]);

        $hotel = \App\Models\Hotel::create([
            'nombre' => trim($data['nombre']),
            'direccion' => $data['direccion'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'correo' => $data['correo'] ?? null,
        ]);

        $tipo = trim($data['habitacion_tipo'] ?? '') ?: 'Estándar / Doble';
        $capacidad = (int) ($data['habitacion_capacidad'] ?? 2) ?: 2;

        $habitacion = $hotel->habitaciones()->create([
            'tipo' => $tipo,
            'capacidad' => $capacidad,
        ]);

        return response()->json([
            'ok' => true,
            'hotel' => [
                'id' => $hotel->id,
                'nombre' => $hotel->nombre,
            ],
            'habitacion' => [
                'id' => $habitacion->id,
                'tipo' => $habitacion->tipo,
                'capacidad' => $habitacion->capacidad,
                'texto' => "{$hotel->nombre} · {$habitacion->tipo} ({$habitacion->capacidad} " . ($habitacion->capacidad === 1 ? 'persona' : 'personas') . ")",
            ],
        ]);
    }
}
