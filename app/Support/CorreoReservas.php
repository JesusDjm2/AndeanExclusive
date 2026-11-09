<?php

namespace App\Support;

use App\Mail\MensajeProveedorMailable;
use App\Models\MensajeProveedor;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Throwable;

/**
 * Mensajes de Reservas a los proveedores desde la cuenta corporativa (reservas@andeanexclusivetours.com).
 * Con MAIL_RESERVAS_HOST configurado sale por su propio SMTP; si no, por el correo general con "responder a"
 * reservas@, para que las respuestas lleguen a esa casilla. Siempre va copia oculta a reservas@ y queda en el hilo.
 */
class CorreoReservas
{
    /** Tipo => [etiqueta del botón, adjunta la orden por defecto, estado al que pasa el servicio (o null)]. */
    public const PLANTILLAS = [
        'solicitud' => ['Solicitar reserva', false, 'solicitado'],
        'confirmacion' => ['Confirmar y enviar orden', true, null],
        'modificacion' => ['Modificar reserva', true, null],
        'cancelacion' => ['Cancelar reserva', false, 'cancelado'],
        'consulta' => ['Consulta', false, null],
    ];

    public static function remitente(): array
    {
        return [config('mail.reservas.address'), config('mail.reservas.name')];
    }

    /** Asunto y cuerpo sugeridos para cada tipo; Reservas los puede editar antes de enviar. */
    public static function plantillas(Servicio $servicio, ?User $usuario = null): array
    {
        $servicio->loadMissing(['programa.paxs', 'programa.cliente', 'proveedor']);
        $programa = $servicio->programa;
        $p = $programa->presupuesto();
        $fecha = $servicio->fecha->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
        $hora = $servicio->hora ? ', ' . substr((string) $servicio->hora, 0, 5) . ' h' : '';
        $titular = $programa->paxs->first()?->nombre ?? $programa->cliente?->nombre;
        $pasajeros = $programa->paxs->count() . ' (' . collect([
            $p['adultos'] ? $p['adultos'] . ' ' . ($p['adultos'] === 1 ? 'adulto' : 'adultos') : null,
            $p['ninos'] ? $p['ninos'] . ' ' . ($p['ninos'] === 1 ? 'niño' : 'niños') : null,
            $p['infantes'] ? $p['infantes'] . ' ' . ($p['infantes'] === 1 ? 'infante' : 'infantes') : null,
        ])->filter()->implode(', ') . ')';

        $detalle = collect([
            "• Servicio: {$servicio->titulo}",
            "• Fecha: {$fecha}{$hora}",
            "• Pasajeros: {$pasajeros}",
            "• Grupo: {$programa->codigo}" . ($titular ? " · {$titular}" : ''),
            $servicio->punto_encuentro ? "• Punto de encuentro: {$servicio->punto_encuentro}" : null,
            $servicio->confirmacion ? "• Su n.º de confirmación: {$servicio->confirmacion}" : null,
        ])->filter()->implode("\n");

        $saludo = 'Estimados ' . ($servicio->proveedor?->nombre ?? 'proveedor') . ':';
        $firma = "Saludos cordiales,\n" . ($usuario?->name ? $usuario->name . "\n" : '') . 'Reservas · Andean Exclusive Tours' . "\n" . config('mail.reservas.address');
        $ref = "{$programa->codigo} · {$servicio->titulo} · {$servicio->fecha->format('d/m/Y')}";

        return [
            'solicitud' => [
                'asunto' => "Solicitud de reserva · {$ref}",
                'cuerpo' => "{$saludo}\n\nLes escribimos de Andean Exclusive Tours para solicitar la siguiente reserva:\n\n{$detalle}\n\nLes agradeceremos confirmar disponibilidad y tarifa respondiendo a este correo, con su número de confirmación.\n\n{$firma}",
            ],
            'confirmacion' => [
                'asunto' => "Confirmación de servicio · {$ref}",
                'cuerpo' => "{$saludo}\n\nConfirmamos el siguiente servicio:\n\n{$detalle}\n\nAdjuntamos la orden de servicio con el detalle de los pasajeros. Por favor, acusen recibo respondiendo a este correo.\n\n{$firma}",
            ],
            'modificacion' => [
                'asunto' => "Modificación de reserva · {$ref}",
                'cuerpo' => "{$saludo}\n\nLes solicitamos modificar la siguiente reserva:\n\n{$detalle}\n\nCambio solicitado: [indique aquí el cambio]\n\nLes agradeceremos confirmar el cambio respondiendo a este correo.\n\n{$firma}",
            ],
            'cancelacion' => [
                'asunto' => "Cancelación de reserva · {$ref}",
                'cuerpo' => "{$saludo}\n\nLes informamos que cancelamos la siguiente reserva:\n\n{$detalle}\n\nLes agradeceremos confirmar la anulación respondiendo a este correo.\n\n{$firma}",
            ],
            'consulta' => [
                'asunto' => "Consulta · {$ref}",
                'cuerpo' => "{$saludo}\n\nSobre el siguiente servicio:\n\n{$detalle}\n\n[Escriba aquí su consulta]\n\n{$firma}",
            ],
        ];
    }

