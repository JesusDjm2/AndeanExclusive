{{-- Recordatorio al cliente (App\Mail\RecordatorioMailable). Los clientes de correo solo respetan estilos en línea. --}}
@php
    $nombre = $programa->cliente?->nombre;
    $fecha = fn ($c) => $c?->locale($isEn ? 'en' : 'es')->isoFormat($isEn ? 'MMMM D, YYYY' : 'D [de] MMMM [de] YYYY');
    $money = fn ($n) => 'US$ ' . number_format((float) $n, 2);
    $btn = 'display:inline-block;padding:12px 22px;background:#078f84;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;';
    $resena = config('seo.review_url') ?: route($isEn ? 'testimonials' : 'testimonios');
@endphp
<!DOCTYPE html>
<html lang="{{ $isEn ? 'en' : 'es' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $programa->nombre }}</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f4;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f0f4f4;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:#078f84;padding:22px 32px;color:#ffffff;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;">
                            <strong style="font-size:18px;">Andean Exclusive Tours</strong>
                            <div style="font-size:13px;opacity:.9;margin-top:4px;">{{ $programa->nombre }} · {{ $programa->codigo }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;font-size:15px;line-height:1.65;color:#333333;">
                            <p style="margin:0 0 16px;">{{ $isEn ? 'Dear' : 'Estimado/a' }} {{ $nombre ?: ($isEn ? 'traveler' : 'viajero/a') }},</p>

                            @switch($tipo)
                                @case('deposito')
                                    @if ($isEn)
                                        <p style="margin:0 0 16px;">Thank you for confirming your trip. To secure your services we need the <strong>{{ \App\Models\Programa::PORCENTAJE_DEPOSITO }}% deposit</strong> within {{ \App\Models\Programa::DIAS_PARA_DEPOSITO }} days of confirmation{{ $cobro['vencimientoDeposito'] ? ' (by ' . $fecha($cobro['vencimientoDeposito']) . ')' : '' }}.</p>
                                    @else
                                        <p style="margin:0 0 16px;">Gracias por confirmar su viaje. Para asegurar los servicios necesitamos el <strong>depósito del {{ \App\Models\Programa::PORCENTAJE_DEPOSITO }}%</strong> dentro de los {{ \App\Models\Programa::DIAS_PARA_DEPOSITO }} días posteriores a la confirmación{{ $cobro['vencimientoDeposito'] ? ' (hasta el ' . $fecha($cobro['vencimientoDeposito']) . ')' : '' }}.</p>
                                    @endif
                                    <p style="margin:0 0 16px;font-size:17px;"><strong>{{ $isEn ? 'Deposit due' : 'Depósito pendiente' }}: {{ $money(max(0, $cobro['deposito'] - $cobro['pagado'])) }}</strong></p>
                                    @break
                                @case('saldo')
                                    @if ($isEn)
                                        <p style="margin:0 0 16px;">As set out in our terms, the remaining balance is due <strong>{{ \App\Models\Programa::DIAS_SALDO_ANTES_DEL_VIAJE }} days before departure</strong>{{ $cobro['vencimientoSaldo'] ? ', on ' . $fecha($cobro['vencimientoSaldo']) : '' }}.</p>
                                    @else
                                        <p style="margin:0 0 16px;">Según nuestros términos, el saldo se cancela <strong>{{ \App\Models\Programa::DIAS_SALDO_ANTES_DEL_VIAJE }} días antes del inicio del viaje</strong>{{ $cobro['vencimientoSaldo'] ? ', el ' . $fecha($cobro['vencimientoSaldo']) : '' }}.</p>
                                    @endif
                                    <p style="margin:0 0 16px;font-size:17px;"><strong>{{ $isEn ? 'Balance due' : 'Saldo pendiente' }}: {{ $money($cobro['saldo']) }}</strong>
                                        <span style="color:#666666;font-size:14px;">({{ $isEn ? 'paid' : 'pagado' }} {{ $money($cobro['pagado']) }} {{ $isEn ? 'of' : 'de' }} {{ $money($cobro['total']) }})</span></p>
                                    @break
                                @case('datos_pax')
                                    @if ($isEn)
                                        <p style="margin:0 0 16px;">Your trip is getting close. To book trains, entrance tickets and hotels we need each traveler’s <strong>passport details, date of birth, dietary needs and an emergency contact</strong>. It only takes a few minutes:</p>
                                    @else
                                        <p style="margin:0 0 16px;">Su viaje se acerca. Para reservar trenes, entradas y hoteles necesitamos de cada viajero los <strong>datos del pasaporte, fecha de nacimiento, alimentación y un contacto de emergencia</strong>. Le tomará pocos minutos:</p>
                                    @endif
                                    <p style="margin:0 0 20px;"><a href="{{ route('itinerario.pasajeros', $programa) }}" style="{{ $btn }}">{{ $isEn ? 'Complete travelers’ details' : 'Completar datos de los viajeros' }}</a></p>
                                    @break
                                @case('encuesta')
                                    @if ($isEn)
                                        <p style="margin:0 0 16px;">We hope you had an unforgettable time in Peru. Your opinion helps us keep improving and helps other travelers choose with confidence. Would you share a few words about your experience?</p>
                                    @else
                                        <p style="margin:0 0 16px;">Esperamos que haya disfrutado de un viaje inolvidable por el Perú. Su opinión nos ayuda a mejorar y orienta a otros viajeros. ¿Nos contaría cómo fue su experiencia?</p>
                                    @endif
                                    <p style="margin:0 0 20px;"><a href="{{ $resena }}" style="{{ $btn }}">{{ $isEn ? 'Share your experience' : 'Contar mi experiencia' }}</a></p>
                                    @break
                            @endswitch

                            @if (in_array($tipo, ['deposito', 'saldo'], true))
                                <p style="margin:0 0 16px;">{{ $isEn ? 'If you have already made the payment, please disregard this message and accept our thanks. For bank details or any questions, simply reply to this email.' : 'Si ya realizó el pago, por favor ignore este mensaje y reciba nuestro agradecimiento. Para datos bancarios o cualquier consulta, responda a este correo.' }}</p>
                            @endif

                            <p style="margin:0 0 16px;"><a href="{{ route('itinerario', $programa) }}" style="color:#078f84;">{{ $isEn ? 'View your itinerary' : 'Ver su itinerario' }}</a></p>
                            <p style="margin:0;">{{ $isEn ? 'Kind regards,' : 'Saludos cordiales,' }}<br><strong>Andean Exclusive Tours</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px;background:#f2fbfa;font-family:system-ui,-apple-system,sans-serif;font-size:12px;color:#666666;text-align:center;">
                            operaciones@andeanexclusive.com · +51 979 721 194 · Cusco, Perú
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
