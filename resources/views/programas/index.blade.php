@extends('layouts.app')
@section('titulo', 'Programas')
@section('title', 'Programas')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col text-start">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-layer-group text-primary me-2"></i>
                    Programas
                </h2>
                <small class="ae-admin-page-desc">
                    Gestión y visualización de programas
                </small>
            </div>

            <div class="col text-center">
                <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-2">
                    <a href="{{ route('programas.por-periodo') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-calendar-alt"></i> Por año y mes
                    </a>
                    <a href="{{ route('programas.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Nuevo Programa
                    </a>
                </div>
            </div>
            <div class="col text-end">
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver atrás
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-3 ae-admin-filter-card">
            <div class="card-body">
                <form method="GET" action="{{ route('programas.index') }}"
                    class="row g-2 align-items-end">
                    <div class="col-md-3 col-lg-2">
                        <label class="form-label small text-muted mb-0">Año</label>
                        <select name="anio_id" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach ($anios as $anio)
                                <option value="{{ $anio->id }}"
                                    {{ (string) request('anio_id') === (string) $anio->id ? 'selected' : '' }}>
                                    {{ $anio->anio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <label class="form-label small text-muted mb-0">Mes</label>
                        <select name="mes_id" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach ($meses as $mes)
                                <option value="{{ $mes->id }}"
                                    {{ (string) request('mes_id') === (string) $mes->id ? 'selected' : '' }}>
                                    {{ $mes->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('programas.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 70vh;">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table ae-admin-table-sticky">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-signature fa-sm"></i> Nombre</th>
                                <th><i class="fas fa-coins fa-sm"></i> Precios</th>
                                <th><i class="fas fa-hotel fa-sm"></i> Hoteles / <i class="fas fa-handshake fa-sm"></i> Prov.</th>
                                <th><i class="fas fa-users fa-sm"></i> Lista de Paxs</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($programas as $programa)
                                <tr>
                                    <td>{{ $programa->id }}</td>
                                    <td>
                                        <div class="fw-bold d-flex align-items-center gap-2 flex-wrap">
                                            {{ $programa->nombre }}
                                            @if ($programa->anio || $programa->mes)
                                                <span class="badge bg-secondary fw-normal">
                                                    @if ($programa->anio)
                                                        {{ $programa->anio->anio }}
                                                    @endif
                                                    @if ($programa->mes)
                                                        @if ($programa->anio)
                                                            ·
                                                        @endif
                                                        {{ $programa->mes->nombre }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        <span class="small d-flex align-items-center">
                                            <i class="fas fa-fw fa-users me-1"></i>
                                            {{ $programa->paxs->count() }}
                                            {{ $programa->paxs->count() === 1 ? 'Pax' : 'Paxs' }}
                                        </span>
                                        <div class="small">
                                            <i class="fas fa-fw fa-code me-1"></i>Código: {{ $programa->codigo }}
                                        </div>
                                        @if ($programa->email)
                                            <div class="small text-muted">
                                                <i class="fas fa-fw fa-envelope me-1"></i>{{ $programa->email }}
                                            </div>
                                        @endif

                                        <div class="mt-2">
                                            <span class="subtitulos"><i class="far fa-calendar-alt me-1"></i> Fechas:</span>
                                            <br>
                                            @if ($programa->inicio && $programa->fin)
                                                <p>
                                                    Inicio:{{ \Carbon\Carbon::parse($programa->inicio)->translatedFormat('d \d\e F \d\e Y') }}
                                                    <br>
                                                    Fin:{{ \Carbon\Carbon::parse($programa->fin)->translatedFormat('d \d\e F \d\e Y') }}
                                                    @php
                                                        $dias =
                                                            \Carbon\Carbon::parse($programa->inicio)->diffInDays(
                                                                \Carbon\Carbon::parse($programa->fin),
                                                            ) + 1;
                                                    @endphp
                                                    <br>Duración: {{ $dias }} {{ $dias > 1 ? 'días' : 'día' }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="mt-2">
                                            <span class="subtitulos mb-2"><i class="fas fa-language me-1"></i>
                                                Idioma:</span><br>
                                            @if ($programa->lang)
                                                @php
                                                    $idiomas = [
                                                        'es' => 'Español',
                                                        'en' => 'Inglés',
                                                        'pt' => 'Portugués',
                                                        'fr' => 'Francés',
                                                    ];
                                                @endphp
                                                <span class="badge bg-info text-dark">
                                                    {{ $idiomas[$programa->lang] ?? $programa->lang }}
                                                </span>
                                            @else
                                                <span class="text-muted small">Sin idioma especificado</span>
                                            @endif
                                        </div>

                                        <div class="mt-2">
                                            <span class="subtitulos mb-2"><i class="fas fa-user-tie"></i>
                                                Agente(s):</span><br>
                                            @forelse ($programa->agentes as $agente)
                                                <span class="badge bg-primary">{{ $agente->nombre }}</span>
                                            @empty
                                                <span class="text-muted small">Sin agentes</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td>
                                        {{-- Precios --}}
                                        @if ($programa->precioAdulto > 0 || $programa->precioChild > 0)
                                            <div class="mt-2 rounded">
                                                @php
                                                    // Clasificación por edades
                                                    $totalAdultos = $programa->paxs->where('edad', '>=', 12)->count();
                                                    $totalNinos = $programa->paxs
                                                        ->whereBetween('edad', [5, 11])
                                                        ->count();
                                                    $totalInfantes = $programa->paxs->where('edad', '<', 5)->count();

                                                    $precioAdulto = floatval($programa->precioAdulto);
                                                    $precioChild = floatval($programa->precioChild);

                                                    $subtotalAdultos = $totalAdultos * $precioAdulto;
                                                    $subtotalNinos = $totalNinos * $precioChild;
                                                    $totalGeneral = $subtotalAdultos + $subtotalNinos;

                                                    // Estadísticas de pasajeros
                                                    $totalPasajeros = $programa->paxs->count();
                                                    $porcentajeAdultos =
                                                        $totalPasajeros > 0
                                                            ? round(($totalAdultos / $totalPasajeros) * 100)
                                                            : 0;
                                                    $porcentajeNinos =
                                                        $totalPasajeros > 0
                                                            ? round(($totalNinos / $totalPasajeros) * 100)
                                                            : 0;
                                                @endphp

                                                {{-- Resumen rápido de pasajeros --}}
                                                <div class="small mb-2">
                                                    {{ $totalAdultos }} adultos · {{ $totalNinos }} niños ·
                                                    {{ $totalInfantes }} infantes
                                                </div>

                                                <div class="small">
                                                    @if ($precioAdulto > 0 && $totalAdultos > 0)
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                Adultos ({{ $totalAdultos }} x
                                                                ${{ number_format($precioAdulto, 2) }}):
                                                            </span>
                                                            <span class="fw-bold">${{ number_format($subtotalAdultos, 2) }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($precioChild > 0 && $totalNinos > 0)
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                Niños 5-11 ({{ $totalNinos }} x
                                                                ${{ number_format($precioChild, 2) }}):
                                                            </span>
                                                            <span class="fw-bold">${{ number_format($subtotalNinos, 2) }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($totalInfantes > 0)
                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                Infantes ({{ $totalInfantes }}):
                                                            </span>
                                                            <span>Gratis</span>
                                                        </div>
                                                    @endif

                                                    @if ($totalGeneral > 0)
                                                        <hr class="my-1">
                                                        <div class="d-flex justify-content-between fw-bold text-primary">
                                                            <span>Total:</span>
                                                            <span>${{ number_format($totalGeneral, 2) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $fechasPorHabitacion = $programa->habitacionesFechas->keyBy('habitacion_id');
                                            $hoteles = $programa->habitaciones->groupBy(
                                                fn($h) => optional($h->hotel)->id,
                                            );
                                        @endphp
                                        <h6 class="text-secondary">Hoteles</h6>
                                        @if ($hoteles->isEmpty())
                                            <span class="text-muted">Sin hoteles</span>
                                        @else
                                            @foreach ($hoteles as $hotelId => $habitaciones)
                                                @php $hotel = $habitaciones->first()->hotel; @endphp

                                                @if ($hotel)
                                                    <div class="mb-2">
                                                        <span class="mb-1">
                                                            <i class="fas fa-hotel text-primary me-1"></i><span
                                                                class="subtitulos">{{ $hotel->nombre }}</span>
                                                            @if ($hotel->telefono)
                                                                <small class="text-muted"> · {{ $hotel->telefono }}</small>
                                                            @endif
                                                        </span>

                                                        <ul class="mb-0 small list-unstyled ms-1 mt-1">
                                                            @foreach ($habitaciones as $habitacion)
                                                                @php $hf = $fechasPorHabitacion->get($habitacion->id); @endphp
                                                                <li class="mb-2 ps-2 border-start border-2 border-primary border-opacity-25">
                                                                    <span class="fw-semibold">{{ $habitacion->tipo }}</span>
                                                                    @if ($hf)
                                                                        <div class="text-muted small mt-1">
                                                                            <i class="fas fa-sign-in-alt me-1"></i>Ingreso:
                                                                            {{ \Carbon\Carbon::parse($hf->fecha_ingreso)->format('d/m/Y') }}
                                                                            <span class="mx-1">·</span>
                                                                            <i class="fas fa-sign-out-alt me-1"></i>Salida:
                                                                            {{ \Carbon\Carbon::parse($hf->fecha_salida)->format('d/m/Y') }}
                                                                        </div>
                                                                    @else
                                                                        <div class="text-warning small mt-1">
                                                                            <i class="fas fa-calendar-times me-1"></i>Sin fechas
                                                                            asignadas
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        <h6 class="text-secondary mt-3">Proveedores</h6>
                                        @php
                                            $proveedoresPorCategoria = $programa->proveedores->groupBy(
                                                fn($p) => optional($p->categoria)->nombre ?? 'Sin categoría',
                                            );
                                        @endphp

                                        @if ($proveedoresPorCategoria->isEmpty())
                                            <span class="text-muted">Sin proveedores</span>
                                        @else
                                            @foreach ($proveedoresPorCategoria as $categoria => $proveedores)
                                                <div class="mb-2">
                                                    <span class="fw-semibold">
                                                        <span
                                                            class="subtitulos">{{ Str::plural($categoria, $proveedores->count()) }}</span>
                                                    </span>

                                                    <ul class="mb-0 small">
                                                        @foreach ($proveedores as $proveedor)
                                                            <li>{{ $proveedor->nombre }} → {{ $proveedor->telefono }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        @if ($programa->paxs->isEmpty())
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-fw fa-users"></i> 0 Paxs
                                            </span>
                                        @else
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($programa->paxs as $pax)
                                                    <li class="mb-2">
                                                        <div class="subtitulos">
                                                            {{ $loop->iteration }}. {{ $pax->nombre }}
                                                        </div>

                                                        <div class="small text-muted ms-3">
                                                            <i class="fas fa-fw fa-birthday-cake"></i>
                                                            Edad: {{ $pax->edad }} años<br>

                                                            <i class="fas fa-fw fa-flag fa-sm"></i>
                                                            Nacionalidad: {{ $pax->nacionalidad }}<br>

                                                            @if ($pax->alimentacion)
                                                                <i class="fas fa-fw fa-utensils fa-sm"></i>
                                                                Alimentación: {{ $pax->alimentacion }}
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('programas.pdf', $programa) }}" class="btn btn-danger btn-sm"
                                            style="margin-top: -0.3em" title="Exportar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('programas.show', $programa) }}" class="btn btn-info btn-sm"
                                            style="margin-top: -0.3em" target="_blank" title="Ver programa">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('programas.edit', $programa) }}" class="btn btn-warning btn-sm"
                                            style="margin-top: -0.3em">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('programas.destroy', $programa) }}" method="POST"
                                            class="form-eliminar-programa d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-eliminar-programa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No hay programas registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-eliminar-programa');
            if (!btn) return;
            const form = btn.closest('form');
            if (!form || typeof Swal === 'undefined') return;
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar programa?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(function(result) {
                if (result.isConfirmed) form.submit();
            });
        });
    </script>
@endsection
