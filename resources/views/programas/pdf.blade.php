<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $programa->nombre }}</title>
    <style type="text/css">
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #2D3748;
            line-height: 1.5;
            margin: 0;
            padding: 0 12px 20px 12px;
            background: #FFFFFF;
        }

        /* Separador superior sutil */
        .pdf-top-border {
            height: 2px;
            background: linear-gradient(90deg, #D4AF37 0%, #1A5F5B 50%, #D4AF37 100%);
            margin: -8px -12px 0 -12px;
        }

        /* Cabecera: tabla para Dompdf — logo + nombre misma línea; derecha alineada */
        table.pdf-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border-bottom: 1px solid #E2E8F0;
        }

        table.pdf-header>tbody>tr>td {
            vertical-align: middle;
            padding: 16px 0;
        }

        td.header-main {
            width: 50%;
        }

        table.header-inline {
            border-collapse: collapse;
            width: auto;
        }

        table.header-inline td {
            vertical-align: middle;
            padding: 0;
        }

        td.logo-cell {
            padding-right: 14px;
            width: 1%;
        }

        td.logo-cell img {
            width: 180px;
            max-width: 180px;
            height: auto;
            opacity: 0.9;
            display: block;
        }

        td.title-area {
            text-align: right;
            vertical-align: middle;
            width: 50%;
            padding-left: 12px;
        }

        .title-area .brand-sub {
            font-size: 8pt;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #D4AF37;
            margin: 0;
            line-height: 1.35;
        }

        td.program-title-cell h1,
        h1 {
            font-size: 16pt;
            font-weight: 700;
            color: #1A5F5B;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .brand-sub {
            font-size: 8pt;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #D4AF37;
            margin-bottom: 4px;
        }

        /* Metadatos: tabla para centrado fiable en Dompdf */
        table.meta-row-wrap {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            margin-bottom: 4px;
        }

        table.meta-row-wrap td.meta-row-cell {
            text-align: center;
            vertical-align: middle;
            padding: 12px 10px;
        }

        .meta-chip {
            display: inline-block;
            font-size: 9pt;
            color: #2D3748;
            background-color: #FFFFFF;
            border: 1px solid #d4af376b;
            padding: 6px 14px;
            border-radius: 4px;
            margin: 4px 5px;
            vertical-align: middle;
            box-shadow: 0 1px 0 rgba(26, 95, 91, 0.08);
        }

        .meta-chip strong {
            color: #1A5F5B;
            font-weight: 700;
        }

        /* Títulos de sección */
        h2 {
            font-size: 11.5pt;
            font-weight: 700;
            color: #1A5F5B;
            margin: 24px 0 12px 0;
            padding: 0 0 6px 0;
            border-bottom: 2px solid #D4AF37;
            letter-spacing: 0.03em;
        }

        h2:first-of-type {
            margin-top: 16px;
        }

        h3 {
            font-size: 10.5pt;
            font-weight: 700;
            color: #1A5F5B;
            margin: 14px 0 8px 0;
            padding-left: 8px;
            border-left: 3px solid #D4AF37;
        }

        h3.subtle {
            color: #4A5568;
            border-left-color: #1A5F5B;
        }

        /* Tablas elegantes */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 16px 0;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        table.data th,
        table.data td {
            border: 1px solid #E2E8F0;
            padding: 8px 10px;
            vertical-align: top;
            text-align: left;
        }

        table.data th {
            background-color: #1A5F5B;
            color: #FFFFFF;
            font-weight: 600;
            font-size: 9pt;
            letter-spacing: 0.02em;
        }

        table.data tbody tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        table.data tbody tr:nth-child(odd) {
            background-color: #FFFFFF;
        }

        .highlight-cell {
            background-color: #F0F9F4 !important;
            font-weight: 700;
            color: #1A5F5B;
        }

        /* Listas */
        ul.compact {
            margin: 4px 0 12px 0;
            padding-left: 20px;
        }

        ul.compact li {
            margin: 4px 0;
            color: #2D3748;
        }

        /* Presentación */
        .presentacion {
            margin: 8px 0 12px 0;
            padding: 12px 16px;
            background-color: #F8FAFC;
            border-left: 3px solid #D4AF37;
            border-radius: 0 6px 6px 0;
        }

        .muted {
            color: #718096;
            font-size: 9pt;
        }

        .empty-note {
            color: #A0AEC0;
            font-style: italic;
            font-size: 9.5pt;
            margin: 8px 0 12px 0;
            padding-left: 4px;
        }

        .hotel-block {
            margin-bottom: 16px;
        }

        /* Pie de página: bloque centrado, alto contraste */
        table.footer-wrap {
            width: 100%;
            border-collapse: collapse;
            margin-top: 36px;
        }

        table.footer-wrap td.footer-cell {
            text-align: center;
            vertical-align: middle;
            background-color: #EDF5F4;
            border-top: 3px solid #D4AF37;
            border-bottom: 2px solid #1A5F5B;
            padding: 18px 22px 20px 22px;
        }

        .footer-brand {
            font-size: 11pt;
            font-weight: 700;
            color: #1A5F5B;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .footer-contact-lines {
            font-size: 9.5pt;
            color: #2D3748;
            line-height: 1.7;
            font-weight: 500;
        }

        .footer-generated {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #C9A227;
            font-size: 9pt;
            color: #1A5F5B;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .footer-generated .footer-accent {
            color: #B8860B;
            font-weight: 700;
        }

        /* Badge de precio */
        .price-badge {
            font-weight: 700;
            color: #1A5F5B;
        }
    </style>
