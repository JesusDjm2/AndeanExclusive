{{-- Panel lateral del botón "?": ayuda de la pantalla actual. Comportamiento: resources/js/ayuda.js · Estilos: resources/sass/admin/_ayuda.scss --}}
@php
    $user = auth()->user();
    $temasPantalla = \App\Support\Ayuda::dePantalla(request(), $user);
    $contexto = \App\Support\Ayuda::contextoPantalla(request(), $user);
@endphp
<aside class="offcanvas offcanvas-end ayuda-panel" tabindex="-1" id="ayudaPanel" aria-labelledby="ayudaPanelTitulo" data-ayuda-panel>
    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-2">
            <span class="ayuda-header-icon"><i class="fas {{ $contexto['icono'] ?? 'fa-question-circle' }}"></i></span>
            <div>
                <h2 class="offcanvas-title mb-0" id="ayudaPanelTitulo">{{ $contexto['titulo'] ?? 'Ayuda de esta pantalla' }}</h2>
                @if (!empty($contexto['badge']))
                    <span class="badge ayuda-badge">{{ $contexto['badge'] }}</span>
                @endif
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar ayuda"></button>
    </div>

    <div class="offcanvas-body">
        @if ($contexto && !empty($contexto['es_compleja']))
            {{-- Encabezado orientador de vista de alta gestión --}}
            <div class="ayuda-hero-banner">
                <p class="ayuda-hero-banner__texto mb-0">{{ $contexto['resumen'] }}</p>
            </div>

            {{-- Bloque dinámico para fichas con pestañas (programas.edit / create) --}}
            @if (!empty($contexto['pestañas_guia']))
                <div class="ayuda-pestana-card" data-ayuda-pestana-card>
                    <div class="ayuda-pestana-card__header">
                        <span class="ayuda-pestana-card__tag"><i class="fas fa-layer-group me-1"></i> Pestaña activa: <strong data-ayuda-pestana-nombre>Datos</strong></span>
                    </div>
                    <p class="ayuda-pestana-card__desc mb-0" data-ayuda-pestana-desc>
                        {{ $contexto['pestañas_guia']['datos']['desc'] ?? 'Cargando información de la pestaña...' }}
                    </p>
                    {{-- Mini diccionario de pestañas --}}
                    <div class="ayuda-pestanas-grid mt-2" data-ayuda-pestanas-meta>
                        @foreach ($contexto['pestañas_guia'] as $tabKey => $tabData)
                            <div class="d-none" data-tab-info="{{ $tabKey }}" data-tab-name="{{ $tabData['nombre'] }}" data-tab-desc="{{ $tabData['desc'] }}"></div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Acciones clave de la pantalla --}}
            @if (!empty($contexto['acciones_clave']))
                <div class="ayuda-seccion">
                    <h3 class="ayuda-seccion__titulo"><i class="fas fa-mouse-pointer me-1"></i> Acciones clave en esta pantalla</h3>
                    <div class="ayuda-acciones-lista">
                        @foreach ($contexto['acciones_clave'] as $accion)
                            <div class="ayuda-accion-item">
                                <div class="ayuda-accion-item__icon"><i class="fas {{ $accion['icono'] }}"></i></div>
                                <div class="ayuda-accion-item__body">
                                    <strong>{{ $accion['nombre'] }}</strong>
                                    <p class="mb-0 text-muted small">{{ $accion['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Flujo recomendado --}}
            @if (!empty($contexto['flujo_recomendado']))
                <div class="ayuda-seccion">
                    <h3 class="ayuda-seccion__titulo"><i class="fas fa-stream me-1"></i> Flujo de trabajo recomendado</h3>
                    <ol class="ayuda-flujo-lista mb-0">
                        @foreach ($contexto['flujo_recomendado'] as $paso)
                            <li>{{ $paso }}</li>
                        @endforeach
                    </ol>
                </div>
            @endif

            {{-- Tips Pro / Consejos Senior --}}
            @if (!empty($contexto['tips_pro']))
                <div class="ayuda-pro-tip">
                    <div class="ayuda-pro-tip__header">
                        <i class="fas fa-lightbulb text-warning me-1"></i> <strong>Consejos de Operaciones Senior</strong>
                    </div>
                    <ul class="ayuda-pro-tip__lista mb-0">
                        @foreach ($contexto['tips_pro'] as $tip)
                            <li>{{ $tip }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="ayuda-seccion mt-2">
                <h3 class="ayuda-seccion__titulo"><i class="fas fa-list-check me-1"></i> Guías técnicas y paso a paso</h3>
                <div class="ayuda-temas-contenedor">
                    @forelse ($temasPantalla as $clave => $tema)
                        @include('ayuda.partials.tema', ['clave' => $clave, 'tema' => $tema, 'abierto' => false])
                    @empty
                        <p class="ayuda-panel__vacio">En el centro de ayuda puedes buscar lo que necesitas.</p>
                    @endforelse
                </div>
            </div>
        @else
            {{-- Vista simple (usuarios, agentes, etc.) --}}
            <div class="ayuda-temas-contenedor">
                @forelse ($temasPantalla as $clave => $tema)
                    @include('ayuda.partials.tema', ['clave' => $clave, 'tema' => $tema, 'abierto' => $loop->first])
                @empty
                    <p class="ayuda-panel__vacio">En el centro de ayuda puedes buscar lo que necesitas.</p>
                @endforelse
            </div>
        @endif
    </div>

    <div class="ayuda-panel__pie">
        <a href="{{ route('ayuda.index') }}" class="btn btn-sm btn-primary"><i class="fas fa-book-open" aria-hidden="true"></i> Ver toda la ayuda</a>
        @if (\App\Support\Ayuda::guiaDisponible())
            <a href="{{ route('ayuda.guia') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf" aria-hidden="true"></i> Guía en PDF</a>
        @endif
    </div>
</aside>
