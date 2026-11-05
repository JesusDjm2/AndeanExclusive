<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public static $rules = [
        'nombre' => 'required',
        'categoria_id' => 'required|exists:categorias,id',
        'telefono' => 'required',
        'correo' => 'required|email',
        'ruc' => ['nullable', 'regex:/^\d{1,11}$/'],
    ];

    protected $fillable = ['nombre', 'categoria_id', 'direccion', 'ruc',  'telefono', 'correo', 'detalles'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'servicios')->distinct();
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class)->conPrioridadTemporada();
    }

    public function tarifasFijas()
    {
        return $this->hasMany(Tarifa::class)->where(fn ($q) => $q->whereNull('desde')->orWhere('es_fija', true));
    }

    public function tarifasTemporada()
    {
        return $this->hasMany(Tarifa::class)->whereNotNull('desde')->where('es_fija', false);
    }
}
