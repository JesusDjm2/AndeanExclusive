<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $fillable = [
        'proveedor_id',
        'hotel_id',
        'tipo',
        'capacidad',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    /**
     * Alias de relación hotel hacia el proveedor para compatibilidad con código existente.
     */
    public function hotel()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function getHotelIdAttribute()
    {
        return $this->proveedor_id;
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
