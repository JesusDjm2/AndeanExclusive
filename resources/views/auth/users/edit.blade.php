@extends('layouts.app')
@section('titulo', 'Editar Usuario · ' . $user->name)

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-user-edit" aria-hidden="true"></i>
                    Editar Usuario
                </h1>
                <small class="ae-admin-page-desc">Actualiza los datos personales, área de operación o contraseña de acceso de <strong>{{ $user->name }}</strong>.</small>
            </div>
            <div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Usuarios
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    {{-- Cabecera con avatar --}}
                    @php
                        $initials = collect(explode(' ', trim($user->name)))
                            ->filter()
                            ->take(2)
                            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                            ->implode('');
                        $currentRole = old('role', $user->roles->first()?->name ?? 'contabilidad');
                    @endphp
                    <div class="card-header p-4 border-bottom bg-transparent d-flex align-items-center gap-3">
                        <span class="ae-avatar-circle ae-avatar-circle--gold" style="width: 3.5rem; height: 3.5rem; font-size: 1.25rem;">
                            {{ $initials ?: 'U' }}
                        </span>
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $user->name }}</h4>
                            <span class="text-muted small">
                                <i class="fas fa-envelope me-1"></i> {{ $user->email }}
                                <span class="mx-1">·</span>
                                <i class="fas fa-id-badge me-1"></i> ID #{{ $user->id }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-id-card text-muted"></i> Datos de la Cuenta
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold small">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                        required value="{{ old('name', $user->name) }}" placeholder="Ej. Carlos Mendoza">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold small">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        required value="{{ old('email', $user->email) }}" placeholder="correo@andeanexclusive.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-user-tag text-muted"></i> Rol y Permisos de Área
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-lg-4">
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $currentRole === 'administrador' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="administrador" class="visually-hidden" @checked($currentRole === 'administrador')>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fas fa-crown text-warning fs-5"></i>
                                            <strong class="d-block">Administración</strong>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.76rem; line-height: 1.35;">
                                            Acceso total, usuarios, respaldos, sitio web y configuración global.
                                        </small>
                                    </label>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $currentRole === 'operaciones' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="operaciones" class="visually-hidden" @checked($currentRole === 'operaciones')>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fas fa-route text-primary fs-5"></i>
                                            <strong class="d-block">Operaciones</strong>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.76rem; line-height: 1.35;">
                                            Programas, consultas de clientes, armado de itinerarios y pasajeros.
                                        </small>
                                    </label>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $currentRole === 'reservas' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="reservas" class="visually-hidden" @checked($currentRole === 'reservas')>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fas fa-concierge-bell text-info fs-5"></i>
                                            <strong class="d-block">Reservas</strong>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.76rem; line-height: 1.35;">
                                            Asignación de servicios, contacto con proveedores, hoteles y órdenes.
                                        </small>
                                    </label>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $currentRole === 'contabilidad' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="contabilidad" class="visually-hidden" @checked($currentRole === 'contabilidad')>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fas fa-calculator text-success fs-5"></i>
                                            <strong class="d-block">Contabilidad</strong>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.76rem; line-height: 1.35;">
                                            Cuentas por pagar, cobros, margen de ganancia real y reportes financieros.
                                        </small>
                                    </label>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $currentRole === 'branding' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="branding" class="visually-hidden" @checked($currentRole === 'branding')>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fas fa-paint-brush fs-5" style="color: #8b5cf6;"></i>
                                            <strong class="d-block">Branding & Web</strong>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.76rem; line-height: 1.35;">
                                            Gestión exclusiva de contenido público: Tours (EN/ES), Blog (EN/ES), Categorías, Tags e Imágenes. Sin acceso a programas.
                                        </small>
                                    </label>
                                </div>
                            </div>
                            @error('role')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <hr class="my-4">

                            <h5 class="fw-bold mb-2 d-flex align-items-center gap-2">
                                <i class="fas fa-key text-muted"></i> Seguridad y Contraseña
                            </h5>
                            <p class="text-muted small mb-3">Deja estos campos en blanco si no deseas cambiar la contraseña actual del usuario.</p>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold small">Nueva Contraseña (opcional)</label>
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="#password" title="Mostrar/ocultar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password-confirm" class="form-label fw-semibold small">Confirmar Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" autocomplete="new-password" placeholder="Repite la contraseña">
                                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="#password-confirm" title="Mostrar/ocultar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.role-card input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    document.querySelectorAll('.role-card').forEach(card => {
                        card.classList.remove('border-primary', 'bg-primary-subtle');
                    });
                    if (radio.checked) {
                        radio.closest('.role-card').classList.add('border-primary', 'bg-primary-subtle');
                    }
                });
            });
        </script>
    @endpush
@endsection
