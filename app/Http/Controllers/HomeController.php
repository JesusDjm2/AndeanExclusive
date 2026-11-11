<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Enblog;
use App\Models\Esblog;
use App\Models\Estour;
use App\Models\Mes;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\Servicio;
use App\Models\Tarea;
use App\Support\Recordatorios;
use App\Http\Controllers\OperacionesController;
use App\Models\Tour;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = Carbon::today();
        $user = auth()->user();
        $isBranding = $user && $user->hasRole('branding');

        // Salud del contenido web: piezas sin su versión en el otro idioma, medios y taxonomías.
        $content = [
            'toursEn' => Tour::count(),
            'toursEs' => Estour::count(),
            'toursMissingEs' => Tour::doesntHave('estour')->count(),
            'blogsEn' => Enblog::count(),
            'blogsEs' => Esblog::count(),
            'blogsMissingEs' => Enblog::doesntHave('esblog')->count(),
            'imagenes' => \App\Models\Imagen::count(),
            'categoriasEn' => \App\Models\TourCategory::count(),
            'categoriasEs' => \App\Models\EsCategoria::count(),
            'tagsEn' => \App\Models\Entag::count(),
            'tagsEs' => \App\Models\Estag::count(),
            'lastTour' => Tour::latest('updated_at')->first(['nombre', 'updated_at']),
            'lastBlog' => Enblog::latest('updated_at')->first(['nombre', 'updated_at']),
            'lastImagen' => \App\Models\Imagen::latest('updated_at')->first(['img', 'updated_at']),
        ];

        // El rol branding está estrictamente limitado a la administración de contenidos públicos.
        // No ejecuta consultas ni recibe datos confidenciales de la operación de viajes o finanzas.
        if ($isBranding) {
            $ongoing = collect();
            $upcoming = collect();
            $stats = [
                'ongoing' => 0,
                'travelersOnTrip' => 0,
                'next30' => 0,
                'thisMonth' => 0,
                'year' => 0,
                'paxYear' => 0,
                'cotizaciones' => 0,
                'saldoPendiente' => 0,
            ];
            $cobros = collect();
            $operacion = [
                'hoy' => 0,
                'manana' => 0,
                'porConfirmar' => 0,
                'pagosVencidos' => 0,
                'pagosSolicitados' => 0,
            ];
            $tareas = collect();
            $avisosCliente = collect();
            $consultasNuevas = 0;
            $months = collect();
            $anios = collect();
            $meses = collect();
            $ultimosMovimientos = collect();

            return view('home', compact('ultimosMovimientos', 'today', 'ongoing', 'upcoming', 'stats', 'months', 'content', 'anios', 'meses', 'cobros', 'operacion', 'tareas', 'avisosCliente', 'consultasNuevas', 'isBranding'));
        }

        $ongoing = Programa::query()
            ->withCount('paxs')
            ->whereDate('inicio', '<=', $today)
            ->whereDate('fin', '>=', $today)
            ->orderBy('fin')
            ->get();

        $upcoming = Programa::query()
            ->withCount('paxs')
            ->with('agentes:id,nombre')
            ->where('estado', '!=', 'cancelado')
            ->whereDate('inicio', '>', $today)
            ->orderBy('inicio')
            ->take(8)
            ->get();

        $stats = [
            'ongoing' => $ongoing->count(),
            'travelersOnTrip' => $ongoing->sum('paxs_count'),
            'next30' => Programa::query()->whereDate('inicio', '>', $today)->whereDate('inicio', '<=', $today->copy()->addDays(30))->count(),
            'thisMonth' => Programa::query()->whereYear('inicio', $today->year)->whereMonth('inicio', $today->month)->count(),
            'year' => Programa::query()->whereYear('inicio', $today->year)->count(),
            'paxYear' => Pax::query()->whereHas('programa', fn ($q) => $q->whereYear('inicio', $today->year))->count(),
        ];

        // Cobros: saldos vencidos o que vencen en los próximos 30 días (regla: saldo 60 días antes del viaje).
        $limiteCobro = $today->copy()->addDays(30);
        $cobros = Programa::query()
            ->with(['paxs', 'pagos'])
            ->where('estado', '!=', 'cancelado')
            ->whereDate('fin', '>=', $today)
            ->get()
            ->map(fn (Programa $programa) => ['programa' => $programa, 'cobro' => $programa->cobranza()])
            ->filter(fn ($item) => $item['cobro']['saldo'] > 0 && $item['cobro']['vencimientoSaldo']?->lte($limiteCobro))
            ->sortBy(fn ($item) => $item['cobro']['vencimientoSaldo'])
            ->values();
        $stats['cotizaciones'] = Programa::query()->where('estado', 'cotizacion')->whereDate('fin', '>=', $today)->count();
        $stats['saldoPendiente'] = $cobros->sum(fn ($item) => $item['cobro']['saldo']);

        // Operación: servicios de hoy, servicios sin confirmar y tareas vencidas o que vencen en 7 días.
        $operacion = [
            'hoy' => Servicio::query()->operables()->whereDate('fecha', $today)->count(),
            'manana' => Servicio::query()->operables()->whereDate('fecha', $today->copy()->addDay())->count(),
            'porConfirmar' => Servicio::query()->operables()->abiertos()
                ->whereDate('fecha', '>=', $today)->whereDate('fecha', '<=', $today->copy()->addDays(OperacionesController::DIAS_POR_CONFIRMAR))->count(),
            'pagosVencidos' => Servicio::query()->operables()->porPagar()->whereDate('fecha', '<', $today)->count(),
            'pagosSolicitados' => Servicio::query()->solicitudesPago()->count(),
        ];
        $tareas = Tarea::query()
            ->pendientes()
            ->whereNotNull('fecha_limite')
            ->whereDate('fecha_limite', '<=', $today->copy()->addDays(7))
            ->whereHas('programa', fn ($q) => $q->where('estado', '!=', 'cancelado')->whereDate('fin', '>=', $today))
            ->with('programa:id,nombre,codigo,token')
            ->orderBy('fecha_limite')
            ->get();

        // Correos que conviene enviar al cliente (los envía una persona del equipo desde la pestaña Pagos).
        $avisosCliente = Recordatorios::porEnviar();
        $consultasNuevas = \App\Models\Consulta::where('estado', 'nueva')->count();

        // Programas por mes del año en curso (gráfico de barras en CSS, sin librerías).
        $perMonth = Programa::query()
            ->whereYear('inicio', $today->year)
            ->get(['inicio'])
            ->countBy(fn ($programa) => (int) Carbon::parse($programa->inicio)->month);
        $months = collect(range(1, 12))->map(fn ($m) => [
            'label' => ucfirst(Carbon::create($today->year, $m, 1)->locale('es')->isoFormat('MMM')),
            'count' => $perMonth->get($m, 0),
            'current' => $m === $today->month,
        ]);

        $anios = Anio::query()->orderByDesc('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();

        $ultimosMovimientos = \App\Models\Movimiento::query()->with('programa:id,codigo')->latest()->orderByDesc('id')->limit(8)->get();

        return view('home', compact('ultimosMovimientos', 'today', 'ongoing', 'upcoming', 'stats', 'months', 'content', 'anios', 'meses', 'cobros', 'operacion', 'tareas', 'avisosCliente', 'consultasNuevas', 'isBranding'));
    }
}
