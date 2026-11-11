<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public static $rules = [
        'nombre' => 'required',
        'categoria_id' => 'required|exists:categorias,id',
        'telefono' => 'required',
        'correo' => 'required|email',
        'ruc' => ['nullable', 'regex:/^\d{1,11}$/'],
    ];

    protected $fillable = ['nombre', 'categoria_id', 'direccion', 'ruc', 'telefono', 'correo', 'detalles', 'img'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'proveedor_id');
    }

    /**
     * Alias de compatibilidad hacia sí mismo para vistas o métodos legados que referencian $proveedor->hotel.
     */
    public function hotel()
    {
        return $this->hasOne(Proveedor::class, 'id', 'id');
    }

    public function getHotelAttribute()
    {
        return $this;
    }

    public function getEsHotelAttribute(): bool
    {
        return str_contains(mb_strtolower($this->categoria?->nombre ?? ''), 'hotel')
            || $this->habitaciones()->exists();
    }

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'servicios')->distinct();
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function mensajes()
    {
        return $this->hasMany(MensajeProveedor::class)->latest()->orderByDesc('id');
    }

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class)->conPrioridadTemporada();
    }

    public function tarifasFijas()
    {
        return $this->hasMany(Tarifa::class)->where(fn ($q) => $q->whereNull('desde')->orWhere('es_fija', true));
    }

    public function tarifasTemporada()
    {
        return $this->hasMany(Tarifa::class)->whereNotNull('desde')->where('es_fija', false);
    }
}
