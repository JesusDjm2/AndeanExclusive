<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Quién vio qué aviso y cuándo. */
class AvisoLectura extends Model
{
    protected $table = 'aviso_lecturas';

    public $timestamps = false;

    protected $fillable = ['aviso_id', 'user_id', 'leido_at'];
}
