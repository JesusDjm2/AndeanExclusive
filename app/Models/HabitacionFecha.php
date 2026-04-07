<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitacionFecha extends Model
{
    protected $table = 'habitacion_fechas';

    protected $fillable = [
        'programa_id',
        'habitacion_id',
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
}