    /**
     * Envía el mensaje y lo deja en el hilo del servicio. Devuelve el mensaje guardado (con 'error' si el envío falló:
     * en ese caso el servicio no cambia de estado).
     */
    public static function enviar(Servicio $servicio, string $tipo, string $para, string $asunto, string $cuerpo, bool $conOrden, ?User $usuario): MensajeProveedor
    {
        $servicio->loadMissing(['programa.paxs', 'programa.cliente', 'programa.agentes', 'proveedor.categoria']);
        $adjunto = null;
        if ($conOrden) {
            $html = View::make('servicios.voucher', ['servicio' => $servicio, 'programa' => $servicio->programa])->render();
            $adjunto = [Pdf::desdeHtml($html), "orden-de-servicio-{$servicio->programa->codigo}-{$servicio->id}.pdf"];
        }

        $mensaje = new MensajeProveedor([
            'proveedor_id' => $servicio->proveedor_id,
            'servicio_id' => $servicio->id,
            'programa_id' => $servicio->programa_id,
            'user_id' => $usuario?->id,
            'direccion' => 'enviado',
            'tipo' => $tipo,
            'para' => $para,
            'asunto' => $asunto,
            'cuerpo' => $cuerpo,
            'con_orden' => $conOrden,
        ]);

        try {
            [$correoReservas] = self::remitente();
            $mailable = (new MensajeProveedorMailable($asunto, $cuerpo, $adjunto))->bcc($correoReservas);
            $mailer = filled(config('mail.mailers.reservas.host')) ? Mail::mailer('reservas') : Mail::mailer();
            $mailer->to($para, $servicio->proveedor?->nombre)->send($mailable);
        } catch (Throwable $e) {
            report($e);
            $mensaje->error = mb_substr($e->getMessage(), 0, 250);
            $mensaje->save();

            return $mensaje;
        }

        $mensaje->save();
        self::aplicarEfecto($servicio, $tipo, $conOrden, $para);

        return $mensaje;
    }

    /** Lo que cambia en el servicio al enviar: solicitar lo pasa a "solicitado", cancelar a "cancelado". */
    private static function aplicarEfecto(Servicio $servicio, string $tipo, bool $conOrden, string $para): void
    {
        $nuevoEstado = self::PLANTILLAS[$tipo][2] ?? null;
        $cambios = [];
        if ($nuevoEstado === 'solicitado' && in_array($servicio->estado, ['sin_asignar', 'pendiente'], true)) {
            $cambios['estado'] = 'solicitado';
        }
        if ($nuevoEstado === 'cancelado' && $servicio->estado !== 'cancelado') {
            $cambios['estado'] = 'cancelado';
        }
        if ($conOrden) {
            $cambios['orden_enviada_at'] = now();
        }
        if ($cambios) {
            $servicio->update($cambios);
        }
        $servicio->registrarEvento('nota', null, null, MensajeProveedor::TIPOS[$tipo] . " enviada a {$para}" . ($conOrden ? ' (con orden de servicio)' : ''));
    }

    /**
     * Registra la respuesta del proveedor (llegó a reservas@ o por teléfono) y actualiza el servicio:
     * confirmó → "confirmado" con su n.º; sin disponibilidad → vuelve a "por reservar" para buscar otro.
     */
    public static function registrarRespuesta(Servicio $servicio, string $resultado, ?string $confirmacion, string $detalle, ?User $usuario): MensajeProveedor
    {
        $mensaje = MensajeProveedor::create([
            'proveedor_id' => $servicio->proveedor_id,
            'servicio_id' => $servicio->id,
            'programa_id' => $servicio->programa_id,
            'user_id' => $usuario?->id,
            'direccion' => 'recibido',
            'tipo' => 'respuesta',
            'cuerpo' => $detalle,
            'resultado' => $resultado,
        ]);

        if ($resultado === 'confirmado') {
            $servicio->update(['estado' => 'confirmado', 'confirmacion' => $confirmacion ?: $servicio->confirmacion]);
        } elseif ($resultado === 'sin_disponibilidad' && $servicio->estado !== 'cancelado') {
            $servicio->update(['estado' => 'pendiente', 'confirmacion' => null]);
        }
        $servicio->registrarEvento('nota', null, null, 'Respuesta del proveedor: ' . MensajeProveedor::RESULTADOS[$resultado]);

        return $mensaje;
    }
}
