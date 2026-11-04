<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Lo que cobra un hotel por habitación o un proveedor por un concepto, entre dos fechas. */
class Tarifa extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public const POR = ['noche' => 'por noche', 'grupo' => 'por grupo', 'persona' => 'por persona'];

    protected $fillable = ['habitacion_id', 'proveedor_id', 'concepto', 'desde', 'hasta', 'precio', 'moneda', 'por', 'notas'];

    protected $casts = ['desde' => 'date', 'hasta' => 'date', 'precio' => 'decimal:2'];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function scopeVigenteEl(Builder $query, string $fecha): Builder
    {
        return $query->whereDate('desde', '<=', $fecha)->whereDate('hasta', '>=', $fecha);
    }

    public function getDeQuienAttribute(): string
    {
        return $this->habitacion ? ($this->habitacion->hotel?->nombre ?? 'Hotel') : ($this->proveedor?->nombre ?? '—');
    }

    public function getQueAttribute(): string
    {
        return $this->habitacion ? ($this->habitacion->tipo ?? 'Habitación') : (string) $this->concepto;
    }
}
