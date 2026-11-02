<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Interés de un posible cliente, de la web o cargado a mano. Se convierte en cotización y termina ganado o perdido. */
class Consulta extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public const ESTADOS = [
        'nueva' => 'Nueva',
        'en_gestion' => 'En gestión',
        'cotizada' => 'Cotizada',
        'ganada' => 'Ganada',
        'perdida' => 'Perdida',
    ];

    public const MOTIVOS_PERDIDA = [
        'precio' => 'Precio',
        'sin_respuesta' => 'Dejó de responder',
        'fechas' => 'Fechas o disponibilidad',
        'otra_agencia' => 'Eligió otra agencia',
        'no_viaja' => 'Canceló su viaje',
        'no_aplica' => 'Spam o no aplica',
        'otro' => 'Otro',
    ];

    protected $attributes = ['estado' => 'nueva', 'idioma' => 'en', 'origen' => 'web'];

    protected $fillable = [
        'nombre', 'email', 'telefono', 'idioma', 'origen', 'tour', 'fecha_inicio', 'fecha_fin', 'adultos', 'ninos',
        'mensaje', 'estado', 'motivo_perdida', 'notas', 'asignado_id', 'cliente_id', 'programa_id', 'atendida_at',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'atendida_at' => 'datetime',
    ];

    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function getResumenViajeAttribute(): string
    {
        $personas = (int) $this->adultos + (int) $this->ninos;

        return collect([
            $this->tour,
            $this->fecha_inicio?->format('d/m/Y'),
            $personas ? $personas . ($personas === 1 ? ' persona' : ' personas') : null,
        ])->filter()->implode(' · ');
    }

    /** Cliente de esta consulta: el mismo correo no se registra dos veces. */
    public function clienteOCrear(): Cliente
    {
        $cliente = $this->cliente
            ?? ($this->email ? Cliente::where('email', $this->email)->first() : null)
            ?? Cliente::create([
                'tipo' => 'directo', 'nombre' => $this->nombre, 'email' => $this->email, 'telefono' => $this->telefono,
                'idioma' => $this->idioma, 'origen' => array_key_exists($this->origen, Cliente::ORIGENES) ? $this->origen : null,
            ]);

        if ($this->cliente_id !== $cliente->id) {
            $this->update(['cliente_id' => $cliente->id]);
        }

        return $cliente;
    }

    /** El programa manda: confirmado = ganada; cancelado = perdida. */
    public function seguirA(Programa $programa): void
    {
        $estado = match ($programa->estado) {
            'confirmado' => 'ganada',
            'cancelado' => 'perdida',
            default => 'cotizada',
        };

        $this->update(['programa_id' => $programa->id, 'estado' => $estado, 'atendida_at' => $this->atendida_at ?? now()]);
    }
}
