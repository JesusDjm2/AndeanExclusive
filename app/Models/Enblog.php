<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enblog extends Model
{
    protected $table = 'enblogs';

    protected $fillable = [
        'nombre',
        'imgThumb',
        'imgFull',
        'descripcionCorta',
        'descripcion',
        'slug',
        'keywords',
    ];
    public function tags()
    {
        return $this->belongsToMany(Entag::class, 'enblogs_tags', 'blog_id', 'tag_id');
    }
    public function esblog()
    {
        return $this->hasOne(Esblog::class, 'relacionado_id');
    }
}
