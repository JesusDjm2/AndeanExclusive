@extends('layouts.app')
@section('titulo', 'Listado de Paxs')
@section('contenido')
    @php
        $totalPaxs = $paxsPorPrograma->flatten(1)->count();
    @endphp
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-4 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-users text-primary me-2"></i>
                    Pasajeros (Paxs)
                </h2>
                <small class="ae-admin-page-desc">Agrupados por programa · {{ $totalPaxs }}
                    {{ $totalPaxs === 1 ? 'pasajero' : 'pasajeros' }}</small>
            </div>
            <div class="col-lg-4 text-center mb-2 mb-lg-0">
                <a href="{{ route('paxs.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Crear nuevo Pax
                </a>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('home') }}" class="btn btn-danger btn-sm">
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

        <div class="card shadow mb-3 ae-admin-filter-card">
            <div class="card-body">
                <label class="form-label small text-muted mb-1" for="buscadorPaxs">Búsqueda en tiempo real</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="search" id="buscadorPaxs" class="form-control border-start-0"
                        placeholder="Nombre, pasaporte (archivo) o nacionalidad…" autocomplete="off"
                        aria-label="Filtrar pasajeros por nombre, pasaporte o nacionalidad">
                </div>
                <small class="text-muted d-block mt-2" id="paxContadorFiltro"></small>
            </div>
        </div>

        <div id="paxSinCoincidencias" class="alert alert-light border shadow-sm d-none" role="status">
            <i class="fas fa-info-circle text-primary me-2"></i>
            No hay pasajeros que coincidan con la búsqueda.
        </div>

        @if ($totalPaxs === 0)
            <div class="card shadow">
                <div class="card-body text-center text-muted py-5">
                    No hay paxs registrados.
                </div>
            </div>
        @else
            <div id="paxGruposContenedor">
                @foreach ($paxsPorPrograma as $programaId => $grupo)
                    @php
                        $programa = optional($grupo->first())->programa;
                        $codigo = optional($programa)->codigo ?? '';
                        $agentesPrograma = collect();
                        if ($programa) {
                            $agentesPrograma = $programa->agentes;
                            $resp = $programa->agenteResponsable;
                            if ($resp && !$agentesPrograma->pluck('id')->contains($resp->id)) {
                                $agentesPrograma = $agentesPrograma->prepend($resp);
                            }
                        }
                    @endphp
                    <div class="card shadow-sm mb-3 pax-grupo-programa" data-pax-grupo>
                        <div
                            class="card-header py-3 bg-dark border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div>
                                <h3 class="h6 mb-0 fw-bold text-white">
                                    <i class="fas fa-suitcase-rolling text-primary me-2"></i>
                                    {{ $programa->nombre ?? 'Programa #' . $programaId }}
                                </h3>
                                @if ($programa)
                                    <div class="small text-white-50 mt-1">
                                        @if ($programa->anio || $programa->mes)
                                            <span class="text-white me-2">
                                                <i class="far fa-calendar-alt me-1"></i>
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
                                        @if ($codigo !== '')
                                            <span class="text-white">Código: <code
                                                    class="text-white">{{ $codigo }}</code></span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <span class="badge bg-primary rounded-pill pax-grupo-count">{{ $grupo->count() }}
                                {{ $grupo->count() === 1 ? 'pax' : 'paxs' }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 ae-admin-data-table">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Edad</th>
                                        <th>Nacionalidad</th>
                                        <th>Alimentación</th>
                                        <th>Pasaporte</th>
                                        <th>Agentes (programa)</th>
                                        <th class="text-center" style="min-width: 140px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grupo as $pax)
                                        @php
                                            $pasPath = $pax->pasaporte;
                                            $pasBase = $pasPath ? basename($pasPath) : '';
                                            if ($pasPath) {
                                                if (
                                                    \Illuminate\Support\Str::startsWith($pasPath, [
                                                        'http://',
                                                        'https://',
                                                    ])
                                                ) {
                                                    $pasUrl = $pasPath;
                                                } elseif (\Illuminate\Support\Str::startsWith($pasPath, 'img/')) {
                                                    $pasUrl = asset($pasPath);
                                                } else {
                                                    $pasUrl = asset('storage/' . ltrim($pasPath, '/'));
                                                }
                                            } else {
                                                $pasUrl = null;
                                            }
                                            $searchRaw =
                                                $pax->nombre . ' ' . $pasBase . ' ' . ($pax->nacionalidad ?? '');
                                            $searchNorm = mb_strtolower(
                                                preg_replace('/\s+/u', ' ', trim($searchRaw)),
                                                'UTF-8',
                                            );
                                        @endphp
                                        <tr class="pax-fila" data-pax-search="{{ e($searchNorm) }}">
                                            <td>{{ $pax->id }}</td>
                                            <td class="fw-semibold">{{ $pax->nombre }}</td>
                                            <td>{{ $pax->edad }}</td>
                                            <td>{{ $pax->nacionalidad }}</td>
                                            <td>
                                                <span class="small">{{ $pax->alimentacion ?: '—' }}</span>
                                            </td>
                                            <td>
                                                @if ($pasUrl)
                                                    <a href="{{ $pasUrl }}" target="_blank" rel="noopener"
                                                        class="small text-break d-inline-block" style="max-width: 12rem"
                                                        title="{{ $pasBase }}">
                                                        <i class="fas fa-file-alt me-1"></i>{{ Str::limit($pasBase, 28) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @forelse ($agentesPrograma as $ag)
                                                    <span class="badge bg-secondary me-1">{{ $ag->nombre }}</span>
                                                @empty
                                                    <span class="text-muted small">—</span>
                                                @endforelse
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-sm" href="{{ route('paxs.show', $pax) }}"
                                                    title="Ver"><i class="fas fa-eye"></i></a>
                                                <a class="btn btn-warning btn-sm" href="{{ route('paxs.edit', $pax) }}"
                                                    title="Editar"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('paxs.destroy', $pax) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit"
                                                        onclick="return confirm('¿Eliminar este pax?');" title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        (function() {
            const input = document.getElementById('buscadorPaxs');
            const contador = document.getElementById('paxContadorFiltro');
            const sinCoincidencias = document.getElementById('paxSinCoincidencias');
            const grupos = document.querySelectorAll('[data-pax-grupo]');
            if (!input || grupos.length === 0) return;

            const totalFilas = document.querySelectorAll('.pax-fila').length;

            function normalizar(s) {
                return (s || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
            }

            function aplicarFiltro() {
                const q = normalizar(input.value);
                let visibles = 0;

                grupos.forEach(function(grupo) {
                    let visiblesGrupo = 0;
                    grupo.querySelectorAll('.pax-fila').forEach(function(fila) {
                        const haystack = normalizar(fila.getAttribute('data-pax-search') || '');
                        const ok = q === '' || haystack.indexOf(q) !== -1;
                        fila.classList.toggle('d-none', !ok);
                        if (ok) {
                            visibles++;
                            visiblesGrupo++;
                        }
                    });
                    grupo.classList.toggle('d-none', visiblesGrupo === 0);
                    const badge = grupo.querySelector('.pax-grupo-count');
                    if (badge) {
                        badge.textContent = visiblesGrupo + (visiblesGrupo === 1 ? ' pax' : ' paxs');
                    }
                });

                if (contador) {
                    if (q === '') {
                        contador.textContent = 'Mostrando todos los pasajeros (' + totalFilas + ').';
                    } else {
                        contador.textContent = visibles + ' de ' + totalFilas + ' pasajeros coinciden.';
                    }
                }
                if (sinCoincidencias) {
                    sinCoincidencias.classList.toggle('d-none', visibles > 0 || q === '');
                }
            }

            input.addEventListener('input', aplicarFiltro);
            input.addEventListener('search', aplicarFiltro);
            aplicarFiltro();
        })();
    </script>
@endpush
