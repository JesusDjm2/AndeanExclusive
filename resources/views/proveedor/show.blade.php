@extends('layouts.app')

@section('body-class', 'view-proveedor-show')
@section('template_title')
    Proveedor: {{ $proveedor->nombre ?? 'Detalles del Proveedor' }}
@endsection

@section('contenido')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-truck me-2"></i>
                                    Proveedor: <strong>{{ $proveedor->nombre }}</strong>
                                </h3>
                                <p class="text-muted mb-0 mt-1">
                                    <small>ID: {{ $proveedor->id }} | Registrado:
                                        {{ $proveedor->created_at->format('d/m/Y') }}</small>
                                </p>
                            </div>
                            <div class="btn-group">
                                @can('proveedores.gestionar')
<a href="{{ route('proveedors.edit', $proveedor->id) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
@endcan
                                <a href="{{ route('proveedors.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light" width="30%">
                                                    <i class="fas fa-user-tag me-2"></i>Nombre
                                                </th>
                                                <td>
                                                    <span class="fw-bold">{{ $proveedor->nombre }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">
                                                    <i class="fas fa-tags me-2"></i>Categoría
                                                </th>
                                                <td>
                                                    <span>
                                                        {{ $proveedor->categoria->nombre ?? 'Sin categoría' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">
                                                    <i class="fas fa-id-card me-2"></i>RUC
                                                </th>
                                                <td>
                                                    <span>{{ $proveedor->ruc }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">
                                                    <i class="fas fa-map-marker-alt me-2"></i>Dirección
                                                </th>
                                                <td>
                                                    {{ $proveedor->direccion ?? 'No especificada' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">
                                                    <i class="fas fa-phone me-2"></i>Teléfono
                                                </th>
                                                <td>
                                                    <span class="text-decoration-none">
                                                        {{ $proveedor->telefono ?? 'No especificado' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">
                                                    <i class="fas fa-envelope me-2"></i>Correo
                                                </th>
                                                <td>
                                                    <a href="mailto:{{ $proveedor->correo }}" class="text-decoration-none">
                                                        {{ $proveedor->correo ?? 'No especificado' }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <th>
                                                <i class="fas fa-info-circle me-2"></i>Descripción:
                                            </th>
                                            <td>
                                                @if ($proveedor->detalles)
                                                    {!! nl2br(e($proveedor->detalles)) !!}
                                                @else
                                                    <i class="fas fa-sticky-note fa-2x mb-3"></i>
                                                    <p>No hay detalles adicionales</p>
                                                @endif
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Si necesitas mostrar más información, puedes agregar otra tarjeta aquí -->
                                <div class="card mt-3">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-history me-2"></i>Información del Registro
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <small class="text-muted">Creado:</small><br>
                                                {{ $proveedor->created_at->format('d/m/Y H:i') }}
                                            </li>
                                            <li>
                                                <small class="text-muted">Actualizado:</small><br>
                                                {{ $proveedor->updated_at->format('d/m/Y H:i') }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opcional: Si necesitas acciones adicionales -->
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                @can('proveedores.gestionar')
<a href="{{ route('proveedors.edit', $proveedor->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Modificar
                                </a>
@endcan
                            </div>
                            <div>
                                @can('proveedores.gestionar')
<form action="{{ route('proveedors.destroy', $proveedor->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este proveedor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
@endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

