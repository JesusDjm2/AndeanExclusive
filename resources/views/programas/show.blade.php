<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $programa->nombre }} | {{ config('seo.brand') }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($programa->presentacion), 160) }}">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('seo.brand') }}">
    <meta property="og:locale" content="{{ $programa->lang === 'es' ? 'es_PE' : 'en_US' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $programa->nombre }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($programa->presentacion), 200) }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ rtrim(config('seo.site_url'), '/') }}/img/cusco-de-noche.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $programa->nombre }}">
    <meta name="twitter:image" content="{{ rtrim(config('seo.site_url'), '/') }}/img/cusco-de-noche.jpg">
    <link rel="preconnect" href="https://stackpath.bootstrapcdn.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://andeanexclusivetours.com">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/paxs.css') }}">

</head>

<body>
    {{-- HERO SECTION --}}
    {{-- <div class="wrapper">
        @auth
            <a href="{{ route('programas.edit', $programa) }}" class="btn-floating-edit" title="Editar programa">
                <i class="fa fa-edit"></i>
            </a>
        @endauth
        <div class="fullscreen-section">
            <img src="https://andeanexclusivetours.com/img/Fondos/blog-peru.webp" alt="Andean Exclusive Tours"
                class="fullscreen-img" width="1920" height="1080" decoding="async" fetchpriority="high">
            <div class="content-overlay-paxs">
                <div class="program-info-card">
                    <div class="program-header">
                        @include('layouts.partials.programa-show-hero-logo', ['locale' => 'es'])
                        <h1 class="mb-2">
                            {{ $programa->nombre }}
                            <span class="title-dot"></span>
                        </h1>
                    </div>
                    @if ($programa->inicio && $programa->fin)
                        @php
                            $dias =
                                \Carbon\Carbon::parse($programa->inicio)->diffInDays(
                                    \Carbon\Carbon::parse($programa->fin),
                                ) + 1;

                            // Cálculo de precios si existen
                            if ($programa->precioAdulto > 0 || $programa->precioChild > 0) {
                                $totalAdultos = $programa->paxs->where('edad', '>=', 12)->count();
                                $totalNinos = $programa->paxs->whereBetween('edad', [5, 11])->count();
                                $totalGeneral =
                                    $totalAdultos * floatval($programa->precioAdulto) +
                                    $totalNinos * floatval($programa->precioChild);
                            }
                        @endphp

                        <div class="program-metrics-grid">
                            <div class="metric-item">
                                <div class="metric-icon">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                                <div class="metric-content">
                                    <span class="metric-label">Duración</span>
                                    <span class="metric-value">{{ $dias }}
                                        {{ $dias > 1 ? 'Días' : 'Día' }}</span>
                                </div>
                            </div>

                            <div class="metric-item">
                                <div class="metric-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="metric-content">
                                    <span class="metric-label">Viajeros</span>
                                    <span class="metric-value">{{ $programa->paxs->count() }}</span>
                                </div>
                            </div>

                            @if (isset($totalGeneral) && $totalGeneral > 0)
                                <div class="metric-item highlight">
                                    <div class="metric-icon">
                                        <i class="fa fa-tag"></i>
                                    </div>
                                    <div class="metric-content">
                                        <span class="metric-label">Precio total</span>
                                        <span class="metric-value">${{ number_format($totalGeneral, 0) }},00</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="program-dates-compact">
                            <div class="date-compact-item">
                                <i class="fa fa-calendar-check"></i>
                                <span class="date-compact-label">Desde</span>
                                <span
                                    class="date-compact-value">{{ \Carbon\Carbon::parse($programa->inicio)->format('d M Y') }}</span>
                            </div>
                            <div class="date-compact-separator">—</div>
                            <div class="date-compact-item">
                                <span class="date-compact-label">al </span>
                                <span
                                    class="date-compact-value">{{ \Carbon\Carbon::parse($programa->fin)->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (($programa->agentes && $programa->agentes->count() > 0) || $programa->paxs->count() > 0)
                        @include('layouts.partials.programa-show-hero-lists', [
                            'locale' => 'es',
                            'programa' => $programa,
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div> --}}
    {{-- HERO SECTION --}}
    <div class="wrapper">
        @auth
            <a href="{{ route('programas.edit', $programa) }}" class="btn-floating-edit" title="Editar programa">
                <i class="fa fa-edit"></i>
            </a>
        @endauth
        <div class="fullscreen-section">
            <img src="https://andeanexclusivetours.com/img/Fondos/blog-peru.webp" alt="Andean Exclusive Tours"
                class="fullscreen-img" width="1920" height="1080" decoding="async" fetchpriority="high">
            <div class="content-overlay-paxs">
                <div class="program-info-card">
                    {{-- Header con logo --}}
                    <div class="program-header">
                        @include('layouts.partials.programa-show-hero-logo', ['locale' => 'es'])
                        <h1 class="mb-2">
                            {{ $programa->nombre }}
                            <span class="title-dot"></span>
                        </h1>
                    </div>

                    @if ($programa->inicio && $programa->fin)
                        @php
                            $dias =
                                \Carbon\Carbon::parse($programa->inicio)->diffInDays(
                                    \Carbon\Carbon::parse($programa->fin),
                                ) + 1;

                            // Formato corto de fechas
                            $inicioFormateado = \Carbon\Carbon::parse($programa->inicio)->format('d M');
                            $finFormateado = \Carbon\Carbon::parse($programa->fin)->format('d M Y');

                            if ($programa->precioAdulto > 0 || $programa->precioChild > 0) {
                                $totalAdultos = $programa->paxs->where('edad', '>=', 12)->count();
                                $totalNinos = $programa->paxs->whereBetween('edad', [5, 11])->count();
                                $totalGeneral =
                                    $totalAdultos * floatval($programa->precioAdulto) +
                                    $totalNinos * floatval($programa->precioChild);
                            }
                        @endphp

                        {{-- Métricas principales en grid --}}
                        <div class="program-metrics-grid program-metrics-grid--compact">
                            {{-- Duración + Fechas (integrado) --}}
                            <div class="metric-item metric-item-dates">
                                <div class="metric-icon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <div class="metric-content">
                                    <span class="metric-label">Duración</span>
                                    <span class="metric-value">{{ $dias }}
                                        {{ $dias > 1 ? 'Días' : 'Día' }}</span>
                                    <div class="metric-subdates">
                                        <span>{{ $inicioFormateado }} — {{ $finFormateado }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Pasajeros --}}
                            <div class="metric-item">
                                <div class="metric-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="metric-content">
                                    <span class="metric-label">Viajeros</span>
                                    <span class="metric-value">{{ $programa->paxs->count() }}</span>
                                </div>
                            </div>

                            {{-- Precio total --}}
                            @if (isset($totalGeneral) && $totalGeneral > 0)
                                <div class="metric-item highlight">
                                    <div class="metric-icon">
                                        <i class="fa fa-tag"></i>
                                    </div>
                                    <div class="metric-content">
                                        <span class="metric-label">Precio total</span>
                                        <span class="metric-value">${{ number_format($totalGeneral, 0) }},00</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Eliminé el bloque program-dates-compact --}}
                    @endif

                    @if (($programa->agentes && $programa->agentes->count() > 0) || $programa->paxs->count() > 0)
                        @include('layouts.partials.programa-show-hero-lists', [
                            'locale' => 'es',
                            'programa' => $programa,
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- AGENTES FLOTANTES - Solo aparece si existen agentes --}}
    @if ($programa->agentes->isNotEmpty())
        <div class="floating-agents" id="floatingAgents">
            <div class="agents-floating-container">
                <div class="floating-indicator"></div>
                <div class="agents-title">
                    <i class="fa fa-headphones mr-1"></i> Sus agentes de viajes
                </div>
                @foreach ($programa->agentes as $agente)
                    <div class="floating-agent-item">
                        <div class="floating-agent-avatar">
                            @if ($agente->foto)
                                <img src="{{ asset($agente->foto) }}" alt="{{ $agente->nombre }}"
                                    style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                <i class="fa fa-user-circle-o"></i>
                            @endif
                        </div>
                        <div class="floating-agent-info">
                            <div class="floating-agent-name">{{ $agente->nombre }}</div>
                            @if ($agente->telefono)
                                <div class="floating-agent-phone">
                                    <i class="fa fa-phone"></i> {{ $agente->telefono }}
                                </div>
                            @endif
                            @if ($agente->email)
                                <div class="floating-agent-email">
                                    <i class="fa fa-envelope-o"></i> {{ $agente->email }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="container">
        <div class="mt-5">
            <div class="program-tabs-scroll">
                <ul class="nav nav-pills mb-0 program-tabs-nav" id="programTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview"
                            role="tab">
                            <i class="fa fa-info-circle mr-2"></i>
                            <span class="d-none d-sm-inline">Descripción del programa</span>
                            <span class="d-inline d-sm-none">Descripción general</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="services-tab" data-toggle="tab" href="#services" role="tab">
                            <i class="fa fa-cutlery mr-2"></i>
                            <span class="d-none d-sm-inline">Servicios y proveedores</span>
                            <span class="d-inline d-sm-none">Servicios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="hotels-tab" data-toggle="tab" href="#hotels" role="tab">
                            <i class="fa fa-bed mr-2"></i>
                            <span class="d-none d-sm-inline">Alojamiento</span>
                            <span class="d-inline d-sm-none">Hoteles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="passengers-tab" data-toggle="tab" href="#passengers"
                            role="tab">
                            <i class="fa fa-users mr-2"></i>
                            <span class="d-none d-sm-inline">Pasajeros</span>
                            <span class="d-inline d-sm-none">Pax</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Tab Content --}}
            <div class="tab-content">
                {{-- Overview Tab --}}
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="card border-0 shadow-sm overview-card">
                        <div class="card-body p-4 p-md-5">
                            @if ($programa->presentacion)
                                <div class="overview-content">
                                    {!! $programa->presentacion !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-file-text-o fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">La descripción general del programa estará disponible
                                        próximamente.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Services Tab --}}
                <div class="tab-pane fade" id="services" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @if ($programa->proveedores->isNotEmpty())
                                @foreach ($programa->proveedores->groupBy(fn($p) => $p->categoria->nombre ?? 'Other Services') as $categoria => $proveedores)
                                    <div class="mb-4">
                                        <h5 class="mb-3 pb-2 border-bottom"
                                            style="font-size: 1rem; font-weight: 600; color: #2d3748;">
                                            <i class="fa fa-tag mr-2 text-primary"
                                                style="font-size: 0.9rem;"></i>{{ $categoria }}
                                            <span class="badge badge-light ml-2">{{ $proveedores->count() }}</span>
                                        </h5>
                                        <div class="row">
                                            @foreach ($proveedores as $proveedor)
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex">
                                                        <i class="fa fa-check-circle text-success mt-1 mr-2"
                                                            style="font-size: 0.8rem;"></i>
                                                        <div>
                                                            <strong>{{ $proveedor->nombre }}</strong>
                                                            @if ($proveedor->detalles)
                                                                <small
                                                                    class="text-muted d-block">{!! $proveedor->detalles !!}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-concierge-bell fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay proveedores de servicios listados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Hotels Tab --}}
                <div class="tab-pane fade" id="hotels" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @if ($programa->habitaciones->isNotEmpty())
                                <div class="row">
                                    @foreach ($programa->habitaciones->groupBy('hotel.id') as $hotelId => $habitaciones)
                                        @php
                                            $hotel = $habitaciones->first()->hotel;
                                            // Obtener las fechas para las habitaciones de este hotel
                                            $fechasPorHabitacion = [];
                                            foreach ($habitaciones as $habitacion) {
                                                $fecha = $programa
                                                    ->habitacionesFechas()
                                                    ->where('habitacion_id', $habitacion->id)
                                                    ->first();
                                                if ($fecha) {
                                                    $fechasPorHabitacion[$habitacion->id] = $fecha;
                                                }
                                            }
                                        @endphp
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100 border shadow-sm">
                                                <div class="card-body">
                                                    <!-- Cabecera del Hotel -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                                                        <h5 class="mb-0 text-uppercase"
                                                            style="font-size: 1.1rem; font-weight: 600;">
                                                            <i class="fa fa-building-o mr-2 text-primary"></i>
                                                            {{ $hotel->nombre ?? 'Hotel' }}
                                                        </h5>
                                                    </div>

                                                    <!-- Ubicación del Hotel -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-2">
                                                        @if ($hotel->direccion ?? false)
                                                            <p class="small mb-0">
                                                                <i class="fa fa-map-marker mr-1 text-danger"></i>
                                                                {{ $hotel->direccion }}
                                                            </p>
                                                        @endif

                                                        @if ($hotel->telefono ?? false)
                                                            <span class="small">
                                                                <i class="fa fa-phone mr-1 text-primary"></i>
                                                                {{ $hotel->telefono }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        @foreach ($habitaciones as $habitacion)
                                                            @php
                                                                $fecha = $fechasPorHabitacion[$habitacion->id] ?? null;
                                                            @endphp
                                                            <div class="mb-1 p-1 bg-light rounded">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                                                            <strong
                                                                                class="text-dark">{{ $habitacion->tipo }}</strong>

                                                                            @if ($fecha)
                                                                                @php
                                                                                    $noches = \Carbon\Carbon::parse(
                                                                                        $fecha->fecha_ingreso,
                                                                                    )->diffInDays(
                                                                                        \Carbon\Carbon::parse(
                                                                                            $fecha->fecha_salida,
                                                                                        ),
                                                                                    );
                                                                                @endphp
                                                                                <span class="badge badge-info small">
                                                                                    <i class="fa fa-moon-o"></i>
                                                                                    {{ $noches }}
                                                                                    {{ $noches == 1 ? 'noche' : 'noches' }}
                                                                                </span>
                                                                            @else
                                                                                <span
                                                                                    class="badge badge-secondary small">
                                                                                    <i class="fa fa-clock-o"></i>
                                                                                    Sin fechas
                                                                                </span>
                                                                            @endif
                                                                        </div>

                                                                        @if ($fecha)
                                                                            <div class="gap-2 small">
                                                                                <div class="mr-3 mb-2">
                                                                                    <i
                                                                                        class="fa fa-calendar-check-o text-dark"></i>
                                                                                    <span>Ingreso:</span>
                                                                                    <strong>{{ \Carbon\Carbon::parse($fecha->fecha_ingreso)->format('d/m/Y') }}</strong>
                                                                                </div>
                                                                                <div>
                                                                                    <i
                                                                                        class="fa fa-calendar-times-o text-dark"></i>
                                                                                    <span>Salida:</span>
                                                                                    <strong>{{ \Carbon\Carbon::parse($fecha->fecha_salida)->format('d/m/Y') }}</strong>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="text-muted small">
                                                                                <i
                                                                                    class="fa fa-exclamation-triangle"></i>
                                                                                Fechas no asignadas
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-hotel fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Sin información de hospedaje</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Passengers Tab --}}
                <div class="tab-pane fade" id="passengers" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @if ($programa->paxs->isNotEmpty())
                                <div class="row">
                                    @foreach ($programa->paxs as $pax)
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="card h-100 border">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3"
                                                            style="width: 45px; height: 45px;">
                                                            <i class="fa fa-user text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 font-weight-bold">{{ $pax->nombre }}</h6>
                                                            <small
                                                                class="text-muted">{{ $pax->nacionalidad ?? 'Nationality not specified' }}</small>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                        <div>
                                                            <small class="text-muted d-block">Edad</small>
                                                            <span>{{ $pax->edad ?? '—' }} años</span>
                                                        </div>
                                                        @if ($pax->alimentacion)
                                                            <div class="text-right">
                                                                <small class="text-muted d-block">Dieta</small>
                                                                <span class="small">{{ $pax->alimentacion }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Sin datos de pasajeros</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.programa-show-footer', ['locale' => 'es'])

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Control de visibilidad de los agentes flotantes
        document.addEventListener('DOMContentLoaded', function() {
            const floatingAgents = document.getElementById('floatingAgents');
            if (!floatingAgents) return;

            // Calcular 80vh en píxeles
            const triggerHeight = window.innerHeight * 0.8;

            function checkScroll() {
                const scrollPosition = window.scrollY;

                // Mostrar agentes flotantes SOLO cuando el scroll ha pasado el 80vh
                if (scrollPosition > triggerHeight) {
                    floatingAgents.classList.add('visible');
                } else {
                    floatingAgents.classList.remove('visible');
                    floatingAgents.classList.remove('expanded'); // También colapsar al ocultar
                }
            }

            // Función para toggle expandir/colapsar en móviles
            function setupMobileBehavior() {
                const isMobile = window.innerWidth <= 768;

                if (isMobile) {
                    // Click en el contenedor para expandir/colapsar
                    const container = floatingAgents.querySelector('.agents-floating-container');
                    if (container && !floatingAgents.hasAttribute('data-listener')) {
                        container.addEventListener('click', function(e) {
                            // Evitar que el click en el botón de cerrar propague
                            if (e.target.classList.contains('close-agents')) {
                                floatingAgents.classList.remove('expanded');
                                e.stopPropagation();
                                return;
                            }
                            floatingAgents.classList.toggle('expanded');
                        });
                        floatingAgents.setAttribute('data-listener', 'true');
                    }

                    // Si no está expandido, mostrar badge con cantidad de agentes
                    const agentCount = floatingAgents.querySelectorAll('.floating-agent-item').length;
                    const firstAgent = floatingAgents.querySelector('.floating-agent-item');
                    if (firstAgent && !floatingAgents.querySelector('.agent-count-badge')) {
                        const badge = document.createElement('span');
                        badge.className = 'agent-count-badge';
                        badge.textContent = `+${agentCount - 1}`;
                        firstAgent.querySelector('.floating-agent-info').appendChild(badge);
                    }

                    // Agregar botón de cerrar para versión expandida
                    if (!floatingAgents.querySelector('.close-agents')) {
                        const closeBtn = document.createElement('button');
                        closeBtn.className = 'close-agents';
                        closeBtn.innerHTML = '×';
                        closeBtn.setAttribute('aria-label', 'Close');
                        floatingAgents.querySelector('.agents-floating-container').appendChild(closeBtn);
                    }
                }
            }

            // Escuchar evento scroll
            window.addEventListener('scroll', checkScroll);

            // Escuchar resize para recalcular el trigger y ajustar comportamiento móvil
            window.addEventListener('resize', function() {
                const newTriggerHeight = window.innerHeight * 0.8;
                if (window.scrollY > newTriggerHeight) {
                    floatingAgents.classList.add('visible');
                } else {
                    floatingAgents.classList.remove('visible');
                }
                setupMobileBehavior();
            });

            // Ejecutar al cargar
            checkScroll();
            setupMobileBehavior();
        });
    </script>
</body>

</html>
