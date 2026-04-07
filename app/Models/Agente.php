<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agente extends Model
{
    protected $table = 'agentes';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'sitio_web',
        'foto',
    ];
    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'agente_programa', 'agente_id', 'programa_id');
    }
    public function paxs()
    {
        return $this->belongsToMany(Pax::class);
    }
}
