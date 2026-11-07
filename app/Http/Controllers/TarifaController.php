<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Tarifario: lo que cobra cada hotel y proveedor (tarifas fijas estándar o por temporada). Sugiere el costo al cotizar y al reservar. */
class TarifaController extends Controller
{
    public function index(Request $request)
    {
        $hoy = now()->toDateString();
        $vista = in_array($request->input('vista'), ['vigentes', 'fijas', 'vencidas', 'todas'], true) ? $request->input('vista') : 'vigentes';
        $categoriaFiltro = $request->input('categoria');

        $query = Tarifa::query()->with(['habitacion.hotel', 'proveedor.categoria'])
            ->when($vista === 'vigentes', fn ($q) => $q->where(fn ($sub) => $sub->whereDate('hasta', '>=', $hoy)->orWhereNull('hasta')->orWhere('es_fija', true)))
            ->when($vista === 'fijas', fn ($q) => $q->where(fn ($sub) => $sub->whereNull('desde')->orWhere('es_fija', true)))
            ->when($vista === 'vencidas', fn ($q) => $q->whereNotNull('hasta')->where('es_fija', false)->whereDate('hasta', '<', $hoy))
            ->when($categoriaFiltro === 'hotel', fn ($q) => $q->whereNotNull('habitacion_id'))
            ->when($categoriaFiltro && is_numeric($categoriaFiltro), fn ($q) => $q->whereHas('proveedor', fn ($p) => $p->where('categoria_id', (int) $categoriaFiltro)))
            ->when($request->filled('q'), function ($q) use ($request) {
                $texto = '%' . addcslashes(trim((string) $request->input('q')), '%_\\') . '%';
                $q->where(fn ($w) => $w->where('concepto', 'like', $texto)
                    ->orWhere('unidad_detalle', 'like', $texto)
                    ->orWhereHas('proveedor', fn ($p) => $p->where('nombre', 'like', $texto))
                    ->orWhereHas('habitacion', fn ($h) => $h->where('tipo', 'like', $texto)->orWhereHas('hotel', fn ($o) => $o->where('nombre', 'like', $texto))));
            });

        $tarifas = (clone $query)->conPrioridadTemporada()->paginate(30)->withQueryString();
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('tarifas.index', compact('tarifas', 'vista', 'categorias', 'categoriaFiltro'));
    }

    public function create(Request $request)
    {
        $tarifa = new Tarifa([
            'moneda' => 'USD',
            'por' => $request->input('por', 'grupo'),
            'es_fija' => true,
        ]);

        if ($request->filled('proveedor_id')) {
            $tarifa->proveedor_id = $request->integer('proveedor_id');
        }
        if ($request->filled('habitacion_id')) {
            $tarifa->habitacion_id = $request->integer('habitacion_id');
            $tarifa->por = 'noche';
        }

        $retorno = $request->input('retorno');

        return view('tarifas.form', array_merge($this->catalogos($tarifa), compact('retorno')));
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $tarifa = Tarifa::create($datos);

        if ($request->input('retorno') === 'proveedor' && $tarifa->proveedor_id) {
            return redirect()->route('proveedors.show', $tarifa->proveedor_id)->with('success', 'Tarifa guardada en el catálogo del proveedor.');
        }

        return redirect()->route('tarifas.index')->with('success', 'Tarifa guardada con éxito.');
    }

    public function edit(Request $request, Tarifa $tarifa)
    {
        $retorno = $request->input('retorno');

        return view('tarifas.form', array_merge($this->catalogos($tarifa), compact('retorno')));
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        $datos = $this->validar($request);
        $tarifa->update($datos);

        if ($request->input('retorno') === 'proveedor' && $tarifa->proveedor_id) {
            return redirect()->route('proveedors.show', $tarifa->proveedor_id)->with('success', 'Tarifa del proveedor actualizada.');
        }

        return redirect()->route('tarifas.index')->with('success', 'Tarifa actualizada.');
    }

    public function destroy(Request $request, Tarifa $tarifa)
    {
        $proveedorId = $tarifa->proveedor_id;
        $tarifa->delete();

        if ($request->input('retorno') === 'proveedor' && $proveedorId) {
            return redirect()->route('proveedors.show', $proveedorId)->with('success', 'Tarifa eliminada del proveedor.');
        }

        return back()->with('success', 'Tarifa eliminada.');
    }

