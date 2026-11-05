@extends('layouts.app')
@section('titulo', 'Ficha de Agente · ' . $agente->nombre)

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-id-card-alt" aria-hidden="true"></i>
                    Ficha Comercial del Agente
                </h1>
                <small class="ae-admin-page-desc">Información de contacto, canales directos y registro de programas operados con Andean Exclusive.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('agentes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Agentes
                </a>
                @can('agentes.gestionar')
                    <a href="{{ route('agentes.edit', $agente) }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="fas fa-pen me-1"></i> Editar Agente
                    </a>
                @endcan
            </div>
        </div>

        @php
            $initials = collect(explode(' ', trim($agente->nombre)))
                ->filter()
                ->take(2)
                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                ->implode('');
            $cleanPhone = preg_replace('/[^0-9]/', '', $agente->telefono);
        @endphp

        {{-- Tarjeta de Perfil Principal --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-4">
                    <div class="col-auto">
                        @if ($agente->foto && file_exists(public_path($agente->foto)))
                            <img src="{{ asset($agente->foto) }}" alt="{{ $agente->nombre }}"
                                class="rounded-circle shadow" width="100" height="100"
                                style="object-fit: cover; border: 3px solid var(--ae-gold-border);">
                        @else
                            <span class="ae-avatar-circle ae-avatar-circle--gold shadow" style="width: 100px; height: 100px; font-size: 2.2rem;">
                                {{ $initials ?: 'A' }}
                            </span>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h2 class="h3 fw-bold mb-0 text-break">{{ $agente->nombre }}</h2>
                            <span class="ae-badge-gold">
                                <i class="fas fa-handshake"></i> Agente Aliado B2B
                            </span>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-calendar-check me-1"></i> Registrado en el sistema el {{ $agente->created_at?->format('d/m/Y') ?? '—' }}
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            @if ($cleanPhone)
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener" class="btn btn-sm btn-success shadow-sm">
                                    <i class="fab fa-whatsapp me-1"></i> Chatear por WhatsApp
                                </a>
                                <a href="tel:{{ $agente->telefono }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-phone-alt me-1"></i> {{ $agente->telefono }}
                                </a>
                            @endif
                            <a href="mailto:{{ $agente->email }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-envelope me-1"></i> {{ $agente->email }}
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ $agente->email }}" title="Copiar correo">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 border-start-lg ps-lg-4 text-center text-lg-start">
                        <div class="p-3 rounded border bg-light-subtle">
                            <span class="d-block text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Programas Operados</span>
                            <span class="display-6 fw-bold text-primary">{{ $agente->programas_count ?? $agente->programas->count() }}</span>
                            <span class="d-block small text-muted mt-1">viajes gestionados en la agencia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Programas vinculados a este agente --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header p-3 border-bottom bg-transparent d-flex align-items-center justify-content-between">
                <span class="fw-bold d-flex align-items-center gap-2">
                    <i class="fas fa-suitcase-rolling text-primary"></i>
                    Historial de Programas Operados con este Agente
                </span>
                <span class="badge bg-secondary-subtle text-secondary border">
                    {{ $agente->programas->count() }} {{ Str::plural('programa', $agente->programas->count()) }}
                </span>
            </div>

            @if ($agente->programas->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-calendar-times fa-3x mb-3 text-secondary opacity-50" aria-hidden="true"></i>
                    <h5 class="fw-semibold">No hay programas vinculados a este agente aún</h5>
                    <p class="small text-muted mb-3">Puedes vincular a {{ $agente->nombre }} al crear o editar un programa de viaje en la pestaña Datos Generales o Pasajeros.</p>
                    <a href="{{ route('programas.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Crear Nuevo Programa
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table">
                        <thead>
                            <tr>
                                <th>Código / Título del Programa</th>
                                <th>Titular / Pasajeros</th>
                                <th>Fechas del Viaje</th>
                                <th>Moneda</th>
                                <th>Estado</th>
                                <th class="text-end">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agente->programas as $programa)
                                <tr>
                                    <td>
                                        <a href="{{ route('programas.edit', $programa) }}" class="fw-bold text-decoration-none text-reset hover-primary d-block">
                                            {{ $programa->titulo ?: 'Programa sin título #' . $programa->id }}
                                        </a>
                                        @if ($programa->codigo)
                                            <span class="badge bg-light text-muted border small">{{ $programa->codigo }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-semibold">{{ $programa->pasajero_titular ?: ($programa->cliente?->nombre ?: '—') }}</div>
                                        @if ($programa->paxs_count || $programa->paxs()->count())
                                            <span class="text-muted small">
                                                <i class="fas fa-users me-1"></i> {{ $programa->paxs()->count() }} {{ Str::plural('pax', $programa->paxs()->count()) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap small">
                                        @if ($programa->fecha_inicio && $programa->fecha_fin)
                                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                                            {{ $programa->fecha_inicio->format('d/m/Y') }} – {{ $programa->fecha_fin->format('d/m/Y') }}
                                        @elseif ($programa->fecha_inicio)
                                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                                            Desde {{ $programa->fecha_inicio->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border">
                                            {{ $programa->moneda ?? 'USD' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $estado = $programa->estado ?? 'borrador';
                                        @endphp
                                        @if ($estado === 'confirmado' || $estado === 'operacion')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="fas fa-check-circle me-1"></i> Confirmado
                                            </span>
                                        @elseif ($estado === 'cerrado')
                                            <span class="badge bg-secondary-subtle text-secondary border">
                                                <i class="fas fa-lock me-1"></i> Cerrado
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                <i class="fas fa-clock me-1"></i> {{ ucfirst($estado) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('programas.edit', $programa) }}" class="btn btn-sm btn-outline-primary" title="Ver programa">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
