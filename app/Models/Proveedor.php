<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public static $rules = [
        'nombre' => 'required',
        'categoria_id' => 'required',
        'telefono' => 'required',
        'correo' => 'required',
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
}
