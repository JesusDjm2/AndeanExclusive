@extends('layouts.app')
@section('titulo', 'Nuevo Agente Comercial')

@section('contenido')
    <div class="container-fluid px-0">
        {{-- Encabezado --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 ae-admin-page-header mb-4">
            <div>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                    Nuevo Agente Comercial
                </h1>
                <small class="ae-admin-page-desc">Registra un nuevo agente de viajes, agencia aliada o representante comercial B2B.</small>
            </div>
            <div>
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
                            <h4 class="mb-0 fw-bold">Ficha de Registro</h4>
                            <span class="text-muted small">Ingresa los canales de contacto directo del agente o agencia aliada.</span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('agentes.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @include('programas.agentes.form', ['modo' => 'crear'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
