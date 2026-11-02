<?php

namespace App\Http\Controllers;

use App\Models\HabitacionFecha;
use App\Models\Programa;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** Hoja de operaciones diaria: todos los servicios, ingresos y salidas de hotel de un día, más lo que falta confirmar. */
class OperacionesController extends Controller
{
    /** Días hacia adelante en los que se revisan servicios sin confirmar. */
    public const DIAS_POR_CONFIRMAR = 14;

    public function index(Request $request)
    {
        $fecha = rescue(fn () => Carbon::parse($request->input('fecha', 'today'))->startOfDay(), Carbon::today(), false);

        $servicios = Servicio::query()
            ->operables()
            ->whereDate('fecha', $fecha)
            ->with(['programa.paxs', 'programa.cliente', 'proveedor', 'pagos'])
            ->orderByRaw('hora IS NULL, hora')
            ->orderBy('id')
            ->get();

        $estadias = HabitacionFecha::query()
            ->where(fn ($q) => $q->whereDate('fecha_ingreso', $fecha)->orWhereDate('fecha_salida', $fecha))
            ->whereHas('programa', fn ($q) => $q->where('estado', 'confirmado'))
            ->with(['habitacion.hotel', 'programa.paxs'])
            ->get();

        $vuelos = Programa::query()->where('estado', 'confirmado')
            ->where(fn ($q) => $q->whereDate('llegada_at', $fecha)->orWhereDate('salida_at', $fecha))
            ->with('paxs')->get()
            ->flatMap(fn (Programa $p) => array_values(array_filter([
                $p->llegada_at?->isSameDay($fecha) ? ['hora' => $p->llegada_at->format('H:i'), 'tipo' => 'Llegada', 'vuelo' => $p->llegada_vuelo, 'programa' => $p] : null,
                $p->salida_at?->isSameDay($fecha) ? ['hora' => $p->salida_at->format('H:i'), 'tipo' => 'Salida', 'vuelo' => $p->salida_vuelo, 'programa' => $p] : null,
            ])))->sortBy('hora')->values();

        $porConfirmar = Servicio::query()
            ->operables()
            ->abiertos()
            ->whereDate('fecha', '>=', Carbon::today())
            ->whereDate('fecha', '<=', Carbon::today()->addDays(self::DIAS_POR_CONFIRMAR))
            ->with(['programa', 'proveedor'])
            ->orderBy('fecha')
            ->orderByRaw('hora IS NULL, hora')
            ->get();

        $resumen = [
            'servicios' => $servicios->count(),
            'programas' => $servicios->pluck('programa_id')->merge($estadias->pluck('programa_id'))->unique()->count(),
            'pax' => $servicios->pluck('programa')->merge($estadias->pluck('programa'))->unique('id')->sum(fn ($p) => $p->paxs->count()),
            'abiertos' => $servicios->whereIn('estado', Servicio::ABIERTOS)->count(),
        ];

        return view('operaciones.index', compact('fecha', 'servicios', 'estadias', 'vuelos', 'porConfirmar', 'resumen'));
    }
}
