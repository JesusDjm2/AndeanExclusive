<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Constancia de que el cliente aceptó una propuesta, desde su enlace privado. */
class Aceptacion extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    protected $table = 'aceptaciones';

    protected $fillable = ['programa_id', 'nombre', 'ip', 'navegador', 'total', 'propuesta', 'huella'];

    protected $casts = ['propuesta' => 'array', 'total' => 'decimal:2'];

    public function programa()
    {
        return $this->belongsTo(Programa::class)->withoutGlobalScope('sin_plantillas');
    }
}
