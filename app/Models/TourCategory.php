<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourCategory extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nombre',
        'slug'
    ];
    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_category','categoria_id', 'tour_id' );
    }
}
