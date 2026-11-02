<?php

namespace App\Support;

use App\Mail\RecordatorioMailable;
use App\Models\Notificacion;
use App\Models\Programa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

/**
 * Avisos para el equipo: qué correo conviene enviar al cliente y cuándo. El sistema NO escribe solo al cliente;
 * sugiere el envío (dashboard y pestaña Pagos) y la persona que gestiona el programa lo envía con un clic.
 *  - deposito:  confirmado sin el 50 % pagado, cuando faltan 2 días o ya pasó el plazo de 7 días desde la confirmación.
 *  - saldo:     depósito cubierto y saldo pendiente, desde 7 días antes del vencimiento (60 días antes del viaje).
 *  - datos_pax: confirmado, faltan 75 días o menos y hay pasajeros sin pasaporte (o ninguno cargado).
 *  - encuesta:  el viaje terminó en los últimos 7 días.
 */
class Recordatorios
{
    public const DIAS_AVISO_DEPOSITO = 2;

    public const DIAS_AVISO_SALDO = 7;

    public const DIAS_PEDIR_DATOS = 75;

    public const DIAS_ENCUESTA = 7;

    /** Tipos que corresponde enviar hoy a este programa (sin mirar si ya se enviaron). */
    public static function pendientes(Programa $programa): array
    {
        if ($programa->estado === 'cancelado' || ! filter_var((string) $programa->email, FILTER_VALIDATE_EMAIL)) {
            return [];
        }

        $hoy = Carbon::today();
        $tipos = [];
        $cobro = $programa->cobranza();
        $inicio = $programa->inicio ? Carbon::parse($programa->inicio)->startOfDay() : null;
        $fin = $programa->fin ? Carbon::parse($programa->fin)->startOfDay() : null;
        $viajeFuturo = $inicio && $hoy->lt($inicio);

        if ($programa->estado === 'confirmado' && $viajeFuturo && $cobro['total'] > 0) {
            if (! $cobro['depositoCubierto'] && $cobro['vencimientoDeposito']?->copy()->subDays(self::DIAS_AVISO_DEPOSITO)->lte($hoy)) {
                $tipos[] = 'deposito';
            }
            if ($cobro['depositoCubierto'] && $cobro['saldo'] > 0.009 && $cobro['vencimientoSaldo']?->copy()->subDays(self::DIAS_AVISO_SALDO)->lte($hoy)) {
                $tipos[] = 'saldo';
            }
        }

        if ($programa->estado === 'confirmado' && $viajeFuturo && $hoy->diffInDays($inicio) <= self::DIAS_PEDIR_DATOS
            && ($programa->paxs->isEmpty() || $programa->paxs->contains(fn ($pax) => blank($pax->pasaporte)))) {
            $tipos[] = 'datos_pax';
        }

        if ($programa->estado === 'confirmado' && $fin && $hoy->gt($fin) && $fin->diffInDays($hoy) <= self::DIAS_ENCUESTA) {
            $tipos[] = 'encuesta';
        }

        return $tipos;
    }

    /** Envíos sugeridos: los que corresponden y todavía no se mandaron. Requiere notificaciones (se cargan si faltan). */
    public static function sugeridos(Programa $programa): array
    {
        $programa->loadMissing('notificaciones');
        $enviados = $programa->notificaciones->pluck('tipo');

        return array_values(array_filter(self::pendientes($programa), fn ($tipo) => ! $enviados->contains($tipo)));
    }

    /**
     * Programas con algún envío sugerido (para el dashboard).
     *
     * @return Collection<int, array{programa: Programa, tipos: array}>
     */
    public static function porEnviar(): Collection
    {
        return Programa::query()
            ->where('estado', 'confirmado')
            ->whereDate('fin', '>=', now()->subDays(self::DIAS_ENCUESTA))
            ->with(['cliente', 'paxs', 'pagos', 'notificaciones'])
            ->orderBy('inicio')
            ->get()
            ->map(fn (Programa $programa) => ['programa' => $programa, 'tipos' => self::sugeridos($programa)])
            ->filter(fn ($item) => $item['tipos'])
            ->values();
    }

    /** Envía el correo (siempre a pedido de una persona del equipo) y lo registra. */
    public static function enviar(Programa $programa, string $tipo, ?int $userId = null): Notificacion
    {
        $programa->loadMissing(['paxs', 'pagos', 'cliente']);
        Mail::to($programa->email)->send(new RecordatorioMailable($programa, $tipo));

        return $programa->notificaciones()->create([
            'tipo' => $tipo,
            'email' => $programa->email,
            'automatica' => false,
            'enviada_por' => $userId,
        ]);
    }
}
