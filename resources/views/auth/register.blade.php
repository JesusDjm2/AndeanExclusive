@extends('layouts.app')
@section('titulo', $formHeading ?? 'Nuevo Usuario')

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                    {{ $formHeading ?? 'Nuevo Usuario' }}
                </h1>
                <small class="ae-admin-page-desc">Registra una nueva cuenta interna para otorgar acceso al panel administrativo de Andean Exclusive.</small>
            </div>
            <div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Usuarios
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="card-header p-4 border-bottom bg-transparent d-flex align-items-center gap-3">
                        <span class="ae-avatar-circle ae-avatar-circle--gold" style="width: 3.5rem; height: 3.5rem; font-size: 1.25rem;">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        <div>
                            <h4 class="mb-0 fw-bold">Crear Cuenta Interna</h4>
                            <span class="text-muted small">Asigna credenciales y el área de trabajo del colaborador.</span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ $formAction ?? route('users.store') }}" novalidate>
                            @csrf

                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-id-card text-muted"></i> Datos Personales
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold small">Nombre Completo <span class="text-danger">*</span></label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                        placeholder="Ej. Carlos Mendoza">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold small">Correo Electrónico Corporativo <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="ejemplo@andeanexclusive.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @php
                                $selectedRole = old('role', 'operaciones');
                            @endphp

                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-user-tag text-muted"></i> Área de Trabajo / Rol <span class="text-danger">*</span>
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-lg-4">
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $selectedRole === 'administrador' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="administrador" class="visually-hidden" @checked($selectedRole === 'administrador')>
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
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $selectedRole === 'operaciones' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="operaciones" class="visually-hidden" @checked($selectedRole === 'operaciones')>
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
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $selectedRole === 'reservas' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="reservas" class="visually-hidden" @checked($selectedRole === 'reservas')>
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
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $selectedRole === 'contabilidad' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="contabilidad" class="visually-hidden" @checked($selectedRole === 'contabilidad')>
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
                                    <label class="card h-100 p-3 border cursor-pointer position-relative role-card {{ $selectedRole === 'branding' ? 'border-primary bg-primary-subtle' : '' }}">
                                        <input type="radio" name="role" value="branding" class="visually-hidden" @checked($selectedRole === 'branding')>
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

                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-lock text-muted"></i> Contraseña de Acceso <span class="text-danger">*</span>
                            </h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold small">Contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="#password" title="Mostrar/ocultar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password-confirm" class="form-label fw-semibold small">Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña">
                                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="#password-confirm" title="Mostrar/ocultar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-user-plus me-1"></i> {{ $submitLabel ?? 'Crear Usuario' }}
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
