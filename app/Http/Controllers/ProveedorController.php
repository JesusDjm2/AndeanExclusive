<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Proveedor;
use App\Support\SubidaImagenes;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $categoriaId = $request->input('categoria_id');
        $q = trim((string) $request->input('q'));

        $proveedors = Proveedor::query()
            ->with(['categoria', 'hotel.habitaciones'])
            ->when($categoriaId, fn ($query) => $query->where('categoria_id', $categoriaId))
            ->when($q !== '', function ($query) use ($q) {
                $wildcard = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(function ($sub) use ($wildcard) {
                    $sub->where('nombre', 'like', $wildcard)
                        ->orWhere('ruc', 'like', $wildcard)
                        ->orWhere('telefono', 'like', $wildcard)
                        ->orWhere('correo', 'like', $wildcard)
                        ->orWhere('direccion', 'like', $wildcard);
                });
            })
            ->orderBy('nombre')
            ->paginate(25)
            ->withQueryString();

        $categorias = Categoria::withCount('proveedors')->orderBy('nombre')->get();

        return view('proveedor.index', compact('proveedors', 'categorias', 'categoriaId', 'q'))
            ->with('i', ($proveedors->currentPage() - 1) * $proveedors->perPage());
    }

    public function create()
    {
        $categorias = Categoria::pluck('nombre', 'id');
        $proveedor = null; // 👈 Esto es clave
        return view('proveedor.create', compact('categorias', 'proveedor'));
    }

    public function store(Request $request)
    {
        request()->validate(Proveedor::$rules);

        $proveedor = Proveedor::create($request->all());

        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show($id)
    {
        $proveedor = Proveedor::with([
            'categoria',
            'hotel.habitaciones.tarifas',
            'tarifas' => fn ($q) => $q->conPrioridadTemporada(),
        ])->findOrFail($id);

        return view('proveedor.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        $categorias = Categoria::pluck('nombre', 'id');
        return view('proveedor.edit', compact('proveedor', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        request()->validate(Proveedor::$rules);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->nombre = $request->get('nombre');
        $proveedor->categoria_id = $request->get('categoria_id');
        $proveedor->direccion = $request->get('direccion');
        $proveedor->ruc = $request->get('ruc');
        $proveedor->telefono = $request->get('telefono');
        $proveedor->correo = $request->get('correo');
        $proveedor->detalles = $request->get('detalles');
        $proveedor->save();

        if ($proveedor->hotel) {
            $proveedor->hotel->update([
                'nombre' => $proveedor->nombre,
                'ruc' => $proveedor->ruc,
                'direccion' => $proveedor->direccion,
                'telefono' => $proveedor->telefono,
                'correo' => $proveedor->correo,
                'detalles' => $proveedor->detalles,
            ]);
        }

        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }

    /**
     * Registra una nueva habitación en el hotel asociado al proveedor.
     */
    public function habitacionStore(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'tipo' => 'required|string|max:100',
            'capacidad' => 'required|integer|min:1|max:20',
        ]);

        $hotel = $proveedor->hotel ?: Hotel::create([
            'nombre' => $proveedor->nombre,
            'proveedor_id' => $proveedor->id,
            'ruc' => $proveedor->ruc,
            'direccion' => $proveedor->direccion,
            'telefono' => $proveedor->telefono,
            'correo' => $proveedor->correo,
        ]);

        $habitacion = $hotel->habitaciones()->create([
            'tipo' => trim($data['tipo']),
            'capacidad' => (int) $data['capacidad'],
        ]);

        return redirect()->route('proveedors.show', $proveedor->id)
            ->with('success', "Habitación «{$habitacion->tipo}» ({$habitacion->capacidad} pax) registrada con éxito.");
    }

    /**
     * Elimina una habitación si no está siendo usada en reservas/itinerarios activos.
     */
    public function habitacionDestroy(Proveedor $proveedor, Habitacion $habitacion)
    {
        if ($habitacion->habitacionFechas()->exists()) {
            return redirect()->route('proveedors.show', $proveedor->id)
                ->with('error', "No se puede eliminar la habitación «{$habitacion->tipo}»: hay itinerarios con estadías registradas.");
        }

        $habitacion->delete();

        return redirect()->route('proveedors.show', $proveedor->id)
            ->with('success', 'Habitación eliminada correctamente.');
    }

    /**
     * Actualiza la foto de portada del hotel.
     */
    public function hotelFoto(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'img' => 'required|' . SubidaImagenes::regla(),
        ], SubidaImagenes::mensajes(['img']));

        $hotel = $proveedor->hotel ?: Hotel::create([
            'nombre' => $proveedor->nombre,
            'proveedor_id' => $proveedor->id,
            'ruc' => $proveedor->ruc,
            'direccion' => $proveedor->direccion,
            'telefono' => $proveedor->telefono,
            'correo' => $proveedor->correo,
        ]);

        if ($hotel->img && file_exists(public_path($hotel->img))) {
            @unlink(public_path($hotel->img));
        }

        $nombre = SubidaImagenes::guardar($request->file('img'), 'img/programas/hoteles', uniqid() . '_');
        $hotel->update(['img' => 'img/programas/hoteles/' . $nombre]);

        return redirect()->route('proveedors.show', $proveedor->id)
            ->with('success', 'Foto de portada del hotel actualizada.');
    }
}
