{{-- Orden de servicio para el proveedor (ServicioController@voucher). Dompdf solo admite estilos dentro del documento. --}}
@php
    $logoPath = collect([public_path('img/logo2-pdf.jpg'), public_path('img/logo2.png')])->first(fn ($p) => is_file($p));
    $logo = $logoPath
        ? 'data:' . (str_ends_with($logoPath, '.png') ? 'image/png' : 'image/jpeg') . ';base64,' . base64_encode((string) file_get_contents($logoPath))
        : null;
    $numero = $programa->codigo . '-' . str_pad((string) $servicio->id, 4, '0', STR_PAD_LEFT);
    $agente = $programa->agentes->first();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Orden de servicio {{ $numero }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; color: #2D3748; margin: 0; padding: 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        .head td { vertical-align: middle; padding: 6px 0 12px; border-bottom: 2px solid #078f84; }
        .head img { height: 46px; }
        .doc-title { text-align: right; }
        .doc-title h1 { margin: 0; font-size: 15pt; color: #078f84; letter-spacing: 0.04em; }
        .doc-title div { font-size: 9pt; color: #666; }
        h2 { margin: 18px 0 6px; font-size: 10.5pt; color: #078f84; text-transform: uppercase; letter-spacing: 0.06em; }
        .grid td { width: 50%; vertical-align: top; padding: 0 8px 0 0; }
        .box { border: 1px solid #dbe8e7; border-radius: 6px; padding: 8px 10px; }
        .label { display: block; font-size: 7.5pt; color: #666; text-transform: uppercase; letter-spacing: 0.05em; }
        .value { font-size: 10.5pt; font-weight: bold; }
        .data th { background: #f2fbfa; color: #056a61; font-size: 8.5pt; text-align: left; padding: 5px 6px; border-bottom: 1px solid #dbe8e7; }
        .data td { padding: 5px 6px; border-bottom: 1px solid #eef3f3; font-size: 9.5pt; }
        .note { background: #fff8e6; border: 1px solid #f0d9a6; border-radius: 6px; padding: 8px 10px; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8.5pt; font-weight: bold; background: #e7f4ec; color: #2f8f5b; }
        .status--pendiente { background: #fbefe2; color: #b25e09; }
        .status--solicitado { background: #e5eef8; color: #2563a8; }
        .status--cancelado { background: #eef2f2; color: #666; }
        .foot { margin-top: 26px; padding-top: 8px; border-top: 1px solid #dbe8e7; font-size: 8.5pt; color: #666; text-align: center; }
    </style>
</head>
<body>
    <table class="head">
        <tr>
            <td>
                @if ($logo)
                    <img src="{{ $logo }}" alt="Andean Exclusive Tours">
                @else
                    <strong>Andean Exclusive Tours</strong>
                @endif
            </td>
            <td class="doc-title">
                <h1>ORDEN DE SERVICIO</h1>
                <div>N.º {{ $numero }} · emitida el {{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <h2>Proveedor</h2>
    <div class="box">
        <span class="value">{{ $servicio->proveedor?->nombre ?? 'Sin proveedor asignado' }}</span>
        @if ($servicio->proveedor)
            <div>
                {{ $servicio->proveedor->categoria?->nombre }}
                @if ($servicio->proveedor->telefono) · Tel. {{ $servicio->proveedor->telefono }}@endif
                @if ($servicio->proveedor->correo) · {{ $servicio->proveedor->correo }}@endif
            </div>
        @endif
    </div>

    <h2>Servicio</h2>
    <table class="grid">
        <tr>
            <td>
                <div class="box">
                    <span class="label">Servicio</span>
                    <span class="value">{{ $servicio->titulo }}</span>
                    <div>{{ $servicio->tipo_etiqueta }}</div>
                </div>
            </td>
            <td>
                <div class="box">
                    <span class="label">Fecha y hora</span>
                    <span class="value">{{ ucfirst($servicio->fecha->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY')) }}{{ $servicio->hora_corta ? ' · ' . $servicio->hora_corta : '' }}</span>
                    <div>Estado: <span class="status status--{{ $servicio->estado }}">{{ \App\Models\Servicio::ESTADOS[$servicio->estado] }}</span>
                        @if ($servicio->confirmacion) · Confirmación <strong>{{ $servicio->confirmacion }}</strong>@endif</div>
                </div>
            </td>
        </tr>
    </table>
    @if ($servicio->punto_encuentro)
        <div class="box" style="margin-top: 8px;">
            <span class="label">Punto de encuentro / recojo</span>
            <span class="value">{{ $servicio->punto_encuentro }}</span>
        </div>
    @endif

    <h2>Grupo · {{ $programa->paxs->count() }} {{ $programa->paxs->count() === 1 ? 'pasajero' : 'pasajeros' }}</h2>
    <p style="margin: 0 0 6px;">Programa <strong>{{ $programa->codigo }}</strong> · {{ $programa->nombre }}@if ($programa->cliente) · Titular: {{ $programa->cliente->etiqueta }}@endif</p>
    @if ($programa->paxs->isNotEmpty())
        <table class="data">
            <tr><th>#</th><th>Nombre</th><th>Edad</th><th>Nacionalidad</th><th>Alimentación / salud</th></tr>
            @foreach ($programa->paxs as $pax)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pax->nombre }}</td>
                    <td>{{ $pax->edad ?? '—' }}</td>
                    <td>{{ $pax->nacionalidad ?? '—' }}</td>
                    <td>{{ $pax->alimentacion ?: '—' }}{{ $pax->condiciones_medicas ? ' · ' . $pax->condiciones_medicas : '' }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if ($servicio->notas)
        <h2>Indicaciones</h2>
        <div class="note">{!! nl2br(e($servicio->notas)) !!}</div>
    @endif

    <div class="foot">
        Andean Exclusive Tours · operaciones@andeanexclusive.com · +51 979 721 194
        @if ($agente) · Contacto: {{ $agente->nombre }}{{ $agente->telefono ? ' (' . $agente->telefono . ')' : '' }}@endif
        <br>Por favor confirme la recepción de esta orden indicando el número de orden.
    </div>
</body>
</html>
