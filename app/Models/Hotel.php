<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'nombre',
        'ruc',
        'direccion',
        'telefono',
        'correo',
        'img',
        'detalles',
    ];

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'hotel_programa');
    }
    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class);
    }
}
