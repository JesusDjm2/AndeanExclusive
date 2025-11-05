<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estag extends Model
{
    protected $table = 'estags';

    protected $fillable = [
        'nombre',
        'slug',
    ];
    public function blogs()
    {
        return $this->belongsToMany(Esblog::class, 'esblogs_tags', 'tag_id', 'blog_id');
    }
}
