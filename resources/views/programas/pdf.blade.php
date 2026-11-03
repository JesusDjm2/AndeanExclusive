{{--
    PDF del programa para el cliente (ProgramaController::buildProgramaPdf), una sola plantilla ES/EN según $programa->lang.
    Mismo contenido y estilo que el itinerario web (programas/show). Dompdf: maquetación con tablas y estilos en el documento.
    Variables: $programa, $presentacionPdf (presentación sin imágenes si no hay GD).
--}}
@php
    $isEn = $programa->lang === 'en';
    $locale = $isEn ? 'en' : 'es';
    $t = $isEn ? [
        'quote' => 'Travel proposal', 'confirmed' => 'Confirmed program', 'cancelled' => 'Cancelled program',
        'quote_note' => 'This is a proposal: services and prices are subject to availability until the deposit is received.',
        'code' => 'Ref.', 'issued' => 'Issued', 'dates' => 'Dates', 'duration' => 'Duration', 'travelers' => 'Travelers',
        'day' => 'Day', 'days' => 'days', 'night' => 'night', 'nights' => 'nights',
        'adult' => 'adult', 'adults' => 'adults', 'child' => 'child', 'children' => 'children', 'infant' => 'infant', 'infants' => 'infants',
        'online' => 'Your itinerary online', 'online_text' => 'Always up to date. You can also complete your travelers’ details there.',
        'overview' => 'Trip overview', 'daybyday' => 'Day by day', 'overnight' => 'Overnight', 'stay' => 'Accommodation',
        'room' => 'Room', 'in' => 'Check-in', 'out' => 'Check-out', 'tbc' => 'To be confirmed',
        'pax' => 'Travelers', 'name' => 'Name', 'age' => 'Age', 'nationality' => 'Nationality', 'diet' => 'Diet',
        'prices' => 'Price', 'rate_adult' => 'Adult rate (12+)', 'rate_child' => 'Child rate (5–11)', 'single' => 'Single room supplement',
        'infants_free' => 'Children under 5 travel free.', 'total' => 'Total',
        'account' => 'Account statement', 'paid' => 'Paid', 'balance' => 'Balance', 'deposit' => 'Deposit (50%)',
        'deposit_due' => 'Deposit due', 'balance_due' => 'Balance due', 'covered' => 'Received', 'pending' => 'Pending',
        'team' => 'Your travel team', 'page' => 'Page',
    ] : [
        'quote' => 'Propuesta de viaje', 'confirmed' => 'Programa confirmado', 'cancelled' => 'Programa cancelado',
        'quote_note' => 'Esta es una propuesta: los servicios y precios están sujetos a disponibilidad hasta recibir el depósito.',
        'code' => 'Ref.', 'issued' => 'Emitido', 'dates' => 'Fechas', 'duration' => 'Duración', 'travelers' => 'Viajeros',
        'day' => 'Día', 'days' => 'días', 'night' => 'noche', 'nights' => 'noches',
        'adult' => 'adulto', 'adults' => 'adultos', 'child' => 'niño', 'children' => 'niños', 'infant' => 'infante', 'infants' => 'infantes',
        'online' => 'Su itinerario en línea', 'online_text' => 'Siempre actualizado. Allí también puede completar los datos de los viajeros.',
        'overview' => 'Descripción del viaje', 'daybyday' => 'Día a día', 'overnight' => 'Alojamiento', 'stay' => 'Alojamiento',
        'room' => 'Habitación', 'in' => 'Ingreso', 'out' => 'Salida', 'tbc' => 'Por confirmar',
        'pax' => 'Viajeros', 'name' => 'Nombre', 'age' => 'Edad', 'nationality' => 'Nacionalidad', 'diet' => 'Alimentación',
        'prices' => 'Precio', 'rate_adult' => 'Tarifa adulto (12+)', 'rate_child' => 'Tarifa niño (5–11)', 'single' => 'Suplemento habitación individual',
        'infants_free' => 'Los menores de 5 años no pagan.', 'total' => 'Total',
        'account' => 'Estado de cuenta', 'paid' => 'Pagado', 'balance' => 'Saldo', 'deposit' => 'Depósito (50%)',
        'deposit_due' => 'Depósito hasta el', 'balance_due' => 'Saldo hasta el', 'covered' => 'Recibido', 'pending' => 'Pendiente',
        'team' => 'Su equipo de viaje', 'page' => 'Página',
    ];
    $plural = fn (int $n, string $uno, string $varios) => $n . ' ' . ($n === 1 ? $t[$uno] : $t[$varios]);
    $fecha = fn ($c, string $es = 'D [de] MMMM [de] YYYY', string $en = 'MMMM D, YYYY') => $c ? \Carbon\Carbon::parse($c)->locale($locale)->isoFormat($isEn ? $en : $es) : '—';
    $usd = fn ($n) => 'US$ ' . number_format((float) $n, 2);

    $estadoDoc = match ($programa->estado) {
        'confirmado' => ['confirmed', '#2f8f5b'],
        'cancelado' => ['cancelled', '#c2422f'],
        default => ['quote', '#b25e09'],
    };
    $duracion = $programa->duracionDias();
    $presupuesto = $programa->presupuesto();
    $cobro = $programa->cobranza();
    $grupo = collect([
        $presupuesto['adultos'] ? $plural($presupuesto['adultos'], 'adult', 'adults') : null,
        $presupuesto['ninos'] ? $plural($presupuesto['ninos'], 'child', 'children') : null,
        $presupuesto['infantes'] ? $plural($presupuesto['infantes'], 'infant', 'infants') : null,
    ])->filter()->implode(' · ');
    $dias = $programa->itinerario(soloCliente: true)['dias']->filter(fn ($d) => $d['dia'] || $d['servicios']->isNotEmpty() || $d['hotel']);
    $hoteles = $programa->alojamiento();
    $paises = \App\Support\Paises::enIdioma($locale);

    // Portada: imagen compuesta (o fondo oscuro liso si no se puede), logo blanco y título que se adapta a su largo.
    $portada = \App\Support\Portadas::paraPdf($programa->portada);
    $logoBlanco = extension_loaded('gd') && is_file(public_path('img/Logo-blanco-andean.png'))
        ? 'data:image/png;base64,' . base64_encode((string) file_get_contents(public_path('img/Logo-blanco-andean.png')))
        : null;
    $largoTitulo = mb_strlen($programa->nombre);
    $tituloPt = $largoTitulo > 55 ? 22 : ($largoTitulo > 32 ? 27 : 33);

    // Logo en JPG (Dompdf sin GD no lee PNG); si no existe, el PNG.
    $logoPath = collect([public_path('img/logo2-pdf.jpg'), public_path('img/logo2.png')])->first(fn ($p) => is_file($p));
    $logo = $logoPath ? 'data:' . (str_ends_with($logoPath, '.png') ? 'image/png' : 'image/jpeg') . ';base64,' . base64_encode((string) file_get_contents($logoPath)) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $programa->nombre }}</title>
    <style>
        @page { margin: 26mm 16mm 20mm 16mm; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; font-size: 9.5pt; line-height: 1.55; color: #1f2a2c; }
        table { width: 100%; border-collapse: collapse; }

        /* Cabecera y pie fijos en cada página */
        .page-header { position: fixed; top: -18mm; left: 0; right: 0; height: 12mm; border-bottom: 1px solid #e3ecec; }
        .page-header img { height: 9mm; }
        .page-header td { vertical-align: middle; }
        .page-header .ref { text-align: right; font-size: 8pt; color: #5b6b6d; }
        .page-footer { position: fixed; bottom: -13mm; left: 0; right: 0; height: 8mm; border-top: 1px solid #e3ecec; font-size: 7.5pt; color: #5b6b6d; }
        .page-footer td { padding-top: 2mm; }
        .page-footer .num { text-align: right; }
        .page-footer .num:after { content: counter(page); }

        /* Portada: página completa, sin márgenes (compensa los de @page). La imagen la compone App\Support\Portadas. */
        .cover { position: absolute; top: -26mm; left: -16mm; width: 210mm; height: 297mm; background: #0b1d1f; color: #ffffff; overflow: hidden; }
        .cover__bg { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .cover__logo { position: absolute; top: 12mm; left: 16mm; width: 46mm; }
        .cover__brand { position: absolute; top: 14mm; left: 16mm; font-size: 12pt; font-weight: bold; }
        .cover__info { position: absolute; left: 16mm; bottom: 13mm; width: 178mm; }
        .cover-end { page-break-after: always; }
        .status { display: inline-block; padding: 1.2mm 3.5mm; border-radius: 3mm; font-size: 7.5pt; font-weight: bold; letter-spacing: 0.7pt; text-transform: uppercase; color: #ffffff; }
        .cover h1 { margin: 4mm 0 1.5mm; line-height: 1.12; font-weight: bold; color: #ffffff; }
        .cover .dates { margin: 0; font-size: 12pt; color: #d5e8e6; }
        .facts { margin-top: 7mm; }
        .facts td { width: 25%; padding: 3mm 3.5mm; border: 1px solid #2a4a4c; background: #13292b; vertical-align: top; }
        .facts .label { display: block; font-size: 7pt; text-transform: uppercase; letter-spacing: 0.6pt; color: #9cc4c0; }
        .facts .value { display: block; margin-top: 0.5mm; font-size: 11pt; font-weight: bold; color: #ffffff; }
        .facts .sub { display: block; font-size: 7.5pt; color: #cfe3e1; }
        .facts .price { background: #0f3836; border-color: #1f6e68; }
        .facts .price .value { color: #4fe0d4; }
        .note { margin-top: 4mm; padding: 2.5mm 4mm; border-left: 1mm solid #e0963f; background: #2b2417; font-size: 8.5pt; color: #f3dcb4; }
        .online { margin-top: 4mm; padding: 2.5mm 4mm; border: 1px solid #2a4a4c; background: #13292b; font-size: 8.5pt; color: #d5e8e6; }
        .online a { color: #4fe0d4; font-weight: bold; text-decoration: none; }
        .online small { display: block; margin-top: 0.5mm; font-size: 7.5pt; color: #9cc4c0; }

        /* Secciones */
        h2 { page-break-after: avoid; margin: 8mm 0 3mm; padding-bottom: 1.5mm; border-bottom: 0.6mm solid #078f84; font-size: 13pt; color: #1f2a2c; }
        .prose { color: #34474a; }
        .prose p { margin: 0 0 2.5mm; }
        .prose img { max-width: 100%; }

        .day { margin-bottom: 3.5mm; page-break-inside: avoid; }
        .day-num { width: 15mm; vertical-align: top; }
        .day-num div { width: 12mm; padding: 1.5mm 0; border-radius: 2mm; background: #078f84; color: #ffffff; text-align: center; }
        .day-num small { display: block; font-size: 6.5pt; text-transform: uppercase; letter-spacing: 0.5pt; }
        .day-num strong { display: block; font-size: 13pt; line-height: 1.1; }
        .day-body { padding: 0 0 0 1mm; vertical-align: top; }
        .day-date { margin: 0; font-size: 7.5pt; font-weight: bold; color: #078f84; text-transform: uppercase; letter-spacing: 0.5pt; }
        .day-title { margin: 0.5mm 0 1.5mm; font-size: 11pt; font-weight: bold; }
        .day-text { margin: 0 0 2mm; color: #34474a; }
        .svc td { padding: 1.4mm 2mm; border-bottom: 1px solid #edf3f3; vertical-align: top; }
        .svc .time { width: 14mm; color: #078f84; font-weight: bold; }
        .svc .desc { display: block; font-size: 8pt; color: #5b6b6d; }
        .overnight { margin: 1.5mm 0 0; font-size: 8.5pt; color: #5b6b6d; }

        .data th { padding: 2mm 2.5mm; background: #f2fbfa; border-bottom: 1px solid #cde8e5; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.4pt; color: #056a61; text-align: left; }
        .data td { padding: 2mm 2.5mm; border-bottom: 1px solid #edf3f3; vertical-align: top; }
        .data .num { text-align: right; white-space: nowrap; }
        .hotel { margin-bottom: 4mm; page-break-inside: avoid; }
        .hotel-name { margin: 0 0 0.5mm; font-size: 10.5pt; font-weight: bold; }
        .muted { color: #5b6b6d; font-size: 8.5pt; }

        .totals td { padding: 2mm 2.5mm; }
        .totals .total td { border-top: 0.5mm solid #078f84; font-size: 12pt; font-weight: bold; color: #078f84; }
        .account td { width: 33.3%; padding: 3mm; border: 1px solid #e3ecec; vertical-align: top; }
        .account .label { display: block; font-size: 7pt; text-transform: uppercase; letter-spacing: 0.5pt; color: #5b6b6d; }
        .account .value { display: block; font-size: 12pt; font-weight: bold; }
        .ok { color: #2f8f5b; }
        .due { color: #b25e09; }

        .team td { padding: 2mm 0; vertical-align: top; }
        .avoid { page-break-inside: avoid; }
    </style>
</head>
<body>
    {{-- Portada: toda la información clave en la primera página --}}
    <div class="cover">
        @if ($portada)
            <img src="{{ $portada }}" class="cover__bg" alt="">
        @endif
        @if ($logoBlanco)
            <img src="{{ $logoBlanco }}" class="cover__logo" alt="Andean Exclusive Tours">
        @else
            <span class="cover__brand">Andean Exclusive Tours</span>
        @endif

        <div class="cover__info">
            <span class="status" style="background: {{ $estadoDoc[1] }};">{{ $t[$estadoDoc[0]] }}</span>
            <h1 style="font-size: {{ $tituloPt }}pt;">{{ $programa->nombre }}</h1>
            @if ($programa->inicio && $programa->fin)
                <p class="dates">{{ ucfirst($fecha($programa->inicio, 'D [de] MMMM', 'MMMM D')) }} – {{ $fecha($programa->fin) }}</p>
            @endif

            <table class="facts">
                <tr>
                    <td><span class="label">{{ $t['duration'] }}</span><span class="value">{{ $duracion ? $plural($duracion, 'day', 'days') : '—' }}</span>
                        @if ($duracion > 1)<span class="sub">{{ $plural($duracion - 1, 'night', 'nights') }}</span>@endif</td>
                    <td><span class="label">{{ $t['travelers'] }}</span><span class="value">{{ $programa->paxs->count() }}</span><span class="sub">{{ $grupo ?: '—' }}</span></td>
                    <td><span class="label">{{ $t['code'] }}</span><span class="value">{{ $programa->codigo }}</span><span class="sub">{{ $programa->cliente?->nombre }}</span></td>
                    <td class="price"><span class="label">{{ $t['total'] }}</span><span class="value">{{ $presupuesto['total'] > 0 ? $usd($presupuesto['total']) : '—' }}</span></td>
                </tr>
            </table>

            @if ($programa->estado === 'cotizacion')
                <div class="note">{{ $t['quote_note'] }}</div>
            @endif
            @if ($programa->estado !== 'cancelado')
                <div class="online">
                    <strong>{{ $t['online'] }}:</strong> <a href="{{ route('itinerario', $programa) }}">{{ route('itinerario', $programa) }}</a>
                    <small>{{ $t['online_text'] }}</small>
                </div>
            @endif
        </div>
    </div>
    <div class="cover-end"></div>

    <div class="page-header">
        <table><tr>
            <td>@if ($logo)<img src="{{ $logo }}" alt="Andean Exclusive Tours">@else<strong>Andean Exclusive Tours</strong>@endif</td>
            <td class="ref">{{ $programa->nombre }} · {{ $t['code'] }} {{ $programa->codigo }}</td>
        </tr></table>
    </div>
    <div class="page-footer">
        <table><tr>
            <td>Andean Exclusive Tours · operaciones@andeanexclusive.com · +51 979 721 194 · {{ $t['issued'] }} {{ now()->format('d/m/Y') }}</td>
            <td class="num">{{ $t['page'] }} </td>
        </tr></table>
    </div>

    {{-- Descripción --}}
    @if (! empty($presentacionPdf))
        <h2>{{ $t['overview'] }}</h2>
        <div class="prose">{!! $presentacionPdf !!}</div>
    @endif

    {{-- Día a día --}}
    @if ($dias->isNotEmpty())
        <h2>{{ $t['daybyday'] }}</h2>
        @foreach ($dias as $dia)
            <table class="day">
                <tr>
                    <td class="day-num"><div><small>{{ $t['day'] }}</small><strong>{{ $dia['numero'] }}</strong></div></td>
                    <td class="day-body">
                        <p class="day-date">{{ $dia['fecha']->locale($locale)->isoFormat($isEn ? 'dddd, MMMM D' : 'dddd D [de] MMMM') }}</p>
                        <p class="day-title">{{ $dia['dia']?->titulo ?: $t['day'] . ' ' . $dia['numero'] }}</p>
                        @if ($dia['dia']?->descripcion)
                            <p class="day-text">{!! nl2br(e($dia['dia']->descripcion)) !!}</p>
                        @endif
                        @if ($dia['servicios']->isNotEmpty())
                            <table class="svc">
                                @foreach ($dia['servicios'] as $servicio)
                                    <tr>
                                        <td class="time">{{ $servicio->hora_corta }}</td>
                                        <td>{{ $servicio->titulo ?: $servicio->tipo_etiqueta }}@if ($servicio->descripcion)<span class="desc">{{ $servicio->descripcion }}</span>@endif</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        @if ($dia['hotel'])
                            <p class="overnight">{{ $t['overnight'] }}: <strong>{{ $dia['hotel'] }}</strong></p>
                        @endif
                    </td>
                </tr>
            </table>
        @endforeach
    @endif

    {{-- Alojamiento --}}
    @if ($hoteles->isNotEmpty())
        <h2>{{ $t['stay'] }}</h2>
        @foreach ($hoteles as ['hotel' => $hotel, 'estadias' => $estadias])
            <div class="hotel">
                <p class="hotel-name">{{ $hotel->nombre }}</p>
                <p class="muted" style="margin: 0 0 1.5mm;">{{ collect([$hotel->direccion, $hotel->telefono])->filter()->implode(' · ') }}</p>
                <table class="data">
                    <tr><th>{{ $t['room'] }}</th><th>{{ $t['in'] }}</th><th>{{ $t['out'] }}</th><th class="num">{{ ucfirst($t['nights']) }}</th></tr>
                    @foreach ($estadias as $estadia)
                        @php $f = $estadia->fecha_ingreso ? $estadia : null; @endphp
                        <tr>
                            <td>{{ $estadia->etiqueta }}</td>
                            <td>{{ $f ? \Carbon\Carbon::parse($f->fecha_ingreso)->format('d/m/Y') : $t['tbc'] }}</td>
                            <td>{{ $f ? \Carbon\Carbon::parse($f->fecha_salida)->format('d/m/Y') : '—' }}</td>
                            <td class="num">{{ $f ? (int) \Carbon\Carbon::parse($f->fecha_ingreso)->diffInDays(\Carbon\Carbon::parse($f->fecha_salida)) : '—' }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    @endif

    {{-- Viajeros --}}
    @if ($programa->paxs->isNotEmpty())
        <div class="avoid">
        <h2>{{ $t['pax'] }}</h2>
        <table class="data">
            <tr><th>#</th><th>{{ $t['name'] }}</th><th>{{ $t['age'] }}</th><th>{{ $t['nationality'] }}</th><th>{{ $t['diet'] }}</th></tr>
            @foreach ($programa->paxs as $pax)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pax->nombre }}</td>
                    <td>{{ $pax->edad ?? '—' }}</td>
                    <td>{{ $pax->pais ? ($paises[$pax->pais] ?? $pax->pais) : '—' }}</td>
                    <td>{{ $pax->alimentacionEn($locale) ?? '—' }}</td>
                </tr>
            @endforeach
        </table>
        </div>
    @endif

    {{-- Precio y estado de cuenta --}}
    @if ($presupuesto['total'] > 0)
        <div class="avoid">
            <h2>{{ $t['prices'] }}</h2>
            <table class="totals">
                @if ($presupuesto['adultos'])
                    <tr><td>{{ $t['rate_adult'] }} × {{ $presupuesto['adultos'] }}</td><td class="num" style="text-align:right;">{{ $usd($presupuesto['ventaAdulto']) }} × {{ $presupuesto['adultos'] }} = {{ $usd($presupuesto['ventaAdulto'] * $presupuesto['adultos']) }}</td></tr>
                @endif
                @if ($presupuesto['ninos'])
                    <tr><td>{{ $t['rate_child'] }} × {{ $presupuesto['ninos'] }}</td><td style="text-align:right;">{{ $usd($presupuesto['ventaNino']) }} × {{ $presupuesto['ninos'] }} = {{ $usd($presupuesto['ventaNino'] * $presupuesto['ninos']) }}</td></tr>
                @endif
                @if ($presupuesto['suplemento'] > 0)
                    <tr><td>{{ $t['single'] }} × {{ $presupuesto['habitacionesIndividuales'] }}</td><td style="text-align:right;">{{ $usd($presupuesto['suplemento']) }}</td></tr>
                @endif
                <tr class="total"><td>{{ $t['total'] }}</td><td style="text-align:right;">{{ $usd($presupuesto['total']) }}</td></tr>
            </table>
            @if ($presupuesto['infantes'])
                <p class="muted" style="margin: 1.5mm 0 0;">{{ $t['infants_free'] }}</p>
            @endif

            @if ($programa->estado === 'confirmado')
                <h2>{{ $t['account'] }}</h2>
                <table class="account">
                    <tr>
                        <td><span class="label">{{ $t['paid'] }}</span><span class="value ok">{{ $usd($cobro['pagado']) }}</span></td>
                        <td><span class="label">{{ $t['balance'] }}</span><span class="value {{ $cobro['saldo'] > 0 ? 'due' : 'ok' }}">{{ $usd($cobro['saldo']) }}</span>
                            @if ($cobro['saldo'] > 0 && $cobro['depositoCubierto'] && $cobro['vencimientoSaldo'])<span class="muted">{{ $t['balance_due'] }} {{ $fecha($cobro['vencimientoSaldo']) }}</span>@endif
                        </td>
                        <td><span class="label">{{ $t['deposit'] }}</span>
                            <span class="value {{ $cobro['depositoCubierto'] ? 'ok' : 'due' }}">{{ $cobro['depositoCubierto'] ? $t['covered'] : $t['pending'] }}</span>
                            @if (! $cobro['depositoCubierto'] && $cobro['vencimientoDeposito'])<span class="muted">{{ $t['deposit_due'] }} {{ $fecha($cobro['vencimientoDeposito']) }}</span>@endif
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    @endif

    {{-- Equipo --}}
    @if ($programa->agentes->isNotEmpty())
        <div class="avoid">
            <h2>{{ $t['team'] }}</h2>
            <table class="team">
                @foreach ($programa->agentes as $agente)
                    <tr><td><strong>{{ $agente->nombre }}</strong><br><span class="muted">{{ collect([$agente->telefono, $agente->email])->filter()->implode(' · ') }}</span></td></tr>
                @endforeach
            </table>
        </div>
    @endif
</body>
</html>
