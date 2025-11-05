<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tour extends Model
{
    protected $table = 'tours';

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
    ];
    public function estour()
    {
        return $this->hasOne(Estour::class, 'relacionado_id');
    }
    public function categorias()
    {
        return $this->belongsToMany(TourCategory::class, 'tour_category', 'tour_id', 'categoria_id');
    }
}
