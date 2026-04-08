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
        'anio_id',
        'mes_id',
    ];

    public function agentes()
    {
        return $this->belongsToMany(Agente::class, 'agente_programa', 'programa_id', 'agente_id');
    }

    /** Agente principal guardado en programas.agente_id (puede coexistir con la tabla agente_programa). */
    public function agenteResponsable()
    {
        return $this->belongsTo(Agente::class, 'agente_id');
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

    public function anio()
    {
        return $this->belongsTo(Anio::class, 'anio_id');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'mes_id');
    }
}
