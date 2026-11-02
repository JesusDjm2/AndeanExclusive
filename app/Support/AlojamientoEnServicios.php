<?php

namespace App\Support;

use App\Models\Categoria;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Programa;
use App\Models\Proveedor;
use App\Models\Servicio;

/**
 * Mantiene un servicio por cada estadía con fechas: el hotel suma al margen, Reservas lo confirma y Contabilidad lo paga.
 * Si el programa ya tiene costos de hotel cargados a mano, no se generan otros para no contarlos dos veces.
 */
class AlojamientoEnServicios
{
    public static function sincronizar(Programa $programa): void
    {
        $estadias = $programa->habitacionesFechas()->with(['habitacion.hotel', 'servicio'])->get();
        $hayManuales = $programa->servicios()->where('tipo', 'alojamiento')
            ->whereNotIn('id', $estadias->pluck('servicio_id')->filter())->exists();

        foreach ($estadias as $estadia) {
            if (! $estadia->noches) {
                self::quitar($estadia);
            } elseif ($estadia->servicio) {
                self::actualizar($estadia);
            } elseif (! $hayManuales) {
                self::crear($estadia);
            }
        }
    }

    /** Al quitar una estadía: su servicio se va con ella, salvo que ya tenga pagos (los debe anular Contabilidad). */
    public static function quitar(HabitacionFecha $estadia): bool
    {
        $servicio = $estadia->servicio;
        if (! $servicio) {
            return true;
        }
        if ($servicio->pagos()->exists()) {
            return false;
        }

        $estadia->forceFill(['servicio_id' => null])->saveQuietly();
        $servicio->delete();

        return true;
    }

    private static function crear(HabitacionFecha $estadia): void
    {
        $importe = self::importe($estadia);
        $servicio = $estadia->programa->servicios()->create([
            'tipo' => 'alojamiento',
            'fecha' => $estadia->fecha_ingreso,
            'titulo' => self::titulo($estadia),
            'proveedor_id' => self::proveedorDe($estadia->habitacion?->hotel)?->id,
            'estado' => 'pendiente',
            'visible_cliente' => false,
            'costo_estimado' => $importe,
            'costo' => $importe,
            'moneda' => $estadia->moneda ?: 'USD',
            'costo_por' => 'grupo',
        ]);

        $estadia->forceFill(['servicio_id' => $servicio->id])->saveQuietly();
    }

    private static function actualizar(HabitacionFecha $estadia): void
    {
        $servicio = $estadia->servicio;
        $importe = self::importe($estadia);
        $cambios = ['fecha' => $estadia->fecha_ingreso, 'titulo' => self::titulo($estadia), 'costo_estimado' => $importe];

        // El costo real sigue al estimado hasta que Reservas pone el del hotel o ya hay pagos.
        $sinTocar = $servicio->costo === null || (float) $servicio->costo === (float) $servicio->costo_estimado;
        if ($sinTocar && $servicio->pagos()->doesntExist()) {
            $cambios += ['costo' => $importe, 'moneda' => $estadia->moneda ?: 'USD'];
        }

        $servicio->update($cambios);
    }

    private static function importe(HabitacionFecha $estadia): ?float
    {
        return $estadia->costo_noche === null ? null : round((float) $estadia->costo_noche * $estadia->cantidad * $estadia->noches, 2);
    }

    private static function titulo(HabitacionFecha $estadia): string
    {
        $noches = $estadia->noches === 1 ? '1 noche' : "{$estadia->noches} noches";

        return "Alojamiento: {$estadia->habitacion?->hotel?->nombre} · {$estadia->etiqueta} · {$noches}";
    }

    /** El hotel se paga como un proveedor más: la primera vez se crea su ficha de proveedor con sus mismos datos. */
    private static function proveedorDe(?Hotel $hotel): ?Proveedor
    {
        if (! $hotel) {
            return null;
        }
        if ($hotel->proveedor) {
            return $hotel->proveedor;
        }

        $categoria = Categoria::firstOrCreate(['nombre' => 'Hoteles']);
        $proveedor = Proveedor::firstOrCreate(['nombre' => $hotel->nombre], [
            'categoria_id' => $categoria->id, 'ruc' => $hotel->ruc, 'direccion' => $hotel->direccion,
            'telefono' => (string) $hotel->telefono, 'correo' => (string) $hotel->correo,
        ]);
        $hotel->forceFill(['proveedor_id' => $proveedor->id])->saveQuietly();

        return $proveedor;
    }
}
