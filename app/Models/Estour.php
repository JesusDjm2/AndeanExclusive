<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estour extends Model
{
    protected $table = 'estours';

    protected $fillable = [
        'nombre',
        'recorrido',
        'dias',
        'precio',
        'imgThumb',
        'imgFull',
        'descripcionCorta',
        'presentacion',
        'itinerario',
        'incluye',
        'mapa',
        'importante',
        'slug',
        'keywords',
        'relacionado_id',
    ];
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'relacionado_id');
    }
    public function categorias()
    {
        return $this->belongsToMany(EsCategoria::class, 'estour_categoria', 'tour_id', 'categoria_id');
    }
}
