@extends('layouts.app')
@section('titulo', 'Programas por año y mes')
@section('contenido')
    @php
        $mesCorto = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    @endphp
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-4 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-calendar-alt text-primary me-2"></i>
                    Programas por año y mes
                </h2>
                <small class="ae-admin-page-desc">Selecciona año y mes; mismo criterio que en el listado con filtros.</small>
            </div>
            <div class="col-lg-4 text-center mb-2 mb-lg-0">
                <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-2">
                    <a href="{{ route('programas.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i> Listado completo
                    </a>
                    <a href="{{ route('programas.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Nuevo programa
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver atrás
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($sinAsignarCount > 0)
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ $sinAsignarCount }}</strong> programa(s) sin año o mes asignado. Asígnalos al crear o editar
                        para que aparezcan en esta vista.
                    </div>
                    <a href="{{ route('programas.index') }}" class="btn btn-sm btn-outline-dark">Ir al listado</a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($anios->isEmpty())
            <div class="card shadow">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-database fa-2x mb-3 d-block"></i>
                    <p class="mb-0">No hay años configurados. Ejecuta migraciones y seeders (<code>MesSeeder</code>,
                        <code>AnioSeeder</code>).</p>
                </div>
            </div>
        @else
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white border-bottom text-center">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-calendar-day me-2"></i>Año
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-1 gap-md-2 justify-content-center">
                        @foreach ($anios as $anio)
                            @php
                                $cnt = (int) ($countsByAnio[$anio->id] ?? 0);
                                $isActive = (int) $anioId === (int) $anio->id;
                            @endphp
                            <a href="{{ route('programas.por-periodo', ['anio_id' => $anio->id]) }}"
                                class="btn btn-sm {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }}">
                                {{ $anio->anio }}
                                <span
                                    class="badge {{ $isActive ? 'bg-light text-primary' : 'bg-secondary' }} ms-1">{{ $cnt }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white border-bottom d-flex flex-wrap align-items-center gap-2">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-th-large me-2"></i>Mes
                    </h6>
                    @if ($anioActivo)
                        <span class="small text-muted">· {{ $anioActivo->anio }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2">
                        @foreach ($meses as $mes)
                            @php
                                $c = (int) ($monthCounts[$mes->id] ?? 0);
                                $isActive = (int) $mesId === (int) $mes->id && $anioId;
                                $abbr = $mesCorto[$mes->numero - 1] ?? $mes->nombre;
                                $href =
                                    $anioId
                                        ? route('programas.por-periodo', [
                                            'anio_id' => $anioId,
                                            'mes_id' => $mes->id,
                                        ])
                                        : '#';
                            @endphp
                            <div class="col">
                                @if ($anioId)
                                    <a href="{{ $href }}"
                                        class="card h-100 text-decoration-none text-body border {{ $isActive ? 'border-primary shadow-sm' : 'border-secondary border-opacity-25' }} {{ $c === 0 ? 'opacity-75' : '' }}">
                                        <div class="card-body p-2 p-md-3 text-center position-relative">
                                            <span
                                                class="position-absolute top-0 end-0 badge {{ $c > 0 ? 'bg-success' : 'bg-secondary' }} rounded-pill m-1">{{ $c }}</span>
                                            <div class="fw-bold text-primary fs-5 pt-1">{{ $abbr }}</div>
                                            <small class="text-muted d-block text-capitalize">{{ $mes->nombre }}</small>
                                        </div>
                                    </a>
                                @else
                                    <div class="card h-100 border border-secondary border-opacity-25 opacity-50">
                                        <div class="card-body p-2 p-md-3 text-center position-relative">
                                            <span class="position-absolute top-0 end-0 badge bg-secondary rounded-pill m-1">—</span>
                                            <div class="fw-bold text-muted fs-5 pt-1">{{ $abbr }}</div>
                                            <small class="text-muted d-block text-capitalize">{{ $mes->nombre }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($anioId && $mesId)
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <h3 class="h5 mb-0 fw-bold text-secondary">
                        <i class="fas fa-list-ul text-primary me-2"></i>
                        {{ optional($anioActivo)->anio }} · {{ optional($mesActivo)->nombre }}
                    </h3>
                    <span class="badge bg-primary rounded-pill">{{ $programas->count() }} programa(s)</span>
                </div>

                @if ($programas->isEmpty())
                    <div class="card shadow mb-5">
                        <div class="card-body text-center text-muted py-5">
                            <i class="far fa-folder-open fa-2x mb-3 d-block"></i>
                            No hay programas en este mes. Crea uno nuevo o asigna año y mes en programas existentes.
                        </div>
                    </div>
                @else
                    <div class="row g-3 mb-5">
                        @foreach ($programas as $programa)
                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-sm border h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <h4 class="h6 fw-bold mb-0">{{ $programa->nombre }}</h4>
                                            <span class="badge bg-light text-dark border">{{ $programa->codigo }}</span>
                                        </div>
                                        <p class="small text-muted mb-3">
                                            <i class="fas fa-users me-1"></i>{{ $programa->paxs->count() }}
                                            {{ $programa->paxs->count() === 1 ? 'pax' : 'paxs' }}
                                            @if ($programa->agentes->isNotEmpty())
                                                <span class="mx-1">·</span>
                                                <i class="fas fa-user-tie me-1"></i>{{ $programa->agentes->pluck('nombre')->join(', ') }}
                                            @endif
                                        </p>
                                        <div class="d-flex flex-wrap gap-1">
                                            {{-- @if ($programa->email && filter_var(trim($programa->email), FILTER_VALIDATE_EMAIL))
                                                <form action="{{ route('programas.enviar-correo', $programa) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Enviar correo con PDF a {{ e($programa->email) }}?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Enviar correo"><i
                                                            class="fas fa-paper-plane"></i></button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary disabled"
                                                    title="Sin correo válido"><i class="fas fa-paper-plane"></i></button>
                                            @endif --}}
                                            <a href="{{ route('programas.show', $programa) }}" target="_blank"
                                                class="btn btn-sm btn-info" title="Ver"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('programas.edit', $programa) }}"
                                                class="btn btn-sm btn-warning" title="Editar"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="{{ route('programas.pdf', $programa) }}"
                                                class="btn btn-sm btn-danger" title="PDF"><i
                                                    class="fas fa-file-pdf"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @elseif($anioId && !$mesId)
                <div class="card shadow mb-5">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-hand-pointer fa-2x mb-3 d-block text-primary"></i>
                        Selecciona un mes en la cuadrícula para ver los programas.
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
