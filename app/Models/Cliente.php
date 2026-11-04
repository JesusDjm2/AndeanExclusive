<?php

namespace App\Models;

use App\Support\Paises;
use Illuminate\Database\Eloquent\Model;

/** Cliente titular de uno o más programas: viajero directo o agencia/mayorista. */
class Cliente extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public const TIPOS = [
        'directo' => 'Cliente directo',
        'agencia' => 'Agencia / mayorista',
    ];

    public const ORIGENES = [
        'web' => 'Sitio web',
        'whatsapp' => 'WhatsApp',
        'email' => 'Correo',
        'recomendacion' => 'Recomendación',
        'repetidor' => 'Cliente que repite',
        'agencia' => 'Agencia / mayorista',
        'redes' => 'Redes sociales',
        'feria' => 'Feria / evento',
        'otro' => 'Otro',
    ];

    protected $fillable = ['tipo', 'nombre', 'empresa', 'email', 'telefono', 'pais', 'idioma', 'origen', 'notas'];

    public function programas()
    {
        return $this->hasMany(Programa::class)->orderByDesc('inicio');
    }

    public function getPaisNombreAttribute(): ?string
    {
        return Paises::nombre($this->pais);
    }

    /** "Ana Pérez" o "Ana Pérez · Viajes Andinos" para agencias. */
    public function getEtiquetaAttribute(): string
    {
        return $this->empresa ? "{$this->nombre} · {$this->empresa}" : $this->nombre;
    }
}
