<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $fillable = [
        'hotel_id',
        'tipo',
        'capacidad',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'habitacion_programa');
    }
    public function habitacionFechas()
    {
        return $this->hasMany(HabitacionFecha::class);
    } 
}
