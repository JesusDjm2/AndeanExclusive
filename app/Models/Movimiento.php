<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/** Entrada de la bitácora de la agencia (ver App\Support\Bitacora). Guarda copias de los nombres: sobrevive a lo que se elimine. */
class Movimiento extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'usuario', 'area', 'verbo', 'modulo', 'titulo', 'programa_id', 'programa_codigo', 'cambios'];

    protected $casts = ['cambios' => 'array'];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    /** Cambios que el usuario actual puede ver: el costo base y el % de ganancia solo los ve quien puede ver la cotización. */
    public function cambiosVisibles(): array
    {
        return array_values(array_filter(
            $this->cambios ?? [],
            fn (array $cambio) => empty($cambio['reservado']) || Gate::allows('cotizacion.ver')
        ));
    }
}
