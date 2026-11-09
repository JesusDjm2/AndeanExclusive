<?php

namespace App\Support;

use App\Models\Programa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Copia un viaje a otro con sus días, hoteles, servicios y precios, corriendo las fechas.
 * No copia lo que es de ese cliente o de esa reserva: pasajeros, pagos, confirmaciones ni costos reales.
 */
class CopiaDePrograma
{
    /** Fecha de referencia de las plantillas: lejos de cualquier listado o estadística. */
    public const BASE_PLANTILLA = '2000-01-03';

    public static function copiar(Programa $origen, array $datos): Programa
    {
        return DB::transaction(function () use ($origen, $datos) {
            $origen->loadMissing(['agentes', 'dias', 'servicios.estadia', 'habitacionesFechas']);
            $desplazar = self::desplazamiento($origen, $datos['inicio'] ?? null);

            $copia = Programa::create([
                'nombre' => $datos['nombre'],
                'codigo' => $datos['codigo'] ?? null,
                'estado' => 'cotizacion',
                'plantilla' => $datos['plantilla'] ?? false,
                'cliente_id' => $datos['cliente_id'] ?? null,
                'inicio' => $desplazar($origen->inicio),
                'fin' => $desplazar($origen->fin),
                'lang' => $origen->lang,
                'moneda' => $origen->moneda ?? 'USD',
                'cantidad_pax' => $origen->cantidad_pax ?? 1,
                'precioAdulto' => $origen->precioAdulto,
                'precioChild' => $origen->precioChild,
                'porcentaje_ganancia' => $origen->porcentaje_ganancia,
                'porcentaje_igv' => $origen->porcentaje_igv,
                'tipo_cambio' => $origen->tipo_cambio,
                'suplemento_individual' => $origen->suplemento_individual,
                'habitaciones_individuales' => $origen->habitaciones_individuales,
                'presentacion' => $origen->presentacion,
                'portada' => $origen->portada,
            ]);
            $copia->agentes()->sync($origen->agentes->pluck('id'));

            foreach ($origen->dias as $dia) {
                $copia->dias()->create([
                    'fecha' => $desplazar($dia->fecha),
                    'titulo' => $dia->titulo,
                    'descripcion' => $dia->descripcion,
                    'es_libre' => (bool) $dia->es_libre,
                ]);
            }

            foreach ($origen->habitacionesFechas as $estadia) {
                $copia->habitacionesFechas()->create([
                    'habitacion_id' => $estadia->habitacion_id, 'cantidad' => $estadia->cantidad, 'costo_noche' => $estadia->costo_noche, 'moneda' => $estadia->moneda,
                    'fecha_ingreso' => $desplazar($estadia->fecha_ingreso), 'fecha_salida' => $desplazar($estadia->fecha_salida),
                ]);
            }

            // Los servicios de hotel los vuelve a generar el alojamiento; los cancelados no se repiten.
            foreach ($origen->servicios as $servicio) {
                if ($servicio->estadia || $servicio->estado === 'cancelado') {
                    continue;
                }
                $copia->servicios()->create([
                    'fecha' => $desplazar($servicio->fecha), 'hora' => $servicio->hora, 'tipo' => $servicio->tipo, 'titulo' => $servicio->titulo,
                    'descripcion' => $servicio->descripcion, 'punto_encuentro' => $servicio->punto_encuentro, 'notas' => $servicio->notas,
                    'visible_cliente' => $servicio->visible_cliente, 'proveedor_id' => $servicio->proveedor_id,
                    'estado' => $servicio->proveedor_id ? 'pendiente' : 'sin_asignar',
                    'costo_estimado' => $servicio->costo_estimado ?? $servicio->costo, 'moneda' => $servicio->moneda, 'costo_por' => $servicio->costo_por,
                ]);
            }

            AlojamientoEnServicios::sincronizar($copia);

            return $copia;
        });
    }

    /** Función que corre una fecha según el nuevo inicio. Sin fechas en el origen o sin nuevo inicio, no hay nada que correr. */
    private static function desplazamiento(Programa $origen, ?string $inicio): callable
    {
        if (! $origen->inicio || ! $inicio) {
            return fn ($fecha) => $origen->inicio ? null : ($fecha ? Carbon::parse($fecha)->toDateString() : null);
        }

        $dias = (int) Carbon::parse($origen->inicio)->startOfDay()->diffInDays(Carbon::parse($inicio)->startOfDay(), false);

        return fn ($fecha) => $fecha ? Carbon::parse($fecha)->addDays($dias)->toDateString() : null;
    }
}
