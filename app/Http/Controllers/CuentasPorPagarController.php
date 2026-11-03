<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** Lo que se debe a proveedores: servicios con costo aún no pagados, agrupados por proveedor; y su registro de pago. */
class CuentasPorPagarController extends Controller
{
    /** Filtro => etiqueta. El servicio se paga, a más tardar, el día que se presta. */
    public const FILTROS = [
        'pendientes' => 'Todo lo pendiente',
        'solicitadas' => 'Pago solicitado',
        'vencidas' => 'Vencidas',
        'semana' => 'Próximos 7 días',
        'pagadas' => 'Pagadas',
    ];

    public function index(Request $request)
    {
        $filtro = array_key_exists((string) $request->input('filtro'), self::FILTROS) ? $request->input('filtro') : 'pendientes';
        $hoy = Carbon::today();

        $base = Servicio::query()
            ->deudas()
            ->when($request->filled('proveedor_id'), fn ($q) => $q->where('proveedor_id', $request->integer('proveedor_id')));

        $aplicar = [
            'pendientes' => fn ($q) => $q->whereNull('pagado_at'),
            'vencidas' => fn ($q) => $q->whereNull('pagado_at')->whereDate('fecha', '<', $hoy),
            'semana' => fn ($q) => $q->whereNull('pagado_at')->whereDate('fecha', '>=', $hoy)->whereDate('fecha', '<=', $hoy->copy()->addDays(7)),
            'pagadas' => fn ($q) => $q->whereNotNull('pagado_at'),
        ];
        // Lo solicitado por Reservas puede ser un adelanto: aún no es deuda (servicio sin confirmar), pero Contabilidad debe verlo.
        $consulta = fn (string $clave) => $clave === 'solicitadas'
            ? Servicio::query()->solicitudesPago()->when($request->filled('proveedor_id'), fn ($q) => $q->where('proveedor_id', $request->integer('proveedor_id')))
            : $aplicar[$clave](clone $base);
        $conteos = collect(self::FILTROS)->map(fn ($etiqueta, $clave) => $consulta($clave)->count());

        $todos = $consulta($filtro)
            ->with(['programa.paxs', 'programa.cliente', 'proveedor.categoria', 'pagos'])
            ->when($filtro === 'pagadas', fn ($q) => $q->orderByDesc('pagado_at'), fn ($q) => $q->orderBy('fecha'))
            ->limit(1000)
            ->get();

        // Pestañas por tipo de proveedor (categoría): cantidad y totales por moneda de cada una.
        $claveCategoria = fn (Servicio $s) => (string) ($s->proveedor?->categoria_id ?? 'sin');
        $categorias = $todos->groupBy($claveCategoria)
            ->map(fn ($items, $clave) => [
                'clave' => (string) $clave,
                'nombre' => $items->first()->proveedor?->categoria?->nombre ?? 'Sin categoría',
                'cantidad' => $items->count(),
                'totales' => $items->groupBy('moneda')->map(fn ($porMoneda) => $porMoneda->sum(fn (Servicio $s) => $s->pagado_at ? $s->importe : $s->saldo_proveedor)),
            ])
            ->sortBy(fn ($c) => $c['clave'] === 'sin' ? 'zzz' : $c['nombre'])
            ->values();
        $categoria = $categorias->firstWhere('clave', (string) $request->input('categoria'))['clave']
            ?? $categorias->first()['clave'] ?? null;
        $servicios = $todos->filter(fn (Servicio $s) => $claveCategoria($s) === $categoria)->values();

        // Agrupar por proveedor; totales por moneda (no se mezclan soles y dólares).
        $grupos = $servicios->groupBy(fn (Servicio $s) => $s->proveedor_id ?? 0)
            ->map(fn ($items) => [
                'proveedor' => $items->first()->proveedor,
                'servicios' => $items,
                'totales' => $items->groupBy('moneda')->map(fn ($porMoneda) => $porMoneda->sum(fn (Servicio $s) => $s->pagado_at ? $s->importe : $s->saldo_proveedor)),
            ])
            ->sortBy(fn ($g) => $g['proveedor']?->nombre ?? 'zzz');

        // Totales: lo que falta pagar (saldo) en pendientes; lo pagado en la pestaña Pagadas.
        $monto = fn (Servicio $s) => $s->pagado_at ? $s->importe : $s->saldo_proveedor;
        $totales = $todos->groupBy('moneda')->map(fn ($porMoneda) => $porMoneda->sum($monto));
        $proveedores = Proveedor::query()->whereHas('servicios', fn ($q) => $q->deudas())->orderBy('nombre')->get(['id', 'nombre']);

        return view('cuentas.index', compact('filtro', 'conteos', 'grupos', 'totales', 'proveedores', 'categorias', 'categoria'));
    }

    /** Paga el saldo de los servicios elegidos (un mismo pago/transferencia puede cubrir varios servicios del proveedor). */
    public function pagar(Request $request)
    {
        $datos = $request->validate([
            'servicios' => 'required|array',
            'servicios.*' => 'integer|exists:servicios,id',
            'fecha' => 'required|date|before_or_equal:today',
            'metodo' => ['nullable', \Illuminate\Validation\Rule::in(array_keys(\App\Models\ProveedorPago::METODOS))],
            'referencia' => 'nullable|string|max:255',
        ], ['servicios.required' => 'Marca al menos un servicio.']);

        $n = 0;
        Servicio::query()->whereIn('id', $datos['servicios'])->whereNull('pagado_at')->where('estado', '!=', 'cancelado')->where('costo', '>', 0)->with('programa.paxs', 'pagos')->get()
            ->each(function (Servicio $servicio) use ($datos, &$n) {
                $saldo = $servicio->saldo_proveedor;
                if ($saldo <= 0) {
                    return;
                }
                $servicio->pagos()->create([
                    'fecha' => $datos['fecha'], 'monto' => $saldo, 'moneda' => $servicio->moneda,
                    'metodo' => $datos['metodo'] ?? null, 'referencia' => $datos['referencia'] ?? null, 'user_id' => auth()->id(),
                ]);
                $n++;
            });

        return redirect()->back()->with('success', $n === 1 ? 'Se registró el pago de 1 servicio.' : "Se registró el pago de {$n} servicios.");
    }

    /** Anula todos los pagos de un servicio con su motivo (quedan en su historial). */
    public function anular(Request $request, Servicio $servicio)
    {
        ['reglas' => $reglas, 'mensajes' => $mensajes] = \App\Models\ProveedorPago::reglasMotivo();
        $motivo = $request->validate($reglas, $mensajes)['motivo'];
        $servicio->pagos()->get()->each(fn ($pago) => $pago->anular($motivo));

        return redirect()->back()->with('success', "Se anularon los pagos de «{$servicio->titulo}».");
    }
}
