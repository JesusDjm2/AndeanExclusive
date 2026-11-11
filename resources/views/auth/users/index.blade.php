@extends('layouts.app')
@section('titulo', 'Usuarios y Permisos')

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado principal --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-user-shield" aria-hidden="true"></i>
                    Usuarios y Permisos
                </h1>
                <small class="ae-admin-page-desc">Cuentas internas de acceso al panel, autenticación y asignación de roles por área operativa.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="fas fa-user-plus me-1" aria-hidden="true"></i> Nuevo Usuario
                </a>
            </div>
        </div>

        {{-- Alertas del sistema --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Métricas superiores (KPIs) --}}
        <div class="ae-stats-row mb-4">
            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--gold">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $totalCount }}</div>
                    <div class="ae-stat-card__lbl">Total Usuarios</div>
                </div>
            </div>

            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--dark">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $rolesCount['administrador'] ?? 0 }}</div>
                    <div class="ae-stat-card__lbl">Administración</div>
                </div>
            </div>

            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--plomo">
                    <i class="fas fa-route"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $rolesCount['operaciones'] ?? 0 }}</div>
                    <div class="ae-stat-card__lbl">Operaciones / Ventas</div>
                </div>
            </div>

            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--plomo">
                    <i class="fas fa-calculator"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ ($rolesCount['reservas'] ?? 0) + ($rolesCount['contabilidad'] ?? 0) }}</div>
                    <div class="ae-stat-card__lbl">Reservas & Finanzas</div>
                </div>
            </div>

            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--gold">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ $rolesCount['branding'] ?? 0 }}</div>
                    <div class="ae-stat-card__lbl">Branding & Web</div>
                </div>
            </div>
        </div>

        {{-- Filtros y buscador --}}
        <div class="card border-0 shadow-sm mb-3 ae-admin-filter-card">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="search" name="q" class="form-control border-start-0"
                                placeholder="Buscar por nombre, correo electrónico o rol..."
                                value="{{ $q ?? '' }}" maxlength="100">
                            @if (!empty($q))
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" title="Limpiar búsqueda">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary px-3">Buscar</button>
                        </div>
                    </div>
                    @if (!empty($q))
                        <div class="col-auto">
                            <span class="small text-muted">
                                Resultados para <strong>"{{ $q }}"</strong> ({{ $users->count() }})
                            </span>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabla de usuarios --}}
        <div class="card border-0 shadow-sm overflow-hidden">
            @if ($users->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-user-slash fa-3x mb-3 text-secondary opacity-50" aria-hidden="true"></i>
                    <h5 class="fw-semibold">No se encontraron usuarios</h5>
                    <p class="small text-muted mb-3">No hay cuentas que coincidan con el término de búsqueda ingresado.</p>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Ver todos los usuarios</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Usuario / Colaborador</th>
                                <th>Correo Electrónico</th>
                                <th>Área / Rol de Acceso</th>
                                <th>Seguridad</th>
                                <th>Alta en Sistema</th>
                                <th class="text-end" style="min-width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @php
                                    $isMe = auth()->id() === $user->id;
                                    $initials = collect(explode(' ', trim($user->name)))
                                        ->filter()
                                        ->take(2)
                                        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                        ->implode('');
                                    $roleName = $user->roles->first()?->name ?? 'sin-rol';
                                @endphp
                                <tr>
                                    <td class="text-muted small fw-bold">#{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ae-avatar-circle {{ $isMe ? 'ae-avatar-circle--gold' : 'ae-avatar-circle--plomo' }}" title="{{ $user->name }}">
                                                {{ $initials ?: 'U' }}
                                            </span>
                                            <div>
                                                <div class="fw-semibold d-flex align-items-center gap-1">
                                                    {{ $user->name }}
                                                    @if ($isMe)
                                                        <span class="badge bg-gold-subtle text-gold-text border ms-1" style="font-size: 0.68rem;">
                                                            <i class="fas fa-user-check me-1"></i>Tú
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-muted small d-block" style="font-size: 0.75rem;">
                                                    {{ \App\Support\Permisos::AREAS[$roleName] ?? ucfirst($roleName) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none text-reset small">
                                                <i class="fas fa-envelope text-muted me-1"></i> {{ $user->email }}
                                            </a>
                                            <button type="button" class="btn btn-xs btn-link text-muted p-0" data-copy="{{ $user->email }}" title="Copiar correo">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($roleName === 'administrador')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                <i class="fas fa-crown me-1"></i> Administración
                                            </span>
                                        @elseif ($roleName === 'operaciones')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                <i class="fas fa-route me-1"></i> Operaciones y Ventas
                                            </span>
                                        @elseif ($roleName === 'reservas')
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                <i class="fas fa-concierge-bell me-1"></i> Reservas
                                            </span>
                                        @elseif ($roleName === 'contabilidad')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="fas fa-calculator me-1"></i> Contabilidad
                                            </span>
                                        @elseif ($roleName === 'branding')
                                            <span class="badge border" style="background-color: rgba(139, 92, 246, 0.12); color: #7c3aed; border-color: rgba(139, 92, 246, 0.25) !important;">
                                                <i class="fas fa-paint-brush me-1"></i> Branding & Web
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border">
                                                {{ ucfirst($roleName) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-muted">
                                            <i class="fas fa-shield-alt text-success me-1"></i> Autenticado
                                        </span>
                                    </td>
                                    <td class="text-nowrap small text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> {{ $user->created_at?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary"
                                                title="Editar usuario y roles" aria-label="Editar usuario">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            @if (!$isMe)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                                    data-confirm="¿Estás seguro de eliminar el acceso del usuario {{ $user->name }} al panel?"
                                                    data-confirm-title="¿Eliminar usuario?"
                                                    data-confirm-button="Sí, eliminar"
                                                    data-confirm-danger>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar usuario" aria-label="Eliminar usuario">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary opacity-50" disabled title="No puedes eliminar tu propia cuenta activa">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @endif
                                        </div>
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
