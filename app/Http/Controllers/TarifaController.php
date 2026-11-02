<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Tarifario: lo que cobra cada hotel y proveedor por temporada. Sugiere el costo al cotizar y al reservar. */
class TarifaController extends Controller
{
    public function index(Request $request)
    {
        $hoy = now()->toDateString();
        $vista = in_array($request->input('vista'), ['vigentes', 'vencidas', 'todas'], true) ? $request->input('vista') : 'vigentes';

        $tarifas = Tarifa::query()->with(['habitacion.hotel', 'proveedor'])
            ->when($vista === 'vigentes', fn ($q) => $q->whereDate('hasta', '>=', $hoy))
            ->when($vista === 'vencidas', fn ($q) => $q->whereDate('hasta', '<', $hoy))
            ->when($request->filled('q'), function ($q) use ($request) {
                $texto = '%' . addcslashes(trim((string) $request->input('q')), '%_\\') . '%';
                $q->where(fn ($w) => $w->where('concepto', 'like', $texto)
                    ->orWhereHas('proveedor', fn ($p) => $p->where('nombre', 'like', $texto))
                    ->orWhereHas('habitacion', fn ($h) => $h->where('tipo', 'like', $texto)->orWhereHas('hotel', fn ($o) => $o->where('nombre', 'like', $texto))));
            })
            ->orderBy('desde')->paginate(30)->withQueryString();

        return view('tarifas.index', compact('tarifas', 'vista'));
    }

    public function create()
    {
        return view('tarifas.form', $this->catalogos(new Tarifa(['moneda' => 'USD', 'por' => 'noche'])));
    }

    public function store(Request $request)
    {
        Tarifa::create($this->validar($request));

        return redirect()->route('tarifas.index')->with('success', 'Tarifa guardada.');
    }

    public function edit(Tarifa $tarifa)
    {
        return view('tarifas.form', $this->catalogos($tarifa));
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        $tarifa->update($this->validar($request));

        return redirect()->route('tarifas.index')->with('success', 'Tarifa actualizada.');
    }

    public function destroy(Tarifa $tarifa)
    {
        $tarifa->delete();

        return back()->with('success', 'Tarifa eliminada.');
    }

    /** Tarifas vigentes en una fecha, para sugerir el costo. Si dos coinciden, primero la más reciente. */
    public function sugerir(Request $request)
    {
        $datos = $request->validate([
            'fecha' => 'required|date',
            'habitacion_id' => 'nullable|integer',
            'proveedor_id' => 'nullable|integer',
        ]);

        $tarifas = Tarifa::query()->vigenteEl($datos['fecha'])
            ->when($datos['habitacion_id'] ?? null, fn ($q, $id) => $q->where('habitacion_id', $id))
            ->when(! ($datos['habitacion_id'] ?? null) && ($datos['proveedor_id'] ?? null), fn ($q) => $q->where('proveedor_id', $datos['proveedor_id']))
            ->when(! ($datos['habitacion_id'] ?? null) && ! ($datos['proveedor_id'] ?? null), fn ($q) => $q->whereRaw('1 = 0'))
            ->latest('id')->limit(10)->get();

        return response()->json(['tarifas' => $tarifas->map(fn (Tarifa $t) => [
            'concepto' => $t->concepto, 'precio' => (float) $t->precio, 'moneda' => $t->moneda, 'por' => Tarifa::POR[$t->por] ?? $t->por,
            'simbolo' => Servicio::MONEDAS[$t->moneda] ?? '', 'hasta' => $t->hasta->format('d/m/Y'),
        ])]);
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'tipo' => 'required|in:habitacion,proveedor',
            'habitacion_id' => ['nullable', 'required_if:tipo,habitacion', 'exists:habitacions,id'],
            'proveedor_id' => ['nullable', 'required_if:tipo,proveedor', 'exists:proveedors,id'],
            'concepto' => 'nullable|required_if:tipo,proveedor|string|max:255',
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
            'precio' => 'required|numeric|min:0',
            'moneda' => ['required', Rule::in(array_keys(Servicio::MONEDAS))],
            'por' => ['required', Rule::in(array_keys(Tarifa::POR))],
            'notas' => 'nullable|string|max:255',
        ], [
            'habitacion_id.required_if' => 'Elige el hotel y la habitación.',
            'proveedor_id.required_if' => 'Elige el proveedor.',
            'concepto.required_if' => 'Indica qué servicio cobra a este precio.',
            'hasta.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
        ]);

        $esHabitacion = $datos['tipo'] === 'habitacion';
        unset($datos['tipo']);

        return array_merge($datos, $esHabitacion
            ? ['proveedor_id' => null, 'concepto' => null, 'por' => 'noche']
            : ['habitacion_id' => null]);
    }

    private function catalogos(Tarifa $tarifa): array
    {
        return [
            'tarifa' => $tarifa,
            'hoteles' => Hotel::with(['habitaciones' => fn ($q) => $q->orderBy('tipo')])->orderBy('nombre')->get(),
            'proveedores' => Proveedor::orderBy('nombre')->get(['id', 'nombre']),
        ];
    }
}
