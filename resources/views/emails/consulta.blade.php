{{-- Correo interno de una consulta del sitio (ConsultaRecibidaMailable). Tablas y estilos en línea: es lo que respetan los clientes de correo. --}}
@php
    $filas = array_filter([
        'Correo' => $consulta->email,
        'Teléfono' => $consulta->telefono,
        'Viaje' => $consulta->tour,
        'Llegada' => $consulta->fecha_inicio?->format('d/m/Y'),
        'Salida' => $consulta->fecha_fin?->format('d/m/Y'),
        'Adultos' => $consulta->adultos,
        'Niños' => $consulta->ninos,
        'Idioma' => $consulta->idioma === 'es' ? 'Español' : 'Inglés',
    ], fn ($valor) => filled($valor));
@endphp
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Nueva consulta</title></head>
<body style="margin:0;padding:0;background:#f4f7f8;font-family:Arial,Helvetica,sans-serif;color:#1b2a33;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f8;padding:24px 12px;">
        <tr><td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #d9e2e6;">
                <tr><td style="background:#078f84;padding:20px 28px;color:#ffffff;">
                    <div style="font-size:13px;opacity:.9;">Andean Exclusive Tours · Consulta desde la web</div>
                    <div style="font-size:22px;font-weight:bold;padding-top:4px;">{{ $consulta->nombre }}</div>
                </td></tr>
                <tr><td style="padding:22px 28px 6px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;">
                        @foreach ($filas as $etiqueta => $valor)
                            <tr>
                                <td style="padding:7px 12px 7px 0;color:#51626d;width:110px;border-bottom:1px solid #edf1f2;vertical-align:top;">{{ $etiqueta }}</td>
                                <td style="padding:7px 0;font-weight:bold;border-bottom:1px solid #edf1f2;">{{ $valor }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td></tr>
                @if ($consulta->mensaje)
                    <tr><td style="padding:16px 28px 4px;">
                        <div style="font-size:13px;color:#51626d;padding-bottom:6px;">Mensaje</div>
                        <div style="background:#f4f7f8;border-radius:8px;padding:14px 16px;font-size:15px;line-height:1.5;">{!! nl2br(e($consulta->mensaje)) !!}</div>
                    </td></tr>
                @endif
                <tr><td style="padding:22px 28px 28px;">
                    <a href="{{ route('consultas.show', $consulta) }}" style="display:inline-block;background:#078f84;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;padding:12px 22px;border-radius:6px;">Abrir en el panel</a>
                    <div style="font-size:13px;color:#51626d;padding-top:14px;">Para contestar, responde a este correo: le llegará directamente a {{ $consulta->email }}.</div>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
