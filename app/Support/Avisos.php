<?php

namespace App\Support;

use App\Models\Aviso;
use App\Models\Programa;
use Illuminate\Support\Facades\Auth;

/** Envío y cierre de avisos entre áreas. Un fallo aquí nunca debe impedir la operación que lo originó. */
class Avisos
{
    /**
     * @param  string  $area  Rol destinatario.
     * @param  string  $accion  Texto del botón: qué se le pide hacer.
     * @param  string|null  $clave  Identifica el asunto: si sigue abierto, se actualiza ese aviso (vuelve a "no visto") en vez de repetirlo.
     */
    public static function enviar(
        string $area,
        string $tipo,
        string $titulo,
        ?string $detalle,
        string $accion,
        string $ruta,
        array $params = [],
        ?string $ancla = null,
        ?string $clave = null,
        ?Programa $programa = null,
        bool $requiereAccion = true,
    ): void {
        // Seeders y tareas de consola sin usuario no son actividad de la agencia.
        if (app()->runningInConsole() && ! Auth::check()) {
            return;
        }

        rescue(function () use ($area, $tipo, $titulo, $detalle, $accion, $ruta, $params, $ancla, $clave, $programa, $requiereAccion) {
            $usuario = Auth::user();
            $datos = [
                'tipo' => $tipo,
                'requiere_accion' => $requiereAccion,
                'titulo' => mb_substr($titulo, 0, 255),
                'detalle' => $detalle ? mb_substr($detalle, 0, 500) : null,
                'accion' => $accion,
                'ruta' => $ruta,
                'ruta_params' => $params,
                'ancla' => $ancla,
                'programa_id' => $programa?->id,
                'programa_codigo' => $programa?->codigo,
                'actor_id' => $usuario?->id,
                'actor_nombre' => $usuario?->name ?? (request()->routeIs('itinerario.*') ? 'Cliente' : 'Sistema'),
            ];

            $abierto = $clave ? Aviso::where('clave', $clave)->where('area', $area)->whereNull('resuelto_at')->first() : null;
            if ($abierto) {
                $abierto->update($datos + ['veces' => $abierto->veces + 1]);
                $abierto->lecturas()->delete();

                return;
            }

            Aviso::create($datos + ['area' => $area, 'clave' => $clave]);
        });
    }

    /** El asunto dejó de requerir acción (ya se pagó, ya se reservó...). */
    public static function resolver(string $clave): void
    {
        rescue(fn () => Aviso::where('clave', $clave)->whereNull('resuelto_at')->update(['resuelto_at' => now()]));
    }
}
