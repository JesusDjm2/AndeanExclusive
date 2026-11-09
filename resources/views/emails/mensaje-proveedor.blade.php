{{-- Mensaje de Reservas a un proveedor (App\Mail\MensajeProveedorMailable). Los clientes de correo solo respetan estilos en línea. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asunto }}</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f4;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f0f4f4;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:#0f172a;padding:20px 32px;color:#ffffff;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;">
                            <strong style="font-size:17px;">Andean Exclusive Tours</strong>
                            <div style="font-size:12px;opacity:.8;margin-top:3px;letter-spacing:.04em;text-transform:uppercase;">Reservas</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;font-size:15px;line-height:1.65;color:#333333;">
                            {!! nl2br(e($cuerpo)) !!}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px;border-top:1px solid #eef2f2;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;font-size:12px;color:#7a8a8a;">
                            Responda a este correo para que su respuesta llegue a {{ config('mail.reservas.address') }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
