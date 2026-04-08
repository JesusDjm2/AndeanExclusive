<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mes extends Model
{
    protected $table = 'meses';

    protected $fillable = ['numero', 'nombre'];

    public function programas(): HasMany
    {
        return $this->hasMany(Programa::class, 'mes_id');
    }
}
