{{--
    Componente para mostrar el código de un programa con vista previa interactiva al pasar el mouse (hover)
    o tocar en móvil (tap), manteniendo un ajuste perfecto a la pantalla en celulares.
    @param \App\Models\Programa|null $programa
    @param string|null $codigo
    @param string $tab  Pestaña a abrir (itinerario, historial, etc.)
--}}
@props([
    'programa' => null,
    'codigo' => null,
    'tab' => 'itinerario',
])

@php
    $cod = $programa?->codigo ?? $codigo;
@endphp

@if (! $programa || ! $cod)
    <span class="ae-prog-code text-muted font-monospace">{{ $cod ?: '—' }}</span>
@else
    @php
        $clienteNombre = $programa->cliente?->nombre ?: 'Sin cliente asignado';
        $inicio = $programa->inicio ? \Carbon\Carbon::parse($programa->inicio) : null;
        $fin = $programa->fin ? \Carbon\Carbon::parse($programa->fin) : null;
        $dias = ($inicio && $fin) ? $inicio->diffInDays($fin) + 1 : null;
        $paxsCount = $programa->relationLoaded('paxs') ? $programa->paxs->count() : ($programa->paxs_count ?? 0);
        
        $fase = $programa->faseViaje();
        $faseInfo = match ($fase) {
            'proximo' => [
                'label' => 'Próximo',
                'badge' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                'icon' => 'fa-clock',
            ],
            'en_curso' => [
                'label' => 'En viaje',
                'badge' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
                'icon' => 'fa-plane-departure',
            ],
            'finalizado' => [
                'label' => 'Finalizado',
                'badge' => 'bg-secondary-subtle text-secondary border',
                'icon' => 'fa-check',
            ],
            default => [
                'label' => 'Sin fechas',
                'badge' => 'bg-light text-muted border',
                'icon' => 'fa-calendar-times',
            ],
        };

        $estadoLabel = \App\Models\Programa::ESTADOS[$programa->estado] ?? ucfirst($programa->estado ?? '');
    @endphp

    <span class="ae-prog-code-wrapper d-inline-flex align-items-center gap-1">
        <a href="{{ route('programas.edit', [$programa->id, 'tab' => $tab]) }}"
           class="ae-prog-code-link font-monospace fw-semibold"
           data-prog-popover
           title="{{ $programa->nombre }}">
            {{ $cod }}
        </a>

        <button type="button"
                class="btn ae-prog-preview-btn p-0 border-0 text-muted"
                data-prog-popover
                aria-label="Detalles del programa {{ $cod }}">
            <i class="fas fa-info-circle"></i>
        </button>

        {{-- Plantilla oculta para el popover (renderizada por Bootstrap vía JS con container body) --}}
        <template class="ae-prog-popover-tpl">
            <div class="ae-prog-card-preview">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-2 pb-2 ae-prog-card-header border-bottom">
                    <span class="font-monospace fw-bold fs-6 ae-prog-code-highlight">{{ $cod }}</span>
                    <span class="badge {{ $faseInfo['badge'] }} rounded-pill px-2 py-1 small">
                        <i class="fas {{ $faseInfo['icon'] }} me-1"></i>{{ $faseInfo['label'] }}
                    </span>
                </div>

                <h6 class="fw-bold mb-2 lh-sm ae-prog-card-name">{{ $programa->nombre }}</h6>

                <div class="ae-prog-preview-meta small text-muted d-flex flex-column gap-1 mb-3">
                    <div class="d-flex align-items-baseline gap-2">
                        <i class="fas fa-user-circle fa-fw"></i>
                        <span class="text-truncate">
                            <strong>Cliente:</strong> {{ $clienteNombre }}
                        </span>
                    </div>

                    @if ($inicio && $fin)
                        <div class="d-flex align-items-baseline gap-2">
                            <i class="fas fa-calendar-alt fa-fw"></i>
                            <span>
                                <strong>Fechas:</strong>
                                {{ $inicio->format('d/m/Y') }} al {{ $fin->format('d/m/Y') }}
                                <span class="badge border ms-1 ae-prog-days-pill">{{ $dias }} {{ $dias === 1 ? 'día' : 'días' }}</span>
                            </span>
                        </div>
                    @else
                        <div class="d-flex align-items-baseline gap-2">
                            <i class="fas fa-calendar-times fa-fw"></i>
                            <span class="fst-italic text-muted">Fechas pendientes de definir</span>
                        </div>
                    @endif

                    <div class="d-flex align-items-baseline gap-2">
                        <i class="fas fa-users fa-fw"></i>
                        <span>
                            <strong>Pasajeros:</strong>
                            {{ $paxsCount }} {{ $paxsCount === 1 ? 'pasajero' : 'pasajeros' }}
                        </span>
                    </div>

                    @if ($estadoLabel)
                        <div class="d-flex align-items-baseline gap-2">
                            <i class="fas fa-bookmark fa-fw"></i>
                            <span>
                                <strong>Estado:</strong> {{ $estadoLabel }}
                            </span>
                        </div>
                    @endif
                </div>

                <a href="{{ route('programas.edit', [$programa->id, 'tab' => $tab]) }}"
                   class="btn btn-sm btn-primary w-100 py-1 rounded-2 text-decoration-none text-white">
                    Abrir programa <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </template>
    </span>
@endif
