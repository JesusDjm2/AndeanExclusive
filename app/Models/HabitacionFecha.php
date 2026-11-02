<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** Alojamiento de un programa: N habitaciones de un tipo, de tal a tal fecha (sin fechas mientras se cotiza). */
class HabitacionFecha extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    protected $table = 'habitacion_fechas';

    protected $fillable = [
        'programa_id',
        'habitacion_id',
        'cantidad',
        'fecha_ingreso',
        'fecha_salida'
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    /** "3 × Doble" o "Doble". */
    public function getEtiquetaAttribute(): string
    {
        return ($this->cantidad > 1 ? $this->cantidad . ' × ' : '') . ($this->habitacion?->tipo ?? 'Habitación');
    }

    public function getNochesAttribute(): ?int
    {
        return $this->fecha_ingreso && $this->fecha_salida
            ? (int) Carbon::parse($this->fecha_ingreso)->diffInDays(Carbon::parse($this->fecha_salida))
            : null;
    }
}