    /** Tarifas vigentes en una fecha o fijas estándar, para sugerir el costo al cotizar o reservar. */
    public function sugerir(Request $request)
    {
        $datos = $request->validate([
            'fecha' => 'nullable|date',
            'habitacion_id' => 'nullable|integer',
            'proveedor_id' => 'nullable|integer',
        ]);

        $query = Tarifa::query();

        if (! empty($datos['habitacion_id'])) {
            $query->where('habitacion_id', $datos['habitacion_id']);
        } elseif (! empty($datos['proveedor_id'])) {
            $query->where('proveedor_id', $datos['proveedor_id']);
        } else {
            return response()->json(['tarifas' => []]);
        }

        $fueraDeTemporada = false;

        if (! empty($datos['fecha'])) {
            $queryFecha = (clone $query)->vigenteEl($datos['fecha']);
            $tarifas = $queryFecha->conPrioridadTemporada()->latest('id')->limit(25)->get();
            if ($tarifas->isEmpty()) {
                // Sin tarifa para esa fecha se muestran las de otras temporadas, marcadas para no cotizar con un precio que no aplica.
                $tarifas = $query->conPrioridadTemporada()->latest('id')->limit(25)->get();
                $fueraDeTemporada = true;
            }
        } else {
            $tarifas = $query->where(function ($q) {
                $q->where('es_fija', true)
                  ->orWhereNull('hasta')
                  ->orWhere('hasta', '>=', now()->startOfYear()->toDateString());
            })->conPrioridadTemporada()->latest('id')->limit(25)->get();

            if ($tarifas->isEmpty()) {
                $tarifas = $query->conPrioridadTemporada()->latest('id')->limit(25)->get();
            }
        }

        return response()->json(['tarifas' => $tarifas->map(fn (Tarifa $t) => [
            'id' => $t->id,
            'concepto' => $t->concepto,
            'tipo_servicio' => $t->tipo_servicio,
            'unidad_detalle' => $t->unidad_detalle,
            'precio' => (float) $t->precio,
            'moneda' => $t->moneda,
            'por' => Tarifa::POR[$t->por] ?? $t->por,
            'por_raw' => $t->por,
            'simbolo' => Servicio::MONEDAS[$t->moneda] ?? '$',
            'es_fija' => (bool) ($t->es_fija || (! $t->desde && ! $t->hasta)),
            'temporada' => $t->temporada_texto,
            'hasta' => $t->hasta?->format('d/m/Y'),
            'fuera_de_temporada' => $fueraDeTemporada,
        ])]);
    }

    private function validar(Request $request): array
    {
        $esFija = $request->boolean('es_fija') || (! $request->filled('desde') && ! $request->filled('hasta'));

        $datos = $request->validate([
            'tipo' => 'required|in:habitacion,proveedor',
            'habitacion_id' => ['nullable', 'required_if:tipo,habitacion', 'exists:habitacions,id'],
            'proveedor_id' => ['nullable', 'required_if:tipo,proveedor', 'exists:proveedors,id'],
            'concepto' => 'nullable|required_if:tipo,proveedor|string|max:255',
            'tipo_servicio' => 'nullable|string|max:50',
            'unidad_detalle' => 'nullable|string|max:100',
            'es_fija' => 'nullable|boolean',
            'desde' => $esFija ? 'nullable|date' : 'required|date',
            'hasta' => $esFija ? 'nullable|date' : 'required|date|after_or_equal:desde',
            'precio' => 'required|numeric|min:0',
            'moneda' => ['required', Rule::in(array_keys(Servicio::MONEDAS))],
            'por' => ['required', Rule::in(array_keys(Tarifa::POR))],
            'notas' => 'nullable|string|max:255',
        ], [
            'habitacion_id.required_if' => 'Elige el hotel y la habitación.',
            'proveedor_id.required_if' => 'Elige el proveedor.',
            'concepto.required_if' => 'Indica qué servicio cobra a este precio.',
            'desde.required' => 'Indica la fecha de inicio de la temporada o marca la tarifa como fija.',
            'hasta.required' => 'Indica la fecha de término de la temporada o marca la tarifa como fija.',
            'hasta.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
        ]);

        $esHabitacion = $datos['tipo'] === 'habitacion';
        unset($datos['tipo']);

        $datos['es_fija'] = $esFija;
        if ($esFija) {
            $datos['desde'] = null;
            $datos['hasta'] = null;
        }

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
