{{-- Correo interno de una consulta del sitio (ConsultaRecibidaMailable). Tablas y estilos en línea: es lo que respetan los clientes de correo. --}}
@php
    $isEs = ($consulta->idioma ?? 'es') === 'es';
    $logoUrl = asset('img/logo2.png');
    if (!str_starts_with($logoUrl, 'http://') && !str_starts_with($logoUrl, 'https://')) {
        $logoUrl = 'https://andean.pachamamaspirit.com/img/logo2.png';
    } elseif (str_contains($logoUrl, 'localhost') || str_contains($logoUrl, '127.0.0.1')) {
        $logoUrl = 'https://andean.pachamamaspirit.com/img/logo2.png';
    }

    $origenTexto = match ($consulta->origen) {
        'rapida' => 'Consulta rápida (Portada)',
        'tour' => 'Página de tour / reserva',
        'contacto' => 'Formulario de contacto',
        default => 'Sitio web',
    };

    $telLimpio = $consulta->telefono ? preg_replace('/\D/', '', (string) $consulta->telefono) : null;

    $diasEstadia = ($consulta->fecha_inicio && $consulta->fecha_fin)
        ? $consulta->fecha_inicio->diffInDays($consulta->fecha_fin)
        : null;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva consulta: {{ $consulta->nombre }}</title>
