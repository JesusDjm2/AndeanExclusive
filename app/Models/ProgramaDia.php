<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Título y descripción de un día del itinerario (lo que lee el cliente). */
class ProgramaDia extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    protected $table = 'programa_dias';

    protected $fillable = ['programa_id', 'fecha', 'titulo', 'descripcion'];

    protected $casts = ['fecha' => 'date'];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }
}
