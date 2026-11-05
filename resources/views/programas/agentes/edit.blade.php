@extends('layouts.app')
@section('titulo', 'Editar Agente · ' . $agente->nombre)

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-user-edit" aria-hidden="true"></i>
                    Editar Agente Comercial
                </h1>
                <small class="ae-admin-page-desc">Actualiza la información de contacto o fotografía de <strong>{{ $agente->nombre }}</strong>.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('agentes.show', $agente) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-eye me-1"></i> Ver Ficha
                </a>
                <a href="{{ route('agentes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Agentes
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="card-header p-4 border-bottom bg-transparent d-flex align-items-center gap-3">
                        <span class="ae-avatar-circle ae-avatar-circle--gold" style="width: 3.5rem; height: 3.5rem; font-size: 1.25rem;">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $agente->nombre }}</h4>
                            <span class="text-muted small">Modifica los canales de contacto directo o la imagen de perfil.</span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('agentes.update', $agente) }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            @include('programas.agentes.form', ['modo' => 'editar'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
