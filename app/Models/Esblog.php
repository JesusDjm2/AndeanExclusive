<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Esblog extends Model
{
    protected $table = 'esblogs';

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
        return $this->belongsToMany(Estag::class, 'esblogs_tags', 'blog_id', 'tag_id');
    }
    public function blog()
    {
        return $this->belongsTo(Enblog::class, 'relacionado_id');
    }
}
