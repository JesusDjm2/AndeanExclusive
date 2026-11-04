<?php

namespace App\Support;

use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Programa;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Cifras de gestión de un periodo: programas confirmados que inician entre dos fechas, todo en dólares.
 * El costo es el real de cada proveedor o, si aún no hay, el estimado.
 */
class Reportes
{
    public function __construct(private Carbon $desde, private Carbon $hasta)
    {
    }

    private ?Collection $filas = null;

    /** Una fila por programa, con lo que hace falta para agrupar. */
    public function filas(): Collection
    {
        return $this->filas ??= Programa::query()
            ->where('estado', 'confirmado')
            ->whereDate('inicio', '>=', $this->desde)->whereDate('inicio', '<=', $this->hasta)
            ->with(['paxs', 'pagos', 'servicios.pagos', 'servicios.proveedor', 'agentes:id,nombre', 'cliente'])
            ->orderBy('inicio')->get()
            ->map(function (Programa $p) {
                $costeo = $p->costeo();
                $cobro = $p->cobranza();

                return [
                    'programa' => $p, 'pasajeros' => $p->paxs->count(), 'venta' => $costeo['venta'], 'cobrado' => $cobro['pagado'],
                    'por_cobrar' => max(0.0, $cobro['saldo']), 'costo' => $costeo['costo'], 'margen' => $costeo['margen'],
                ];
            });
    }

    public function totales(): array
    {
        return $this->sumar($this->filas());
    }

    public function porMes(): Collection
    {
        return $this->agrupar(fn ($f) => [Carbon::parse($f['programa']->inicio)->format('Y-m')])
            ->map(fn ($fila, $clave) => ['etiqueta' => ucfirst(Carbon::parse($clave . '-01')->locale('es')->isoFormat('MMMM YYYY'))] + $fila)->sortKeys();
    }

    /** Un programa con dos agentes cuenta para los dos: esta tabla no se suma. */
    public function porAgente(): Collection
    {
        return $this->agrupar(fn ($f) => $f['programa']->agentes->pluck('nombre')->all() ?: ['Sin agente'])
            ->map(fn ($fila, $clave) => ['etiqueta' => $clave] + $fila)->sortByDesc('venta');
    }

    public function porOrigen(): Collection
    {
        return $this->agrupar(fn ($f) => [$f['programa']->cliente?->origen ?: 'sin_dato'])
            ->map(fn ($fila, $clave) => ['etiqueta' => Cliente::ORIGENES[$clave] ?? 'Sin dato'] + $fila)->sortByDesc('venta');
    }

    public function porProveedor(): Collection
    {
        return $this->filas()->flatMap(fn ($f) => $f['programa']->servicios->where('estado', '!=', 'cancelado')->whereNotNull('proveedor_id')
            ->each(fn (Servicio $s) => $s->setRelation('programa', $f['programa'])))
            ->groupBy('proveedor_id')
            ->map(function (Collection $servicios) {
                $usd = fn (Servicio $s, float $monto) => $s->moneda === 'PEN' ? $monto / $s->programa->tipoCambio() : $monto;
                $costo = $servicios->sum(fn (Servicio $s) => $s->importe_previsto_usd);
                $pagado = $servicios->sum(fn (Servicio $s) => $usd($s, (float) $s->pagado_proveedor));

                return ['etiqueta' => $servicios->first()->proveedor?->nombre ?? '—', 'servicios' => $servicios->count(), 'costo' => $costo, 'pagado' => $pagado, 'por_pagar' => max(0.0, $costo - $pagado)];
            })
            ->sortByDesc('costo')->values();
    }

    /** Consultas recibidas en el periodo y en qué terminaron. */
    public function consultas(): array
    {
        $consultas = Consulta::query()->whereDate('created_at', '>=', $this->desde)->whereDate('created_at', '<=', $this->hasta)->get(['estado', 'origen', 'motivo_perdida']);
        $ganadas = $consultas->where('estado', 'ganada')->count();

        return [
            'recibidas' => $consultas->count(),
            'estados' => collect(Consulta::ESTADOS)->map(fn ($etiqueta, $estado) => ['etiqueta' => $etiqueta, 'cantidad' => $consultas->where('estado', $estado)->count()])->values(),
            'conversion' => $consultas->count() ? $ganadas / $consultas->count() * 100 : 0.0,
            'motivos' => $consultas->where('estado', 'perdida')->whereNotNull('motivo_perdida')->countBy('motivo_perdida')
                ->map(fn ($cantidad, $motivo) => ['etiqueta' => Consulta::MOTIVOS_PERDIDA[$motivo] ?? $motivo, 'cantidad' => $cantidad])->sortByDesc('cantidad')->values(),
        ];
    }

    private function agrupar(callable $claves): Collection
    {
        $grupos = [];
        foreach ($this->filas() as $fila) {
            foreach ($claves($fila) as $clave) {
                $grupos[$clave][] = $fila;
            }
        }

        return collect($grupos)->map(fn ($filas) => $this->sumar(collect($filas)));
    }

    private function sumar(Collection $filas): array
    {
        $venta = (float) $filas->sum('venta');
        $margen = (float) $filas->sum('margen');

        return [
            'programas' => $filas->count(), 'pasajeros' => (int) $filas->sum('pasajeros'), 'venta' => $venta, 'cobrado' => (float) $filas->sum('cobrado'),
            'por_cobrar' => (float) $filas->sum('por_cobrar'), 'costo' => (float) $filas->sum('costo'), 'margen' => $margen,
            'margen_pct' => $venta > 0 ? $margen / $venta * 100 : 0.0,
        ];
    }
}
