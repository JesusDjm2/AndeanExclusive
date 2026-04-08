@php
    $isEn = ($programa->lang ?? 'es') === 'en';
    $agency = config('mail.from.name', config('app.name', 'Andean Exclusive'));
@endphp
<!DOCTYPE html>
<html lang="{{ $isEn ? 'en' : 'es' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'Your program' : 'Su programa' }}</title>
</head>

<body style="margin:0;padding:0;background-color:#f0f2f5;font-family:Georgia,'Times New Roman',serif;-webkit-font-smoothing:antialiased;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%"
        style="background-color:#f0f2f5;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%"
                    style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">
                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#1e3a5f 0%,#2d5a87 45%,#c9a227 100%);padding:28px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:600;letter-spacing:0.02em;">
                                {{ $agency }}
                            </h1>
                            <p style="margin:10px 0 0;font-size:14px;color:rgba(255,255,255,0.92);font-family:system-ui,-apple-system,sans-serif;">
                                @if ($isEn)
                                    Tailor-made journeys in Peru
                                @else
                                    Viajes a medida en el Perú
                                @endif
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 32px 8px;font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:16px;line-height:1.65;color:#334155;">
                            @if ($isEn)
                                <p style="margin:0 0 16px;">Dear traveler,</p>
                                <p style="margin:0 0 16px;">It is a pleasure to greet you on behalf of
                                    <strong>{{ $agency }}</strong>. We are delighted to welcome you to <strong>Peru</strong>
                                    — a country of extraordinary landscapes, living history, and warm hospitality.
                                </p>
                                <p style="margin:0 0 16px;">We have prepared your travel program
                                    <strong style="color:#1e3a5f;">{{ $programa->nombre }}</strong>@if ($programa->codigo)
                                        <span style="color:#64748b;"> (ref. {{ $programa->codigo }})</span>
                                    @endif in PDF format, attached to this message, so you can review every detail at
                                    your convenience.
                                </p>
                                <p style="margin:0 0 16px;">If you have any questions or wish to adjust your itinerary,
                                    simply reply to this email — our team will be happy to assist you.</p>
                                <p style="margin:0;">We wish you an unforgettable stay in Peru.</p>
                            @else
                                <p style="margin:0 0 16px;">Estimado/a viajero/a,</p>
                                <p style="margin:0 0 16px;">Es un gusto saludarle en nombre de
                                    <strong>{{ $agency }}</strong>. Nos complace darle la más cordial bienvenida al
                                    <strong>Perú</strong>: un país de paisajes extraordinarios, historia viva y una
                                    hospitalidad que enamora.
                                </p>
                                <p style="margin:0 0 16px;">Adjunto a este correo encontrará su programa de viaje
                                    <strong style="color:#1e3a5f;">{{ $programa->nombre }}</strong>@if ($programa->codigo)
                                        <span style="color:#64748b;"> (ref. {{ $programa->codigo }})</span>
                                    @endif en formato <strong>PDF</strong>, para que pueda revisar todos los detalles con
                                    tranquilidad.
                                </p>
                                <p style="margin:0 0 16px;">Si tiene alguna consulta o desea ajustar su itinerario,
                                    puede responder a este mismo correo; con gusto le atenderemos.</p>
                                <p style="margin:0;">Le deseamos una estadía inolvidable en el Perú.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 28px;font-family:system-ui,-apple-system,sans-serif;">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%"
                                style="background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:#64748b;">
                                            @if ($isEn)
                                                Program summary
                                            @else
                                                Resumen del programa
                                            @endif
                                        </p>
                                        @if ($programa->inicio && $programa->fin)
                                            @php
                                                $dIni = \Carbon\Carbon::parse($programa->inicio);
                                                $dFin = \Carbon\Carbon::parse($programa->fin);
                                                $txtIni = $isEn
                                                    ? $dIni->format('F j, Y')
                                                    : $dIni->locale('es')->translatedFormat('d \d\e F \d\e Y');
                                                $txtFin = $isEn
                                                    ? $dFin->format('F j, Y')
                                                    : $dFin->locale('es')->translatedFormat('d \d\e F \d\e Y');
                                            @endphp
                                            <p style="margin:0;font-size:14px;color:#1e293b;">
                                                <strong>{{ $isEn ? 'Dates' : 'Fechas' }}:</strong>
                                                {{ $txtIni }} — {{ $txtFin }}
                                            </p>
                                        @endif
                                        <p style="margin:12px 0 0;font-size:13px;color:#64748b;">
                                            @if ($isEn)
                                                📎 The full program is attached as a PDF file.
                                            @else
                                                📎 El programa completo va adjunto en PDF.
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding:20px 32px 28px;border-top:1px solid #e2e8f0;font-family:system-ui,-apple-system,sans-serif;font-size:14px;color:#64748b;text-align:center;">
                            <p style="margin:0 0 8px;color:#1e293b;font-weight:600;">{{ $agency }}</p>
                            <p style="margin:0;font-size:12px;">
                                @if ($isEn)
                                    This message was sent because a program was shared with your email address.
                                @else
                                    Este mensaje se envió porque se compartió un programa con su correo electrónico.
                                @endif
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
