{{--
    Contenido del Modal de Historial de Movimientos de un Programa / Grupo.
    Permite consultar la bitácora completa en 1 clic sin abrir el formulario de edición.
--}}
@php
    $primerMov = $movimientos->last();
    $ultimoMov = $movimientos->first();
    $dias = $programa->diasParaInicio();
    $inicio = $programa->inicio ? \Carbon\Carbon::parse($programa->inicio) : null;
    $fin = $programa->fin ? \Carbon\Carbon::parse($programa->fin) : null;
@endphp

<div class="p-1">
    {{-- Tarjeta de contexto del grupo --}}
    <div class="card bg-light border-0 mb-4 p-3 rounded-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-dark font-monospace fs-7"><i class="fas fa-hashtag me-1 text-gold"></i>{{ $programa->codigo }}</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase">{{ $programa->estado }}</span>
                    @if ($programa->lang)
                        <span class="badge bg-secondary-subtle text-secondary">{{ strtoupper($programa->lang) }}</span>
                    @endif
                </div>
                <h4 class="h5 mb-1 fw-bold text-dark">{{ $programa->nombre }}</h4>
                <p class="text-muted small mb-0">
                    <i class="fas fa-calendar-alt me-1"></i>
                    @if ($inicio && $fin)
                        {{ $inicio->locale('es')->isoFormat('D MMM YYYY') }} – {{ $fin->locale('es')->isoFormat('D MMM YYYY') }}
                        ({{ $programa->duracionDias() }} {{ $programa->duracionDias() === 1 ? 'día' : 'días' }})
                    @else
                        Fechas por definir
                    @endif
                    · <i class="fas fa-users me-1 ms-2"></i> {{ $programa->paxs->count() }} pax
                    @if ($programa->cliente)
                        · <i class="fas fa-user-tag me-1 ms-2"></i> {{ $programa->cliente->nombre }}
                    @endif
                </p>
            </div>

            <div class="text-end d-flex flex-column gap-1">
                <span class="badge text-bg-light border text-muted px-3 py-2 text-start">
                    <i class="fas fa-stream me-1 text-primary"></i> <strong>{{ $movimientos->count() }}</strong> registros en bitácora
                </span>
                @if ($ultimoMov)
                    <small class="text-muted" style="font-size: 0.78rem;">
                        Último: {{ $ultimoMov->created_at->diffForHumans() }} por <strong>{{ $ultimoMov->usuario }}</strong>
                    </small>
                @endif
            </div>
        </div>
    </div>

    {{-- Línea de tiempo de la bitácora --}}
    <div class="historial-timeline-wrapper">
        @if ($movimientos->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-history fa-3x mb-3 text-secondary opacity-50"></i>
                <p class="mb-0">Todavía no hay movimientos registrados para este grupo.</p>
                <small class="text-muted">Cualquier cambio de estado, pasajero, fecha o servicio quedará registrado automáticamente aquí.</small>
            </div>
        @else
            @include('movimientos.partials.lista', ['movimientos' => $movimientos, 'conPrograma' => false])
        @endif
    </div>

    {{-- Barra de accesos directos al pie del modal --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 pt-3 border-top">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('programas.ficha', $programa) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fas fa-id-card me-1"></i> Abrir Ficha Operativa
            </a>
            @can('programas.gestionar')
                <a href="{{ route('programas.edit', [$programa, 'tab' => 'historial']) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-pencil-alt me-1"></i> Ir al Formulario de Edición
                </a>
            @endcan
        </div>
        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cerrar
        </button>
    </div>
</div>
