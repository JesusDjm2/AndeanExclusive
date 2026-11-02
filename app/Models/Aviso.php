<?php

namespace App\Models;

use App\Support\Permisos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** Aviso a un área sobre algo que debe saber o hacer (lo crea App\Support\ReglasDeAvisos). */
class Aviso extends Model
{
    protected $fillable = [
        'area', 'tipo', 'requiere_accion', 'titulo', 'detalle', 'accion', 'ruta', 'ruta_params', 'ancla', 'clave', 'veces',
        'programa_id', 'programa_codigo', 'actor_id', 'actor_nombre', 'resuelto_at',
    ];

    protected $casts = [
        'requiere_accion' => 'boolean',
        'ruta_params' => 'array',
        'resuelto_at' => 'datetime',
    ];

    public function lecturas()
    {
        return $this->hasMany(AvisoLectura::class);
    }

    /** Los de su área (el administrador ve los de todas); nadie recibe aviso de lo que hizo él mismo. */
    public function scopeParaUsuario(Builder $query, User $user): Builder
    {
        return $query
            ->when(! $user->hasRole(Permisos::ADMIN), fn ($q) => $q->whereIn('area', $user->getRoleNames()))
            ->where(fn ($q) => $q->whereNull('actor_id')->orWhere('actor_id', '!=', $user->id));
    }

    /** Pendientes de ver. Lo ya resuelto no cuenta: nadie debe actuar sobre algo que dejó de hacer falta. */
    public function scopeNoVistosPor(Builder $query, User $user): Builder
    {
        return $query->whereNull('resuelto_at')
            ->whereDoesntHave('lecturas', fn ($q) => $q->where('user_id', $user->id));
    }

    public function scopeVistosPor(Builder $query, User $user): Builder
    {
        return $query->where(fn ($q) => $q->whereNotNull('resuelto_at')
            ->orWhereHas('lecturas', fn ($l) => $l->where('user_id', $user->id)));
    }

    public function scopeConVisto(Builder $query, User $user): Builder
    {
        return $query->withExists(['lecturas as visto' => fn ($q) => $q->where('user_id', $user->id)]);
    }

    public function marcarVisto(User $user): void
    {
        DB::table('aviso_lecturas')->updateOrInsert(['aviso_id' => $this->id, 'user_id' => $user->id], ['leido_at' => now()]);
    }

    public function marcarNoVisto(User $user): void
    {
        $this->lecturas()->where('user_id', $user->id)->delete();
    }
}
