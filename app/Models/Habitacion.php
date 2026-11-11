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
    public function habitacionFechas()
    {
        return $this->hasMany(HabitacionFecha::class);
    }
    public function tarifas()
    {
        return $this->hasMany(Tarifa::class)->conPrioridadTemporada();
    } 
}
