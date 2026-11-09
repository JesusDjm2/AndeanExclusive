<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Bandeja del área de reservas: todos los servicios a gestionar con los proveedores, por estado,
 * con su situación de pago y acciones rápidas (solicitar, confirmar, pagar, ver historial) e individuales o en lote.
 */
class ReservasController extends Controller
{
    /** Pestañas: clave => [etiqueta, estados que incluye]. */
    public const BANDEJAS = [
        'sin_asignar' => ['Sin asignar', ['sin_asignar']],
        'por_reservar' => ['Por reservar', ['pendiente']],
        'solicitados' => ['Solicitados', ['solicitado']],
        'confirmados' => ['Confirmados', ['confirmado']],
        'cancelados' => ['Cancelados', ['cancelado']],
    ];

    /** Periodo: clave => [etiqueta, días hacia adelante (null = sin límite)]. */
    public const PERIODOS = [
        '7' => ['Próximos 7 días', 7],
        '30' => ['Próximos 30 días', 30],
        '90' => ['Próximos 90 días', 90],
        'todos' => ['Todos los futuros', null],
        'pasados' => ['Ya prestados', null],
    ];

    public function index(Request $request)
    {
        $bandeja = array_key_exists((string) $request->input('bandeja'), self::BANDEJAS) ? $request->input('bandeja') : 'por_reservar';
        $periodo = array_key_exists((string) $request->input('periodo'), self::PERIODOS) ? $request->input('periodo') : '30';
        $hoy = Carbon::today();

        // Filtros comunes (periodo, proveedor, búsqueda) para contar cada bandeja con los mismos criterios.
        $base = Servicio::query()
            ->whereHas('programa', fn ($q) => $q->where('estado', '!=', 'cancelado')->where('plantilla', false))
            ->when($periodo === 'pasados',
                fn ($q) => $q->whereDate('fecha', '<', $hoy),
                fn ($q) => $q->whereDate('fecha', '>=', $hoy)
                    ->when(self::PERIODOS[$periodo][1], fn ($q, $dias) => $q->whereDate('fecha', '<=', $hoy->copy()->addDays($dias))))
            ->when($request->filled('proveedor_id'), fn ($q) => $request->input('proveedor_id') === 'sin'
                ? $q->whereNull('proveedor_id')
                : $q->where('proveedor_id', $request->integer('proveedor_id')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->input('q')) . '%';
                $q->where(fn ($w) => $w->where('titulo', 'like', $term)->orWhere('confirmacion', 'like', $term)
                    ->orWhereHas('programa', fn ($p) => $p->where('codigo', 'like', $term)->orWhere('nombre', 'like', $term)));
            });

        $conteos = collect(self::BANDEJAS)->map(fn ($b) => (clone $base)->whereIn('estado', $b[1])->count());

        // Sin bandeja elegida: abrir la primera con trabajo pendiente (por reservar → solicitados → confirmados).
        if (! $request->filled('bandeja')) {
            $bandeja = collect(['sin_asignar', 'por_reservar', 'solicitados', 'confirmados'])->first(fn ($b) => $conteos[$b] > 0) ?? 'sin_asignar';
        }

        $servicios = (clone $base)
            ->whereIn('estado', self::BANDEJAS[$bandeja][1])
            ->with(['programa.paxs', 'programa.cliente', 'proveedor', 'pagos'])->withCount('mensajes')
            ->when($periodo === 'pasados', fn ($q) => $q->orderByDesc('fecha'), fn ($q) => $q->orderBy('fecha'))
            ->orderByRaw('hora IS NULL, hora')
            ->paginate(40)
            ->withQueryString();

        // Urgentes: sin confirmar y faltan 3 días o menos (el aviso lleva a la bandeja donde están).
        $urgentesPorEstado = Servicio::query()->operables()->abiertos()
            ->whereDate('fecha', '>=', $hoy)->whereDate('fecha', '<=', $hoy->copy()->addDays(3))
            ->selectRaw('estado, COUNT(*) as total')->groupBy('estado')->pluck('total', 'estado');
        $urgentes = (int) $urgentesPorEstado->sum();
        $bandejaUrgentes = match (true) {
            ($urgentesPorEstado['sin_asignar'] ?? 0) > 0 => 'sin_asignar',
            ($urgentesPorEstado['pendiente'] ?? 0) > 0 => 'por_reservar',
            default => 'solicitados',
        };

        $proveedores = Proveedor::query()->with('categoria:id,nombre')->orderBy('nombre')->get(['id', 'nombre', 'categoria_id']);

        return view('reservas.index', compact('bandeja', 'periodo', 'conteos', 'servicios', 'urgentes', 'bandejaUrgentes', 'proveedores'));
    }

    /** Acción en lote sobre varios servicios: marcarlos como solicitados o cancelados. */
    public function lote(Request $request)
    {
        $datos = $request->validate([
            'servicios' => 'required|array',
            'servicios.*' => 'integer|exists:servicios,id',
            'accion' => 'required|in:solicitado,cancelado,pendiente',
        ], ['servicios.required' => 'Marca al menos un servicio.']);

        $servicios = Servicio::query()->whereIn('id', $datos['servicios'])->get();
        // Solicitar exige proveedor: los que no lo tienen se omiten y se avisa.
        [$aplicables, $sinProveedor] = $servicios->partition(fn (Servicio $s) => $s->proveedor_id || ! in_array($datos['accion'], Servicio::REQUIEREN_PROVEEDOR, true));
        $aplicables->each(fn (Servicio $s) => $s->update(['estado' => $datos['accion']]));

        $mensaje = $aplicables->count() . ' ' . ($aplicables->count() === 1 ? 'servicio pasó' : 'servicios pasaron') . ' a «' . Servicio::ESTADOS[$datos['accion']] . '».';
        if ($sinProveedor->isNotEmpty()) {
            $mensaje .= $sinProveedor->count() === 1 ? ' 1 servicio sin proveedor no se cambió: asígnale uno primero.' : ' ' . $sinProveedor->count() . ' servicios sin proveedor no se cambiaron: asígnales uno primero.';
        }

        return back()->with($aplicables->isEmpty() ? 'error' : 'success', $mensaje);
    }
}
