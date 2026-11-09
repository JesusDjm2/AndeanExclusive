<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Lo que cobra un hotel por habitación o un proveedor por un servicio/concepto (tarifa fija o por temporada). */
class Tarifa extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public const POR = ['noche' => 'por noche', 'grupo' => 'por grupo', 'persona' => 'por persona'];

    protected $fillable = [
        'habitacion_id', 'proveedor_id', 'concepto', 'tipo_servicio',
        'desde', 'hasta', 'es_fija', 'es_especial', 'nombre_especial',
        'precio', 'moneda', 'por', 'unidad_detalle', 'notas',
    ];

    protected $casts = [
        'desde' => 'date',
        'hasta' => 'date',
        'es_fija' => 'boolean',
        'es_especial' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /** Vigente en una fecha dada: coincide la temporada (desde/hasta) o es una tarifa fija estándar. */
    public function scopeVigenteEl(Builder $query, ?string $fecha = null): Builder
    {
        if (! $fecha) {
            return $query;
        }

        return $query->where(function ($q) use ($fecha) {
            $q->where(function ($sub) use ($fecha) {
                $sub->whereNotNull('desde')->whereNotNull('hasta')
                    ->whereDate('desde', '<=', $fecha)
                    ->whereDate('hasta', '>=', $fecha);
            })->orWhere(function ($sub) {
                $sub->where(function ($w) {
                    $w->whereNull('desde')->whereNull('hasta');
                })->orWhere('es_fija', true);
            });
        });
    }

    /** Ordena primero las tarifas de fechas especiales, luego temporadas y luego las fijas estándar. */
    public function scopeConPrioridadTemporada(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN es_especial = 1 THEN 0 WHEN desde IS NOT NULL AND hasta IS NOT NULL THEN 1 ELSE 2 END')
            ->orderBy('concepto');
    }

    public function getDeQuienAttribute(): string
    {
        return $this->habitacion ? ($this->habitacion->hotel?->nombre ?? 'Hotel') : ($this->proveedor?->nombre ?? '—');
    }

    public function getQueAttribute(): string
    {
        return $this->habitacion ? ($this->habitacion->tipo ?? 'Habitación') : (string) $this->concepto;
    }

    public function getTemporadaTextoAttribute(): string
    {
        if ($this->es_especial) {
            $nombre = $this->nombre_especial ? " ({$this->nombre_especial})" : '';
            return '⭐ Fecha especial' . $nombre . ': del ' . ($this->desde ? $this->desde->format('d/m/Y') : '—') . ' al ' . ($this->hasta ? $this->hasta->format('d/m/Y') : '—');
        }

        if ($this->es_fija || (! $this->desde && ! $this->hasta)) {
            return 'Tarifa fija (Todo el año)';
        }

        return 'Del ' . ($this->desde ? $this->desde->format('d/m/Y') : '—') . ' al ' . ($this->hasta ? $this->hasta->format('d/m/Y') : '—');
    }

    public function getEsVigenteAttribute(): bool
    {
        if ($this->es_fija || (! $this->desde && ! $this->hasta)) {
            return true;
        }

        $hoy = now()->toDateString();

        return (! $this->desde || $this->desde->toDateString() <= $hoy) && (! $this->hasta || $this->hasta->toDateString() >= $hoy);
    }
}
