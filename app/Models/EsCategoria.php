<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsCategoria extends Model
{
    protected $table = 'escategorias';

    protected $fillable = [
        'nombre',
        'slug'
    ];
    public function tours()
    {
        return $this->belongsToMany(Estour::class, 'estour_categoria', 'categoria_id', 'tour_id' );
    }

}
