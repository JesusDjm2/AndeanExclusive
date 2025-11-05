<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entag extends Model
{
    protected $table = 'entags';

    protected $fillable = [
        'nombre',
        'slug',
    ];
    public function blogs()
    {
        return $this->belongsToMany(Enblog::class, 'enblogs_tags', 'tag_id', 'blog_id');
    }
}
