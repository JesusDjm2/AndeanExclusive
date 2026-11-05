@extends('layouts.app')
@section('titulo', 'Agentes y Counters Comerciales')

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado principal --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-user-tie" aria-hidden="true"></i>
                    Agentes y Counters Comerciales
                </h1>
                <small class="ae-admin-page-desc">Directorio de agencias de viajes aliadas, tour leaders y representantes comerciales B2B que operan con Andean Exclusive.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('programas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-suitcase-rolling me-1"></i> Ver Programas
                </a>
                @can('agentes.gestionar')
                    <a href="{{ route('agentes.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="fas fa-plus-circle me-1" aria-hidden="true"></i> Nuevo Agente
                    </a>
                @endcan
            </div>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Métricas superiores (KPIs) --}}
        <div class="ae-stats-row mb-4">
            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--gold">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $totalAgentes }}</div>
                    <div class="ae-stat-card__lbl">Total Agentes Registrados</div>
                </div>
            </div>

            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--dark">
                    <i class="fas fa-suitcase-rolling"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $conProgramas }}</div>
                    <div class="ae-stat-card__lbl">Con Programas Activos</div>
                </div>
            </div>

            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--plomo">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $agentes->total() }}</div>
                    <div class="ae-stat-card__lbl">Contactos Listados</div>
                </div>
            </div>
        </div>

        {{-- Barra de búsqueda y filtros --}}
        <div class="card border-0 shadow-sm mb-3 ae-admin-filter-card">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('agentes.index') }}" class="row g-2 align-items-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="search" name="q" class="form-control border-start-0"
                                placeholder="Buscar por nombre, correo o teléfono/WhatsApp..."
                                value="{{ $q ?? '' }}" maxlength="100">
                            @if (!empty($q))
                                <a href="{{ route('agentes.index') }}" class="btn btn-outline-secondary" title="Limpiar búsqueda">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary px-3">Buscar</button>
                        </div>
                    </div>
                    @if (!empty($q))
                        <div class="col-auto">
                            <span class="small text-muted">
                                Resultados para <strong>"{{ $q }}"</strong> ({{ $agentes->total() }})
                            </span>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabla de Agentes --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            @if ($agentes->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-user-slash fa-3x mb-3 text-secondary opacity-50" aria-hidden="true"></i>
                    <h5 class="fw-semibold">No se encontraron agentes</h5>
                    <p class="small text-muted mb-3">No hay agentes comerciales registrados que coincidan con la búsqueda.</p>
                    @can('agentes.gestionar')
                        <a href="{{ route('agentes.create') }}" class="btn btn-primary btn-sm">Registrar el primer agente</a>
                    @endcan
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table">
                        <thead>
                            <tr>
                                <th>Agente / Counter</th>
                                <th>Teléfono / WhatsApp</th>
                                <th>Correo Comercial</th>
                                <th class="text-center">Programas</th>
                                <th>Registro</th>
                                <th class="text-end" style="min-width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agentes as $agente)
                                @php
                                    $initials = collect(explode(' ', trim($agente->nombre)))
                                        ->filter()
                                        ->take(2)
                                        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                        ->implode('');
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $agente->telefono);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($agente->foto && file_exists(public_path($agente->foto)))
                                                <img src="{{ asset($agente->foto) }}" alt="{{ $agente->nombre }}"
                                                    class="rounded-circle shadow-sm" width="46" height="46"
                                                    style="object-fit: cover; border: 2px solid var(--ae-gold-border);">
                                            @else
                                                <span class="ae-avatar-circle ae-avatar-circle--gold" style="width: 46px; height: 46px; font-size: 0.95rem;">
                                                    {{ $initials ?: 'A' }}
                                                </span>
                                            @endif
                                            <div>
                                                <a href="{{ route('agentes.show', $agente) }}" class="fw-bold text-decoration-none text-reset hover-primary d-block">
                                                    {{ $agente->nombre }}
                                                </a>
                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                    <i class="fas fa-handshake me-1 text-gold"></i> Representante B2B
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="tel:{{ $agente->telefono }}" class="text-decoration-none text-reset small text-nowrap">
                                                <i class="fas fa-phone-alt text-muted me-1"></i> {{ $agente->telefono }}
                                            </a>
                                            @if ($cleanPhone)
                                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener"
                                                    class="btn btn-xs btn-outline-success px-1 py-0" title="Chatear por WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="mailto:{{ $agente->email }}" class="text-decoration-none text-reset small text-nowrap">
                                                <i class="fas fa-envelope text-muted me-1"></i> {{ $agente->email }}
                                            </a>
                                            <button type="button" class="btn btn-xs btn-link text-muted p-0" data-copy="{{ $agente->email }}" title="Copiar correo">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('agentes.show', $agente) }}" class="ae-badge-count text-decoration-none" title="Ver programas de este agente">
                                            <i class="fas fa-suitcase-rolling me-1"></i>
                                            {{ $agente->programas_count ?? 0 }} {{ Str::plural('viaje', $agente->programas_count ?? 0) }}
                                        </a>
                                    </td>
                                    <td class="text-nowrap small text-muted">
                                        {{ $agente->created_at?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href="{{ route('agentes.show', $agente) }}" class="btn btn-sm btn-outline-secondary"
                                                title="Ver ficha comercial" aria-label="Ver ficha">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('agentes.gestionar')
                                                <a href="{{ route('agentes.edit', $agente) }}" class="btn btn-sm btn-outline-primary"
                                                    title="Editar agente" aria-label="Editar agente">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form action="{{ route('agentes.destroy', $agente) }}" method="POST" class="d-inline"
                                                    data-confirm="¿Deseas eliminar al agente comercial {{ $agente->nombre }}?"
                                                    data-confirm-title="¿Eliminar agente?"
                                                    data-confirm-button="Sí, eliminar"
                                                    data-confirm-danger>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar agente" aria-label="Eliminar agente">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-center">
            {{ $agentes->links() }}
        </div>
    </div>
@endsection