</head>
<body style="margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#eef3f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#182229;">
    <!-- Preheader invisible para vista previa en bandeja de entrada -->
    <div style="display:none;font-size:1px;color:#eef3f5;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Nueva consulta de {{ $consulta->nombre }} &middot; {{ $consulta->tour ?: 'Consulta web' }} &middot; {{ $consulta->email }}
    </div>

    <!-- Contenedor principal -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eef3f5;padding:32px 12px;">
        <tr>
            <td align="center">
                <!-- Tarjeta central -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:620px;background-color:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #dbe4ea;box-shadow:0 8px 24px rgba(24,34,41,0.06);">
                    
                    <!-- Barra de Marca con Logo -->
                    <tr>
                        <td style="background-color:#ffffff;padding:26px 32px 20px;border-bottom:1px solid #f0f4f7;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="left" style="vertical-align:middle;">
                                        <a href="https://andeanexclusivetours.com" target="_blank" rel="noopener" style="text-decoration:none;display:inline-block;">
                                            <img src="{{ $logoUrl }}" alt="Andean Exclusive Tours" width="185" style="display:block;border:0;outline:none;text-decoration:none;max-width:185px;height:auto;" />
                                        </a>
                                    </td>
                                    <td align="right" style="vertical-align:middle;">
                                        <span style="display:inline-block;padding:5px 13px;background-color:#e6f8f6;color:#078f84;font-size:11px;font-weight:700;letter-spacing:0.6px;text-transform:uppercase;border-radius:16px;border:1px solid #b7ede6;">
                                            Nueva Consulta
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Franja decorativa color marca (teal) -->
                    <tr>
                        <td height="4" style="background-color:#09ccbd;line-height:4px;font-size:4px;">&nbsp;</td>
                    </tr>

                    <!-- Cabecera de la consulta (Lead resumen) -->
                    <tr>
                        <td style="background-color:#f8fbfb;padding:28px 32px;border-bottom:1px solid #eef2f5;">
                            <div style="font-size:12px;font-weight:700;color:#078f84;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:6px;">
                                Solicitud de viaje recibida
                            </div>
                            <div style="font-size:23px;font-weight:800;color:#182229;line-height:1.25;margin:0 0 6px;">
                                {{ $consulta->nombre }}
                            </div>
                            <div style="font-size:15px;color:#51626d;line-height:1.5;">
                                @if ($consulta->tour)
                                    Tour solicitado: <strong style="color:#078f84;">{{ $consulta->tour }}</strong>
                                @else
                                    <span style="color:#51626d;">Consulta personalizada / itinerario a medida</span>
                                @endif
                            </div>
                            <div style="margin-top:14px;padding-top:12px;border-top:1px dashed #dbe5e9;font-size:12px;color:#7b8e9b;">
                                <span>📅 Recibido: <strong>{{ $consulta->created_at?->format('d/m/Y \a \l\a\s H:i') ?? now()->format('d/m/Y \a \l\a\s H:i') }}</strong></span>
                                <span style="margin:0 8px;">&bull;</span>
                                <span>🌐 Idioma del cliente: <strong>{{ $consulta->idioma === 'es' ? 'Español' : 'Inglés' }}</strong></span>
                            </div>
                        </td>
                    </tr>

                    <!-- Tabla de detalles clave -->
                    <tr>
                        <td style="padding:28px 32px 16px;">
                            <div style="font-size:13px;font-weight:700;color:#51626d;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">
                                Datos de la consulta
                            </div>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e5ecf0;border-radius:8px;overflow:hidden;border-collapse:collapse;">
                                <tr>
                                    <td style="padding:11px 16px;background-color:#fcfdfe;color:#51626d;font-size:13px;font-weight:600;width:140px;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                        Correo del cliente
                                    </td>
                                    <td style="padding:11px 16px;background-color:#ffffff;font-size:14px;font-weight:700;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                        <a href="mailto:{{ $consulta->email }}" style="color:#078f84;text-decoration:none;">{{ $consulta->email }}</a>
                                    </td>
                                </tr>
                                @if ($consulta->telefono)
                                    <tr>
                                        <td style="padding:11px 16px;background-color:#fcfdfe;color:#51626d;font-size:13px;font-weight:600;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                            Teléfono / Móvil
                                        </td>
                                        <td style="padding:11px 16px;background-color:#ffffff;font-size:14px;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                            <strong style="color:#182229;">{{ $consulta->telefono }}</strong>
                                            @if ($telLimpio)
                                                <a href="https://wa.me/{{ $telLimpio }}" target="_blank" rel="noopener" style="display:inline-block;margin-left:10px;padding:3px 10px;background-color:#25d366;color:#ffffff;font-size:11px;font-weight:700;border-radius:12px;text-decoration:none;">
                                                    WhatsApp &rarr;
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($consulta->fecha_inicio || $consulta->fecha_fin)
                                    <tr>
                                        <td style="padding:11px 16px;background-color:#fcfdfe;color:#51626d;font-size:13px;font-weight:600;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                            Fechas estimadas
                                        </td>
                                        <td style="padding:11px 16px;background-color:#ffffff;font-size:14px;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                            @if ($consulta->fecha_inicio && $consulta->fecha_fin)
                                                <strong style="color:#182229;">{{ $consulta->fecha_inicio->format('d/m/Y') }}</strong> al <strong style="color:#182229;">{{ $consulta->fecha_fin->format('d/m/Y') }}</strong>
                                                @if ($diasEstadia)
                                                    <span style="color:#7b8e9b;font-size:12px;margin-left:6px;">({{ $diasEstadia }} {{ $diasEstadia === 1 ? 'día' : 'días' }})</span>
                                                @endif
                                            @elseif ($consulta->fecha_inicio)
                                                Llegada: <strong style="color:#182229;">{{ $consulta->fecha_inicio->format('d/m/Y') }}</strong>
                                            @else
                                                Salida: <strong style="color:#182229;">{{ $consulta->fecha_fin->format('d/m/Y') }}</strong>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:11px 16px;background-color:#fcfdfe;color:#51626d;font-size:13px;font-weight:600;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                        Viajeros
                                    </td>
                                    <td style="padding:11px 16px;background-color:#ffffff;font-size:14px;border-bottom:1px solid #e5ecf0;vertical-align:middle;">
                                        <strong style="color:#182229;">{{ $consulta->adultos }}</strong> {{ $consulta->adultos === 1 ? 'adulto' : 'adultos' }}
                                        @if ($consulta->ninos > 0)
                                            &middot; <strong style="color:#182229;">{{ $consulta->ninos }}</strong> {{ $consulta->ninos === 1 ? 'niño' : 'niños' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:11px 16px;background-color:#fcfdfe;color:#51626d;font-size:13px;font-weight:600;vertical-align:middle;">
                                        Origen web
                                    </td>
                                    <td style="padding:11px 16px;background-color:#ffffff;font-size:13px;color:#51626d;vertical-align:middle;">
                                        {{ $origenTexto }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Mensaje del visitante (si lo incluyó) -->
                    @if ($consulta->mensaje)
                        <tr>
                            <td style="padding:4px 32px 20px;">
                                <div style="font-size:13px;font-weight:700;color:#51626d;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">
                                    Mensaje del cliente
                                </div>
                                <div style="background-color:#fbfcfd;border:1px solid #e1e8ed;border-left:4px solid #09ccbd;border-radius:4px 8px 8px 4px;padding:16px 20px;font-size:14px;line-height:1.6;color:#2c3e50;">
                                    {!! nl2br(e($consulta->mensaje)) !!}
                                </div>
                            </td>
                        </tr>
                    @endif

                    <!-- Botones de Acción (CTA) -->
                    <tr>
                        <td style="padding:16px 32px 32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <a href="mailto:{{ $consulta->email }}?subject={{ rawurlencode('Andean Exclusive Tours — Re: ' . ($consulta->tour ?: 'Tu consulta de viaje')) }}" style="display:inline-block;padding:12px 24px;background-color:#078f84;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;border-radius:6px;box-shadow:0 3px 10px rgba(7,143,132,0.3);">
                                            ✉ Responder al cliente
                                        </a>
                                    </td>
                                    <td width="12"></td>
                                    <td style="vertical-align:middle;">
                                        <a href="{{ route('consultas.show', $consulta) }}" style="display:inline-block;padding:11px 20px;background-color:#ffffff;color:#333333;border:1px solid #cbd5dc;text-decoration:none;font-weight:600;font-size:14px;border-radius:6px;">
                                            Abrir en el panel &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <div style="font-size:12px;color:#7b8e9b;line-height:1.45;">
                                💡 <strong>Respuesta rápida:</strong> Al hacer clic en <em>Responder</em> directamente en tu programa de correo (Gmail, Outlook), se enviará a <strong>{{ $consulta->email }}</strong>.
                            </div>
                        </td>
                    </tr>

                    <!-- Pie de página (Footer corporativo elegante) -->
                    <tr>
                        <td style="background-color:#182229;padding:26px 32px;color:#ffffff;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <div style="font-size:14px;font-weight:700;letter-spacing:0.6px;color:#ffffff;">
                                            ANDEAN EXCLUSIVE TOURS
                                        </div>
                                        <div style="font-size:12px;color:rgba(255,255,255,0.7);margin-top:3px;">
                                            Tradition of Excellence in Luxury Travel &middot; Cusco, Perú
                                        </div>
                                        <div style="font-size:12px;color:rgba(255,255,255,0.65);margin-top:8px;">
                                            Contacto: <a href="https://wa.me/51979721194" style="color:#4fe0d4;text-decoration:none;">+51 979 721 194</a>
                                            &middot; <a href="mailto:info@andeanexclusivetours.com" style="color:#4fe0d4;text-decoration:none;">info@andeanexclusivetours.com</a>
                                        </div>
                                        <div style="border-top:1px solid rgba(255,255,255,0.12);margin-top:14px;padding-top:12px;font-size:11px;color:rgba(255,255,255,0.4);">
                                            Notificación del sistema &middot; <a href="https://andeanexclusivetours.com" style="color:rgba(255,255,255,0.55);text-decoration:none;">andeanexclusivetours.com</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
