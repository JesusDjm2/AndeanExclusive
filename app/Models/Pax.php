<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pax extends Model
{
    protected $fillable = [
        'nombre',
        'edad',
        'pasaporte',
        'nacionalidad',
        'alimentacion',
        'programa_id',
    ];
    
    public function agentes()
    {
        return $this->belongsToMany(Agente::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }
}
