<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';

    protected $fillable = [
        'nombre',
        'codigo',
        'email',
        'inicio',
        'fin',
        'lang',
        'precioAdulto',
        'precioChild',
        'presentacion',
    ];

    public function agentes()
    {
        return $this->belongsToMany(Agente::class, 'agente_programa', 'programa_id', 'agente_id');
    }

    public function paxs()
    {
        return $this->hasMany(Pax::class);
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'programa_proveedor');
    }

    public function habitaciones()
    {
        return $this->belongsToMany(Habitacion::class);
    }
    public function habitacionesFechas()
    {
        return $this->hasMany(HabitacionFecha::class, 'programa_id');
    }
}
