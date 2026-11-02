{{-- Alta / edición de cliente (ClienteController@create, @edit). Estilos: _programas-form.scss (prog-section, pf-*) --}}
@extends('layouts.app')
@section('titulo', $cliente->exists ? $cliente->nombre : 'Nuevo cliente')

@php
    $isEdit = $cliente->exists;
    $canEdit = auth()->user()?->can('programas.gestionar');
@endphp

@section('contenido')
    <div class="pf">
        <div class="pf-header">
            <div class="pf-header__main">
                <a href="{{ route('clientes.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Clientes</a>
                <h1 class="pf-header__title">{{ $isEdit ? $cliente->nombre : 'Nuevo cliente' }}</h1>
                @if ($isEdit)
                    <p class="pf-header__meta">
                        <span>{{ $tipos[$cliente->tipo] ?? $cliente->tipo }}</span>
                        <span><i class="fas fa-suitcase-rolling" aria-hidden="true"></i>{{ $cliente->programas->count() }} programas</span>
                    </p>
                @endif
            </div>
            @if ($isEdit && $canEdit)
                <div class="pf-header__actions">
                    <a href="{{ route('programas.create', ['cliente_id' => $cliente->id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus" aria-hidden="true"></i> Nuevo programa para este cliente
                    </a>
                </div>
            @endif
        </div>

        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
        @endforeach

        <form id="cliente-form" method="POST" action="{{ $isEdit ? route('clientes.update', $cliente) : route('clientes.store') }}" novalidate>
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <fieldset class="pf-fieldset" @disabled(! $canEdit)>
            <div class="prog-section">
                <div class="prog-section__body">
                    @include('clientes.partials.campos', ['cliente' => $cliente, 'prefix' => '', 'idPrefix' => 'cli'])
                </div>
            </div>
        </fieldset>
        </form>

        @if ($isEdit)
            <div class="prog-section">
                <div class="prog-section__header"><span class="prog-section__title">Historial de programas</span></div>
                @if ($cliente->programas->isEmpty())
                    <div class="admin-empty"><i class="fas fa-suitcase" aria-hidden="true"></i> Sin programas todavía.</div>
                @else
                    <div class="table-responsive">
                        <table class="table ae-admin-data-table mb-0">
                            <thead><tr><th>Programa</th><th>Fechas</th><th>Estado</th><th class="text-end">Total</th><th class="text-end">Pagado</th></tr></thead>
                            <tbody>
                                @foreach ($cliente->programas as $programa)
                                    @php $cobro = $programa->cobranza(); @endphp
                                    <tr>
                                        <td><a href="{{ route('programas.edit', $programa) }}" class="fw-semibold text-decoration-none">{{ $programa->nombre }}</a><small class="d-block text-muted">{{ $programa->codigo }}</small></td>
                                        <td class="small">{{ $programa->inicio ? \Carbon\Carbon::parse($programa->inicio)->locale('es')->isoFormat('D MMM YYYY') : '—' }}</td>
                                        <td><span class="pf-status pf-status--{{ $programa->estado }}">{{ \App\Models\Programa::ESTADOS[$programa->estado] ?? $programa->estado }}</span></td>
                                        <td class="text-end">${{ number_format($cobro['total'], 2) }}</td>
                                        <td class="text-end">{{ $cobro['porcentaje'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if ($canEdit && $cliente->programas->isEmpty())
                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" data-confirm="Se eliminará el cliente {{ $cliente->nombre }}."
                    data-confirm-title="¿Eliminar cliente?" data-confirm-button="Sí, eliminar" data-confirm-danger>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt" aria-hidden="true"></i> Eliminar cliente</button>
                </form>
            @endif
        @endif

        @if ($canEdit)
            <div class="pf-actionbar">
                <div class="pf-actionbar__buttons">
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" form="cliente-form" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Guardar cambios' : 'Crear cliente' }}</button>
                </div>
            </div>
        @endif
    </div>
@endsection
