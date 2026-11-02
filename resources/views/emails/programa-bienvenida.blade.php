{{--
    Correo con el programa en PDF adjunto (App\Mail\ProgramaBienvenidaMailable), ES/EN según $programa->lang.
    Propuesta (cotización) o programa confirmado; siempre enlaza al itinerario en línea. Estilos en línea (clientes de correo).
--}}
@php
    $isEn = ($programa->lang ?? 'es') === 'en';
    $esPropuesta = $programa->estado === 'cotizacion';
    $nombre = $programa->cliente?->nombre;
    $locale = $isEn ? 'en' : 'es';
    $fecha = fn ($c) => \Carbon\Carbon::parse($c)->locale($locale)->isoFormat($isEn ? 'MMMM D, YYYY' : 'D [de] MMMM [de] YYYY');
    $btn = 'display:inline-block;padding:12px 22px;background:#078f84;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;';
    $btnGhost = 'display:inline-block;padding:11px 20px;border:1px solid #078f84;color:#078f84;text-decoration:none;border-radius:8px;font-weight:600;';
    $total = $programa->presupuesto()['total'];
    $faltanDatos = $programa->paxs->isEmpty() || $programa->paxs->contains(fn ($p) => blank($p->pasaporte));
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
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
                        <td style="background:#0b1d1f;padding:26px 32px;color:#ffffff;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;">
                            <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#4fe0d4;font-weight:700;">
                                {{ $esPropuesta ? ($isEn ? 'Travel proposal' : 'Propuesta de viaje') : ($isEn ? 'Confirmed program' : 'Programa confirmado') }} · {{ $programa->codigo }}
                            </div>
                            <div style="font-size:22px;font-weight:700;margin-top:6px;line-height:1.25;">{{ $programa->nombre }}</div>
                            @if ($programa->inicio && $programa->fin)
                                <div style="font-size:14px;color:#cfe3e1;margin-top:6px;">{{ ucfirst($fecha($programa->inicio)) }} – {{ $fecha($programa->fin) }}</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;font-size:15px;line-height:1.65;color:#333333;">
                            <p style="margin:0 0 16px;">{{ $isEn ? 'Dear' : 'Estimado/a' }} {{ $nombre ?: ($isEn ? 'traveler' : 'viajero/a') }},</p>

                            @if ($esPropuesta)
                                <p style="margin:0 0 16px;">{{ $isEn
                                    ? 'Thank you for your interest in traveling to Peru with us. Attached you will find the proposal we have prepared for you, with the day-by-day itinerary, accommodation and price.'
                                    : 'Gracias por su interés en viajar al Perú con nosotros. Adjuntamos la propuesta que preparamos para usted, con el itinerario día a día, el alojamiento y el precio.' }}</p>
                                <p style="margin:0 0 16px;">{{ $isEn
                                    ? 'Services and prices are subject to availability until the deposit is received. If you would like to adjust anything, simply reply to this email.'
                                    : 'Los servicios y precios están sujetos a disponibilidad hasta recibir el depósito. Si desea ajustar algo, solo responda a este correo.' }}</p>
                            @else
                                <p style="margin:0 0 16px;">{{ $isEn
                                    ? 'Your trip to Peru is confirmed. Attached you will find your program in PDF, with the day-by-day itinerary, accommodation and your account statement.'
                                    : 'Su viaje al Perú está confirmado. Adjuntamos su programa en PDF, con el itinerario día a día, el alojamiento y su estado de cuenta.' }}</p>
                            @endif

                            <p style="margin:0 0 8px;font-weight:600;">{{ $isEn ? 'Your itinerary online' : 'Su itinerario en línea' }}</p>
                            <p style="margin:0 0 18px;color:#555555;font-size:14px;">{{ $isEn
                                ? 'The online version is always up to date with any change we make to your trip.'
                                : 'La versión en línea siempre está al día con cualquier cambio que hagamos en su viaje.' }}</p>
                            <p style="margin:0 0 20px;">
                                <a href="{{ route('itinerario', $programa) }}" style="{{ $btn }}">{{ $isEn ? 'View my itinerary' : 'Ver mi itinerario' }}</a>
                                @if (! $esPropuesta && $faltanDatos)
                                    &nbsp;<a href="{{ route('itinerario.pasajeros', $programa) }}" style="{{ $btnGhost }}">{{ $isEn ? 'Complete travelers’ details' : 'Completar datos de viajeros' }}</a>
                                @endif
                            </p>

                            @if ($total > 0)
                                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0 0 20px;background:#f2fbfa;border:1px solid #d5ecea;border-radius:8px;">
                                    <tr>
                                        <td style="padding:14px 18px;font-size:14px;">
                                            {{ $isEn ? 'Total' : 'Total' }}: <strong style="color:#078f84;font-size:16px;">US$ {{ number_format($total, 2) }}</strong>
                                            <span style="color:#666666;"> · {{ $programa->paxs->count() }} {{ $isEn ? 'travelers' : 'viajeros' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin:0 0 4px;color:#666666;font-size:13px;">📎 {{ $isEn ? 'The full program is attached as a PDF file.' : 'El programa completo va adjunto en PDF.' }}</p>
                            <p style="margin:16px 0 0;">{{ $isEn ? 'Kind regards,' : 'Saludos cordiales,' }}<br><strong>Andean Exclusive Tours</strong></p>
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