</head>

<body>
    @php
        $fechasPorHabitacion = $programa->habitacionesFechas->keyBy('habitacion_id');
        $idiomas = ['es' => 'Español', 'en' => 'Inglés', 'pt' => 'Portugués', 'fr' => 'Francés'];

        // Preferir JPG generado (sin GD en Apache); si no existe, usar logo2.png (requiere GD en Dompdf).
        $logoPath = collect([public_path('img/logo2-pdf.jpg'), public_path('img/logo2.png')])->first(
            fn($p) => is_file($p),
        );

        $logoDataUri = '';
        if ($logoPath) {
            $logoExt = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $logoMime = $logoExt === 'png' ? 'image/png' : 'image/jpeg';
            $logoDataUri = 'data:' . $logoMime . ';base64,' . base64_encode((string) file_get_contents($logoPath));
        }
    @endphp

    <div class="pdf-top-border"></div>
    <!-- Cabecera: logo + nombre del programa en una fila; “Programa de viaje” a la derecha -->
    <table class="pdf-header">
        <tr>
            <td class="header-main">
                <table class="header-inline">
                    <tr>
                        <td class="logo-cell">
                            @if ($logoDataUri !== '')
                                <img src="{{ $logoDataUri }}" alt="Andean Exclusive Tours">
                            @else
                                <span class="brand-sub" style="display:block;white-space:nowrap;">
                                    Andean Exclusive Tours
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td class="title-area">
                <h1>{{ $programa->nombre }}</h1>
                <div class="brand-sub">Programa de viaje</div>
            </td>
        </tr>
    </table>

    <!-- METADATOS (centrados) -->
    <table class="meta-row-wrap" cellpadding="0" cellspacing="0">
        <tr>
            <td class="meta-row-cell" align="center">
                <span class="meta-chip"><strong>Código:</strong> {{ $programa->codigo }}</span>
                @if ($programa->email)
                    <span class="meta-chip"><strong>Email:</strong> {{ $programa->email }}</span>
                @endif
                @if ($programa->lang)
                    <span class="meta-chip"><strong>Idioma:</strong>
                        {{ $idiomas[$programa->lang] ?? $programa->lang }}</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- DATOS GENERALES -->
    <h2>Datos generales</h2>
    <table class="data">
        <tr>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Duración</th>
        </tr>
        <tr>
            <td>
                {{ $programa->inicio ? \Carbon\Carbon::parse($programa->inicio)->format('d/m/Y') : '—' }}
            </td>
            <td>
                {{ $programa->fin ? \Carbon\Carbon::parse($programa->fin)->format('d/m/Y') : '—' }}
            </td>
            <td>
                @if ($programa->inicio && $programa->fin)
                    @php
                        $dias =
                            \Carbon\Carbon::parse($programa->inicio)->diffInDays(
                                \Carbon\Carbon::parse($programa->fin),
                            ) + 1;
                    @endphp
                    <strong>{{ $dias }}</strong> {{ $dias === 1 ? 'día' : 'días' }}
                @else
                    —
                @endif
            </td>
        </tr>
    </table>

    <!-- PRECIOS -->
    @if ($programa->precioAdulto > 0 || $programa->precioChild > 0)
        @php
            $totalAdultos = $programa->paxs->where('edad', '>=', 12)->count();
            $totalNinos = $programa->paxs->whereBetween('edad', [5, 11])->count();
            $totalInfantes = $programa->paxs->where('edad', '<', 5)->count();
            $precioAdulto = floatval($programa->precioAdulto);
            $precioChild = floatval($programa->precioChild);
            $subtotalAdultos = $totalAdultos * $precioAdulto;
            $subtotalNinos = $totalNinos * $precioChild;
            $totalGeneral = $subtotalAdultos + $subtotalNinos;
        @endphp
        <h2>Precios</h2>
        <table class="data">
            <tr>
                <th>Tarifa adulto (≥12)</th>
                <th>Tarifa niño (5–11)</th>
                <th>Resumen pasajeros</th>
                <th>Total estimado</th>
            </tr>
            <tr>
                <td>{{ $precioAdulto > 0 ? '$' . number_format($precioAdulto, 2) : '—' }}</td>
                <td>{{ $precioChild > 0 ? '$' . number_format($precioChild, 2) : '—' }}</td>
                <td>
                    <span class="muted">{{ $totalAdultos }} adultos · {{ $totalNinos }} niños ·
                        {{ $totalInfantes }} infantes</span>
                </td>
                <td class="highlight-cell">{{ $totalGeneral > 0 ? '$' . number_format($totalGeneral, 2) : '—' }}</td>
            </tr>
        </table>
    @endif

    <!-- PRESENTACIÓN -->
    @if (!empty($presentacionPdf))
        <h2>Presentación</h2>
        <div class="presentacion">{!! $presentacionPdf !!}</div>
    @endif

    <!-- AGENTES -->
    <h2>Agentes</h2>
    @if ($programa->agentes->isEmpty())
        <p class="empty-note">Sin agentes asignados.</p>
    @else
        <table class="data">
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
            @foreach ($programa->agentes as $agente)
                <tr>
                    <td>{{ $agente->nombre }}</td>
                    <td>{{ $agente->telefono ?? '—' }}</td>
                    <td>{{ $agente->email ?? '—' }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <!-- PROVEEDORES -->
    <h2>Proveedores</h2>
    @php
        $proveedoresPorCategoria = $programa->proveedores->groupBy(
            fn($p) => optional($p->categoria)->nombre ?? 'Sin categoría',
        );
    @endphp
    @if ($proveedoresPorCategoria->isEmpty())
        <p class="empty-note">Sin proveedores.</p>
    @else
        @foreach ($proveedoresPorCategoria as $categoria => $proveedores)
            <h3 class="subtle">{{ $categoria }}</h3>
            <ul class="compact">
                @foreach ($proveedores as $proveedor)
                    <li>
                        {{ $proveedor->nombre }}
                        @if ($proveedor->telefono)
                            <span class="muted"> — {{ $proveedor->telefono }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endforeach
    @endif

    <!-- HOTELES Y HABITACIONES -->
    <h2>Hoteles y habitaciones</h2>
    @if ($programa->habitaciones->isEmpty())
        <p class="empty-note">Sin hoteles registrados.</p>
    @else
        @foreach ($programa->habitaciones->groupBy(fn($h) => optional($h->hotel)->id) as $hotelId => $habitaciones)
            @php $hotel = $habitaciones->first()->hotel; @endphp
            @if ($hotel)
                <div class="hotel-block">
                    <h3>{{ $hotel->nombre }}</h3>
                    <p class="muted" style="margin: 0 0 8px 10px;">
                        @if ($hotel->telefono)
                            <strong style="color: #1A5F5B;">Tel.</strong> {{ $hotel->telefono }}
                        @endif
                        @if ($hotel->direccion)
                            @if ($hotel->telefono)
                                &nbsp;·&nbsp;
                            @endif
                            {{ $hotel->direccion }}
                        @endif
                    </p>
                    <table class="data">
                        <tr>
                            <th>Habitación</th>
                            <th>Ingreso</th>
                            <th>Salida</th>
                            <th>Noches</th>
                        </tr>
                        @foreach ($habitaciones as $habitacion)
                            @php $fecha = $fechasPorHabitacion->get($habitacion->id); @endphp
                            <tr>
                                <td>{{ $habitacion->tipo }}</td>
                                <td>
                                    {{ $fecha ? \Carbon\Carbon::parse($fecha->fecha_ingreso)->format('d/m/Y') : '—' }}
                                </td>
                                <td>
                                    {{ $fecha ? \Carbon\Carbon::parse($fecha->fecha_salida)->format('d/m/Y') : '—' }}
                                </td>
                                <td>
                                    @if ($fecha)
                                        @php
                                            $noches = \Carbon\Carbon::parse($fecha->fecha_ingreso)->diffInDays(
                                                \Carbon\Carbon::parse($fecha->fecha_salida),
                                            );
                                        @endphp
                                        <strong>{{ $noches }}</strong>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        @endforeach
    @endif

    <!-- PASAJEROS -->
    <h2>Pasajeros (Pax)</h2>
    @if ($programa->paxs->isEmpty())
        <p class="empty-note">Sin pasajeros registrados.</p>
    @else
        <table class="data">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Nacionalidad</th>
                <th>Alimentación</th>
            </tr>
            @foreach ($programa->paxs as $pax)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pax->nombre }}</td>
                    <td>{{ $pax->edad }}</td>
                    <td>{{ $pax->nacionalidad }}</td>
                    <td>{{ $pax->alimentacion ?? '—' }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <!-- PIE DE PÁGINA -->
    <table class="footer-wrap" cellpadding="0" cellspacing="0">
        <tr>
            <td class="footer-cell" align="center">
                <div class="footer-brand">Andean Exclusive Tours</div>
                <div class="footer-contact-lines">
                    operaciones@andeanexclusive.com · reservas@andeanexclusive.com<br>
                    +51 979 721 194
                </div>
                <div class="footer-generated">
                    <span class="footer-accent">✦</span> Documento generado el {{ now()->format('d/m/Y H:i') }}
                    <span class="footer-accent">✦</span>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
