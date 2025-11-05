<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Categoria
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 * @property Proveedor[] $proveedors
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Categoria extends Model
{
    public static $rules = [
        'nombre' => 'required',
    ];

    protected $fillable = ['nombre'];

    public function proveedors()
    {
        return $this->hasMany(Proveedor::class);
    }
}
