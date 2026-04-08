<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anio extends Model
{
    protected $table = 'anios';

    protected $fillable = ['anio'];

    public function programas(): HasMany
    {
        return $this->hasMany(Programa::class, 'anio_id');
    }
}
