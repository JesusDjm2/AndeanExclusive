@extends('layouts.app')
@section('titulo', 'Editar usuario · ' . $user->name)

@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-user-edit text-primary me-2"></i>
                    Actualizar Usuario
                </h2>
                <small class="ae-admin-page-desc">Modifica los accesos o datos del usuario <strong>{{ $user->name }}</strong></small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a usuarios
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3 bg-white border-bottom">
                        <span class="fw-bold text-secondary">
                            <i class="fas fa-id-card me-1 text-primary"></i> Información de la cuenta
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Nombre <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                        required value="{{ old('name', $user->name) }}" placeholder="Nombre completo">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Correo electrónico <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        required value="{{ old('email', $user->email) }}" placeholder="correo@andeanexclusive.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="role" class="col-md-4 col-form-label text-md-end">Rol en el panel <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    @php
                                        $currentRole = old('role', $user->roles->first()?->name ?? 'contabilidad');
                                    @endphp
                                    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="administrador" {{ $currentRole === 'administrador' ? 'selected' : '' }}>Administrador (incluye usuarios)</option>
                                        <option value="operaciones" {{ $currentRole === 'operaciones' ? 'selected' : '' }}>Operaciones y ventas</option>
                                        <option value="contabilidad" {{ $currentRole === 'contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                                        <option value="reservas" {{ $currentRole === 'reservas' ? 'selected' : '' }}>Reservas</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mb-3">
                                <div class="col-md-7 offset-md-4">
                                    <h6 class="fw-bold mb-1 text-secondary">
                                        <i class="fas fa-key me-1 text-primary"></i> Cambiar contraseña
                                    </h6>
                                    <small class="text-muted d-block">
                                        Deja estos campos en blanco si deseas mantener la contraseña actual del usuario.
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Nueva contraseña</label>
                                <div class="col-md-7">
                                    <div class="input-group has-validation">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres (opcional)">
                                        <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                                            data-toggle-password="#password" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                                            aria-pressed="false">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmar contraseña</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" autocomplete="new-password" placeholder="Repite la nueva contraseña">
                                        <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                                            data-toggle-password="#password-confirm" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                                            aria-pressed="false">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-7 offset-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Actualizar usuario
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-light border px-3">
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
