{{--
    Itinerario privado del cliente (noindex), una sola plantilla para ES y EN según $programa->lang.
    Página única con navegación fija por secciones: Resumen · Día a día · Alojamiento · Viajeros.
    Estilos: resources/sass/programa/_itinerario.scss · JS: resources/js/programa.js (navegación por secciones).
--}}
@php
    $isEn = $programa->lang === 'en';
    $locale = $isEn ? 'en' : 'es';
    $t = $isEn
        ? [
            'private' => 'Private itinerary', 'edit' => 'Edit program', 'skip' => 'Skip to content',
            'nav' => ['overview' => 'Overview', 'days' => 'Day by day', 'stay' => 'Accommodation', 'travelers' => 'Travelers'],
            'day' => 'Day', 'days' => 'days', 'night' => 'night', 'nights' => 'nights',
            'traveler' => 'traveler', 'travelers' => 'travelers', 'total' => 'Total price', 'markup' => 'incl. :pct% markup',
            'your_trip' => 'Your trip', 'dates' => 'Dates', 'duration' => 'Duration', 'party' => 'Travelers',
            'adults' => 'adults', 'adult' => 'adult', 'children' => 'children', 'child' => 'child', 'infants' => 'infants', 'infant' => 'infant',
            'team' => 'Your travel team', 'team_hint' => 'Write to us any time, before or during your trip.',
            'overview_title' => 'Trip overview', 'empty_overview' => 'Your program overview will be available soon.',
            'days_title' => 'Day by day', 'days_sub' => 'Times are local (Peru, GMT−5) and may be adjusted with your guide.',
            'empty_days' => 'The day-by-day itinerary will be available soon.', 'overnight' => 'Overnight',
            'expand_all' => 'Expand all', 'collapse_all' => 'Collapse all',
            'activity' => 'activity', 'activities' => 'activities',
            'stay_title' => 'Accommodation', 'stay_sub' => 'Hotels selected for your journey.', 'empty_stay' => 'Accommodation details will be available soon.',
            'check_in' => 'Check-in', 'check_out' => 'Check-out', 'no_dates' => 'Dates to be confirmed',
            'travelers_title' => 'Travelers', 'empty_travelers' => 'No traveler details yet.',
            'age' => 'years', 'passport' => 'Passport', 'kid' => 'Child',
            'cta_title' => 'Travelers’ details', 'cta_text' => 'Help us book trains, entrance tickets and hotels: complete passports, diets and emergency contacts.',
            'cta_done' => 'You can review or update your travelers’ details at any time.', 'cta_button' => 'Complete details', 'cta_review' => 'Review details',
        ]
        : [
            'private' => 'Itinerario privado', 'edit' => 'Editar programa', 'skip' => 'Saltar al contenido',
            'nav' => ['overview' => 'Resumen', 'days' => 'Día a día', 'stay' => 'Alojamiento', 'travelers' => 'Viajeros'],
            'day' => 'Día', 'days' => 'días', 'night' => 'noche', 'nights' => 'noches',
            'traveler' => 'viajero', 'travelers' => 'viajeros', 'total' => 'Precio total', 'markup' => 'incl. :pct% ganancia',
            'your_trip' => 'Su viaje', 'dates' => 'Fechas', 'duration' => 'Duración', 'party' => 'Viajeros',
            'adults' => 'adultos', 'adult' => 'adulto', 'children' => 'niños', 'child' => 'niño', 'infants' => 'infantes', 'infant' => 'infante',
            'team' => 'Su equipo de viaje', 'team_hint' => 'Escríbanos cuando lo necesite, antes o durante el viaje.',
            'overview_title' => 'Descripción del viaje', 'empty_overview' => 'La descripción del programa estará disponible próximamente.',
            'days_title' => 'Día a día', 'days_sub' => 'Horarios en hora local (Perú, GMT−5); pueden ajustarse con su guía.',
            'empty_days' => 'El itinerario día a día estará disponible próximamente.', 'overnight' => 'Alojamiento',
            'expand_all' => 'Expandir todos', 'collapse_all' => 'Colapsar todos',
            'activity' => 'actividad', 'activities' => 'actividades',
            'stay_title' => 'Alojamiento', 'stay_sub' => 'Hoteles seleccionados para su viaje.', 'empty_stay' => 'La información de hospedaje estará disponible próximamente.',
            'check_in' => 'Ingreso', 'check_out' => 'Salida', 'no_dates' => 'Fechas por confirmar',
            'travelers_title' => 'Viajeros', 'empty_travelers' => 'Aún no hay datos de viajeros.',
            'age' => 'años', 'passport' => 'Pasaporte', 'kid' => 'Niño',
            'cta_title' => 'Datos de los viajeros', 'cta_text' => 'Ayúdenos a reservar trenes, entradas y hoteles: complete pasaportes, alimentación y contactos de emergencia.',
            'cta_done' => 'Puede revisar o actualizar los datos de los viajeros cuando quiera.', 'cta_button' => 'Completar datos', 'cta_review' => 'Revisar datos',
        ];
    $plural = fn (int $n, string $uno, string $varios) => $n . ' ' . ($n === 1 ? $t[$uno] : $t[$varios]);

    $inicio = $programa->inicio ? \Carbon\Carbon::parse($programa->inicio) : null;
    $fin = $programa->fin ? \Carbon\Carbon::parse($programa->fin) : null;
    $duracion = $programa->duracionDias();
    $rango = $inicio && $fin
        ? ($inicio->isSameMonth($fin)
            ? $inicio->locale($locale)->isoFormat('D') . ' – ' . $fin->locale($locale)->isoFormat($isEn ? 'D MMMM YYYY' : 'D [de] MMMM [de] YYYY')
            : $inicio->locale($locale)->isoFormat($isEn ? 'D MMM' : 'D [de] MMM') . ' – ' . $fin->locale($locale)->isoFormat($isEn ? 'D MMM YYYY' : 'D [de] MMM [de] YYYY'))
        : null;

    $presupuesto = $programa->presupuesto();
    $grupo = collect([
        $presupuesto['adultos'] ? $plural($presupuesto['adultos'], 'adult', 'adults') : null,
        $presupuesto['ninos'] ? $plural($presupuesto['ninos'], 'child', 'children') : null,
        $presupuesto['infantes'] ? $plural($presupuesto['infantes'], 'infant', 'infants') : null,
    ])->filter()->implode(' · ');
    $itinerario = $programa->itinerario(soloCliente: true);
    $diasConContenido = $itinerario['dias']->filter(fn ($d) => $d['dia'] || $d['servicios']->isNotEmpty() || $d['hotel']);
    $hoteles = $programa->alojamiento();
    $abierto = $programa->estado !== 'cancelado' && $programa->faseViaje() !== 'finalizado';
    $faltanDatos = $programa->paxs->isEmpty() || $programa->paxs->contains(fn ($p) => blank($p->pasaporte) || ! $p->fecha_nacimiento);
    $paises = \App\Support\Paises::enIdioma($locale);
    $telefono = fn (?string $n) => preg_replace('/[^0-9+]/', '', (string) $n);

    $siteUrl = rtrim(config('seo.site_url'), '/');
    $description = \Illuminate\Support\Str::limit(strip_tags((string) $programa->presentacion), 160);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $programa->nombre }} | {{ config('seo.brand') }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0b1d1f">
    <meta property="og:site_name" content="{{ config('seo.brand') }}">
    <meta property="og:title" content="{{ $programa->nombre }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $siteUrl }}/img/cusco-de-noche.jpg">
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    @vite(['resources/sass/programa.scss', 'resources/js/programa.js'])
</head>

<body class="itin-page">
    <a href="#contenido" class="itin-skip">{{ $t['skip'] }}</a>

    {{-- Portada --}}
    <header class="itin-hero">
        <img src="{{ asset(\App\Support\Portadas::resolver($programa->portada)) }}" alt="" class="itin-hero__bg" width="1920" height="1080" decoding="async" fetchpriority="high">
        <div class="container itin-hero__inner">
            <div class="itin-hero__top">
                <a href="{{ $isEn ? route('index') : route('inicio') }}" target="_blank" rel="noopener">
                    <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="Andean Exclusive Tours" class="itin-hero__logo" width="170" height="62">
                </a>
                @auth
                    <a href="{{ route('programas.edit', $programa) }}" class="itin-hero__edit"><i class="fa fa-pencil" aria-hidden="true"></i> {{ $t['edit'] }}</a>
                @endauth
            </div>

            <div class="itin-hero__content">
                <p class="itin-hero__eyebrow"><i class="fa fa-lock" aria-hidden="true"></i> {{ $t['private'] }} · {{ $programa->codigo }}</p>
                <h1 class="itin-hero__title">{{ $programa->nombre }}</h1>
                @if ($rango)
                    <p class="itin-hero__dates">{{ ucfirst($rango) }}</p>
                @endif

                <ul class="itin-facts">
                    @if ($duracion)
                        <li class="itin-facts__item">
                            <i class="fa fa-calendar-o" aria-hidden="true"></i>
                            <span><strong>{{ $plural($duracion, 'day', 'days') }}</strong>@if ($duracion > 1)<small>{{ $plural($duracion - 1, 'night', 'nights') }}</small>@endif</span>
                        </li>
                    @endif
                    <li class="itin-facts__item">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span><strong>{{ $plural($programa->paxs->count(), 'traveler', 'travelers') }}</strong>@if ($grupo)<small>{{ $grupo }}</small>@endif</span>
                    </li>
                    @if ($hoteles->isNotEmpty())
                        <li class="itin-facts__item">
                            <i class="fa fa-bed" aria-hidden="true"></i>
                            <span><strong>{{ $hoteles->count() }} {{ $hoteles->count() === 1 ? 'hotel' : ($isEn ? 'hotels' : 'hoteles') }}</strong></span>
                        </li>
                    @endif
                    @if ($presupuesto['total'] > 0)
                        <li class="itin-facts__item itin-facts__item--price">
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            <span><small>{{ $t['total'] }}</small><strong>US$ {{ number_format($presupuesto['total'], 2) }}</strong></span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </header>

    {{-- Navegación por secciones (fija al desplazarse) --}}
    <nav class="itin-nav" aria-label="{{ $isEn ? 'Sections' : 'Secciones' }}" data-itin-nav>
        <div class="container">
            <ul class="itin-nav__list">
                <li><a href="#resumen" class="itin-nav__link" data-itin-link><i class="fa fa-compass" aria-hidden="true"></i> {{ $t['nav']['overview'] }}</a></li>
                <li><a href="#dia-a-dia" class="itin-nav__link" data-itin-link><i class="fa fa-map-signs" aria-hidden="true"></i> {{ $t['nav']['days'] }}</a></li>
                <li><a href="#alojamiento" class="itin-nav__link" data-itin-link><i class="fa fa-bed" aria-hidden="true"></i> {{ $t['nav']['stay'] }}</a></li>
                <li><a href="#viajeros" class="itin-nav__link" data-itin-link><i class="fa fa-users" aria-hidden="true"></i> {{ $t['nav']['travelers'] }}</a></li>
            </ul>
        </div>
    </nav>

    <main id="contenido" class="container itin-main" tabindex="-1">
        @if ($abierto)
            <aside class="itin-cta {{ $faltanDatos ? 'itin-cta--pending' : '' }}">
                <span class="itin-cta__icon" aria-hidden="true"><i class="fa {{ $faltanDatos ? 'fa-id-card-o' : 'fa-check' }}"></i></span>
                <div class="itin-cta__body">
                    <p class="itin-cta__title">{{ $t['cta_title'] }}</p>
                    <p class="itin-cta__text">{{ $faltanDatos ? $t['cta_text'] : $t['cta_done'] }}</p>
                </div>
                <a href="{{ route('itinerario.pasajeros', $programa) }}" class="itin-btn {{ $faltanDatos ? '' : 'itin-btn--ghost' }}">
                    {{ $faltanDatos ? $t['cta_button'] : $t['cta_review'] }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </a>
            </aside>
        @endif

        @if ($programa->aceptable())
            @php
                $vigenteCliente = $programa->aceptacionVigente();
                $textosAcepta = $isEn
                    ? ['title' => 'Your proposal', 'text' => 'If everything looks right, confirm it here and our team will contact you with the next steps.', 'name' => 'Your full name', 'terms' => 'I have read and accept the', 'terms_link' => 'terms and conditions', 'button' => 'Accept proposal', 'done' => 'Accepted by :name on :date.', 'url' => route('terms')]
                    : ['title' => 'Su propuesta', 'text' => 'Si todo está conforme, acéptela aquí y nuestro equipo le escribirá con los siguientes pasos.', 'name' => 'Su nombre completo', 'terms' => 'He leído y acepto los', 'terms_link' => 'términos y condiciones', 'button' => 'Aceptar propuesta', 'done' => 'Aceptada por :name el :date.', 'url' => route('terminos')];
            @endphp
            <aside id="propuesta" class="itin-accept">
                <p class="itin-cta__title">{{ $textosAcepta['title'] }}</p>
                @if (session('aceptada'))
                    <p class="itin-accept__ok" role="status"><i class="fa fa-check" aria-hidden="true"></i> {{ session('aceptada') }}</p>
                @endif
                @if ($vigenteCliente)
                    <p class="itin-accept__ok"><i class="fa fa-check" aria-hidden="true"></i> {{ str_replace([':name', ':date'], [$vigenteCliente->nombre, $vigenteCliente->created_at->locale($locale)->isoFormat('LL')], $textosAcepta['done']) }}</p>
                @else
                    <p class="itin-cta__text">{{ $textosAcepta['text'] }}</p>
                    <form action="{{ route('itinerario.aceptar', $programa) }}" method="POST" class="itin-accept__form">
                        @csrf
                        <label for="acepta-nombre">{{ $textosAcepta['name'] }}</label>
                        <input id="acepta-nombre" type="text" name="nombre" value="{{ old('nombre') }}" maxlength="255" required autocomplete="name">
                        @error('nombre')<p class="itin-accept__error">{{ $message }}</p>@enderror
                        <label class="itin-accept__terms">
                            <input type="checkbox" name="terminos" value="1" required>
                            <span>{{ $textosAcepta['terms'] }} <a href="{{ $textosAcepta['url'] }}" target="_blank" rel="noopener">{{ $textosAcepta['terms_link'] }}</a></span>
                        </label>
                        @error('terminos')<p class="itin-accept__error">{{ $message }}</p>@enderror
                        <button type="submit" class="itin-btn">{{ $textosAcepta['button'] }} <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                    </form>
                @endif
            </aside>
        @endif

        {{-- Resumen --}}
        <section id="resumen" class="itin-section" aria-labelledby="resumen-title" data-itin-section>
            <div class="itin-overview">
                <div class="itin-card itin-overview__text">
                    <h2 id="resumen-title" class="itin-section__title">{{ $t['overview_title'] }}</h2>
                    @if ($programa->presentacion)
                        <div class="itin-prose">{!! $programa->presentacion !!}</div>
                    @else
                        <p class="itin-empty"><i class="fa fa-file-text-o" aria-hidden="true"></i> {{ $t['empty_overview'] }}</p>
                    @endif
                </div>

                <div class="itin-overview__side">
                    <div class="itin-card">
                        <h3 class="itin-card__title">{{ $t['your_trip'] }}</h3>
                        <dl class="itin-summary">
                            @if ($rango)
                                <div><dt>{{ $t['dates'] }}</dt><dd>{{ ucfirst($rango) }}</dd></div>
                            @endif
                            @if ($duracion)
                                <div><dt>{{ $t['duration'] }}</dt><dd>{{ $plural($duracion, 'day', 'days') }}@if ($duracion > 1) · {{ $plural($duracion - 1, 'night', 'nights') }}@endif</dd></div>
                            @endif
                            <div><dt>{{ $t['party'] }}</dt><dd>{{ $grupo ?: '—' }}</dd></div>
                            @if ($presupuesto['total'] > 0)
                                <div class="itin-summary__total">
                                    <dt>{{ $t['total'] }}</dt>
                                    <dd>US$ {{ number_format($presupuesto['total'], 2) }}
                                        @can('cotizacion.ver')
                                            @if ($presupuesto['pctGanancia'] > 0)
                                                <small>{{ str_replace(':pct', number_format($presupuesto['pctGanancia'], 2), $t['markup']) }}</small>
                                            @endif
                                        @endcan
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    @if ($programa->agentes->isNotEmpty())
                        <div class="itin-card">
                            <h3 class="itin-card__title">{{ $t['team'] }}</h3>
                            <p class="itin-card__hint">{{ $t['team_hint'] }}</p>
                            <ul class="itin-team">
                                @foreach ($programa->agentes as $agente)
                                    <li class="itin-team__member">
                                        @if ($agente->foto)
                                            <img src="{{ asset($agente->foto) }}" alt="" class="itin-avatar" width="44" height="44" loading="lazy">
                                        @else
                                            <span class="itin-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($agente->nombre, 0, 1)) }}</span>
                                        @endif
                                        <div class="itin-team__info">
                                            <strong>{{ $agente->nombre }}</strong>
                                            @if ($agente->telefono)
                                                <a href="https://wa.me/{{ ltrim($telefono($agente->telefono), '+') }}" target="_blank" rel="noopener"><i class="fa fa-whatsapp" aria-hidden="true"></i> {{ $agente->telefono }}</a>
                                            @endif
                                            @if ($agente->email)
                                                <a href="mailto:{{ $agente->email }}"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $agente->email }}</a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Día a día --}}
        <section id="dia-a-dia" class="itin-section" aria-labelledby="dias-title" data-itin-section>
            <header class="itin-section__head itin-section__head--actions">
                <div class="itin-section__head-titles">
                    <h2 id="dias-title" class="itin-section__title">{{ $t['days_title'] }}</h2>
                    <p class="itin-section__sub">{{ $t['days_sub'] }}</p>
                </div>
                @if ($diasConContenido->isNotEmpty())
                    <div class="itin-accordion-controls" role="group" aria-label="{{ $isEn ? 'Day by day controls' : 'Controles de día a día' }}">
                        <button type="button" class="itin-accordion-btn" data-itin-expand-all aria-label="{{ $t['expand_all'] }}">
                            <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                            <span>{{ $t['expand_all'] }}</span>
                        </button>
                        <span class="itin-accordion-controls__divider" aria-hidden="true">·</span>
                        <button type="button" class="itin-accordion-btn" data-itin-collapse-all aria-label="{{ $t['collapse_all'] }}">
                            <i class="fa fa-angle-double-up" aria-hidden="true"></i>
                            <span>{{ $t['collapse_all'] }}</span>
                        </button>
                    </div>
                @endif
            </header>

            @if ($diasConContenido->isEmpty())
                <p class="itin-card itin-empty"><i class="fa fa-map-o" aria-hidden="true"></i> {{ $t['empty_days'] }}</p>
            @else
                <ol class="itin-timeline">
                    @foreach ($itinerario['dias'] as $dia)
                        @php
                            $numServicios = $dia['servicios']->count();
                            $hasServices = $numServicios > 0;
                            $hasHotel = !empty($dia['hotel']);
                            $isOpen = $loop->first;
                        @endphp
                        <li class="itin-day {{ $isOpen ? 'is-open' : '' }}" data-itin-day id="dia-{{ $dia['numero'] }}">
                            <button type="button" 
                                    class="itin-day__marker itin-day__marker--interactive" 
                                    data-itin-marker-toggle
                                    aria-hidden="true" 
                                    tabindex="-1"
                                    title="{{ $t['day'] }} {{ $dia['numero'] }}">
                                <span class="itin-day__label">{{ $t['day'] }}</span>
                                <span class="itin-day__num">{{ $dia['numero'] }}</span>
                            </button>
                            <article class="itin-card itin-day__card">
                                <header class="itin-day__header">
                                    <button type="button" 
                                            class="itin-day__toggle" 
                                            data-itin-day-toggle
                                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}" 
                                            aria-controls="dia-collapse-{{ $dia['numero'] }}">
                                        <div class="itin-day__toggle-left">
                                            <div class="itin-day__meta-top">
                                                <span class="itin-day__mobile-badge" aria-hidden="true">
                                                    {{ $t['day'] }} {{ $dia['numero'] }}
                                                </span>
                                                <span class="itin-day__date">{{ ucfirst($dia['fecha']->locale($locale)->isoFormat($isEn ? 'dddd, MMMM D' : 'dddd D [de] MMMM')) }}</span>
                                            </div>
                                            <h3 class="itin-day__title">{{ $dia['dia']?->titulo ?: $t['day'] . ' ' . $dia['numero'] }}</h3>
                                            
                                            {{-- Resumen compacto en pastillas visible en mobile y tablet --}}
                                            @if ($hasServices || $hasHotel)
                                                <div class="itin-day__summary-pills">
                                                    @if ($hasServices)
                                                        <span class="itin-day__pill">
                                                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                            {{ $numServicios }} {{ $plural($numServicios, 'activity', 'activities') }}
                                                        </span>
                                                    @endif
                                                    @if ($hasHotel)
                                                        <span class="itin-day__pill itin-day__pill--hotel">
                                                            <i class="fa fa-bed" aria-hidden="true"></i>
                                                            <span class="itin-day__pill-hotel-name">{{ $dia['hotel'] }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <div class="itin-day__toggle-right" aria-hidden="true">
                                            <span class="itin-day__chevron-circle">
                                                <i class="fa fa-chevron-down itin-day__chevron"></i>
                                            </span>
                                        </div>
                                    </button>
                                </header>

                                <div id="dia-collapse-{{ $dia['numero'] }}" 
                                     class="itin-day__collapse" 
                                     role="region" 
                                     aria-label="{{ $t['day'] }} {{ $dia['numero'] }}"
                                     {{ $isOpen ? '' : 'hidden' }}>
                                    <div class="itin-day__content">
                                        @if ($dia['dia']?->descripcion)
                                            <div class="itin-day__text">{!! nl2br(e($dia['dia']->descripcion)) !!}</div>
                                        @endif
                                        @if ($dia['servicios']->isNotEmpty())
                                            <ul class="itin-services">
                                                @foreach ($dia['servicios'] as $servicio)
                                                    <li class="itin-service">
                                                        <span class="itin-service__time">{{ $servicio->hora_corta ?? '' }}</span>
                                                        <span class="itin-service__icon" aria-hidden="true"><i class="fa {{ \App\Models\Servicio::TIPOS[$servicio->tipo][3] ?? 'fa-check' }}"></i></span>
                                                        <span class="itin-service__body">
                                                            <span class="itin-service__name">{{ $servicio->titulo ?: $servicio->tipo_etiqueta }}</span>
                                                            @if ($servicio->descripcion)
                                                                <span class="itin-service__desc">{{ $servicio->descripcion }}</span>
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if ($dia['hotel'])
                                            <p class="itin-day__hotel"><i class="fa fa-moon-o" aria-hidden="true"></i> {{ $t['overnight'] }}: <strong>{{ $dia['hotel'] }}</strong></p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        {{-- Alojamiento --}}
        <section id="alojamiento" class="itin-section" aria-labelledby="hoteles-title" data-itin-section>
            <header class="itin-section__head">
                <h2 id="hoteles-title" class="itin-section__title">{{ $t['stay_title'] }}</h2>
                <p class="itin-section__sub">{{ $t['stay_sub'] }}</p>
            </header>

            @if ($hoteles->isEmpty())
                <p class="itin-card itin-empty"><i class="fa fa-bed" aria-hidden="true"></i> {{ $t['empty_stay'] }}</p>
            @else
                <div class="itin-hotels">
                    @foreach ($hoteles as ['hotel' => $hotel, 'estadias' => $estadias])
                        <article class="itin-card itin-hotel">
                            <div class="itin-hotel__media">
                                @if ($hotel->img)
                                    <img src="{{ asset($hotel->img) }}" alt="{{ $hotel->nombre }}" loading="lazy" decoding="async">
                                @else
                                    <i class="fa fa-building-o" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="itin-hotel__body">
                                <h3 class="itin-hotel__name">{{ $hotel->nombre }}</h3>
                                @if ($hotel->direccion)
                                    <p class="itin-hotel__meta"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $hotel->direccion }}</p>
                                @endif
                                @if ($hotel->telefono)
                                    <p class="itin-hotel__meta"><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:{{ $telefono($hotel->telefono) }}">{{ $hotel->telefono }}</a></p>
                                @endif
                                <ul class="itin-rooms">
                                    @foreach ($estadias as $estadia)
                                        @php
                                            $fecha = $estadia->fecha_ingreso ? $estadia : null;
                                            $ingreso = $fecha ? \Carbon\Carbon::parse($fecha->fecha_ingreso) : null;
                                            $salida = $fecha ? \Carbon\Carbon::parse($fecha->fecha_salida) : null;
                                        @endphp
                                        <li class="itin-room">
                                            <span class="itin-room__type">
                                                <i class="fa fa-bed" aria-hidden="true"></i> {{ $estadia->etiqueta }}
                                                @if ($estadia->reservado_cliente)
                                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle ms-2 py-1 px-2 small" style="font-size: 0.72rem;">
                                                        <i class="fa fa-user me-1" aria-hidden="true"></i> {{ $isEn ? 'Reserved by client' : 'Por cuenta del pasajero' }}
                                                    </span>
                                                @endif
                                            </span>
                                            @if ($fecha)
                                                <span class="itin-room__dates">{{ $t['check_in'] }} {{ $ingreso->locale($locale)->isoFormat('D MMM') }} · {{ $t['check_out'] }} {{ $salida->locale($locale)->isoFormat('D MMM') }}</span>
                                                <span class="itin-room__nights">{{ $plural((int) $ingreso->diffInDays($salida), 'night', 'nights') }}</span>
                                            @else
                                                <span class="itin-room__dates">{{ $t['no_dates'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Viajeros --}}
        <section id="viajeros" class="itin-section" aria-labelledby="viajeros-title" data-itin-section>
            <header class="itin-section__head">
                <h2 id="viajeros-title" class="itin-section__title">{{ $t['travelers_title'] }}</h2>
                @if ($grupo)<p class="itin-section__sub">{{ $grupo }}</p>@endif
            </header>

            @if ($programa->paxs->isEmpty())
                <p class="itin-card itin-empty"><i class="fa fa-users" aria-hidden="true"></i> {{ $t['empty_travelers'] }}</p>
            @else
                <ul class="itin-travelers">
                    @foreach ($programa->paxs as $pax)
                        @php
                            $iniciales = collect(explode(' ', trim($pax->nombre)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                            $esNino = $pax->edad !== null && $pax->edad < \App\Models\Programa::EDAD_ADULTO;
                        @endphp
                        <li class="itin-card itin-traveler">
                            <span class="itin-avatar itin-avatar--{{ $loop->index % 4 }}" aria-hidden="true">{{ $iniciales }}</span>
                            <div class="itin-traveler__info">
                                <p class="itin-traveler__name">{{ $pax->nombre }}@if ($esNino) <span class="itin-tag">{{ $t['kid'] }}</span>@endif</p>
                                <p class="itin-traveler__meta">
                                    @if ($pax->edad !== null)<span>{{ $pax->edad }} {{ $t['age'] }}</span>@endif
                                    @if ($pax->pais)<span>{{ $paises[$pax->pais] ?? $pax->nacionalidad }}</span>@endif
                                </p>
                                @if ($pax->alimentacionEn($locale))
                                    <p class="itin-traveler__diet"><i class="fa fa-cutlery" aria-hidden="true"></i> {{ $pax->alimentacionEn($locale) }}</p>
                                @endif
                                @auth
                                    @if ($pax->pasaporte)
                                        <p class="itin-traveler__meta"><span>{{ $t['passport'] }} {{ $pax->pasaporte_oculto }}</span></p>
                                    @endif
                                @endauth
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </main>

    @include('layouts.partials.programa-show-footer', ['locale' => $locale])
</body>

</html>
