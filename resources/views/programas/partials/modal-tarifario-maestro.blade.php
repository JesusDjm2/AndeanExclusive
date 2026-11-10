{{--
    Modal para consultar y aplicar servicios del Tarifario Maestro 2027 (Aracari)
    en cualquier fila del itinerario en el formulario de programas.
--}}
<div class="modal fade" id="modalTarifarioMaestro" tabindex="-1" aria-labelledby="modalTarifarioMaestroLabel" aria-hidden="true" data-modal-tarifario-maestro>
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-calculator fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fs-6 fw-bold mb-0 text-white" id="modalTarifarioMaestroLabel">
                            Tarifario Maestro Oficial 2027 · Andean Aracari
                        </h5>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            Tours, traslados, boletos oficiales y experiencias con escala 1 a 20 pasajeros.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-3 bg-light">
                {{-- Barra de contexto de cotización (Adultos, Niños y Feriado) --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white">
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-7">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label small fw-semibold text-dark mb-0">
                                        <i class="fas fa-users text-primary me-1"></i>Pasajeros para cotizar:
                                    </label>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace fw-bold px-2 py-0.5" id="maestro-total-pax-badge" style="font-size: 0.72rem;">
                                        Total: 2 Pax
                                    </span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-dark fw-bold px-1.5" title="Adultos (tarifa normal)"><i class="fas fa-user text-primary me-1"></i>Adt</span>
                                            <button class="btn btn-outline-secondary px-1.5" type="button" data-maestro-adt-dec><i class="fas fa-minus"></i></button>
                                            <input type="number" id="maestro-adt-input" min="1" max="20" value="2" class="form-control text-center font-monospace fw-bold px-1" data-maestro-adt>
                                            <button class="btn btn-outline-secondary px-1.5" type="button" data-maestro-adt-inc><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-dark fw-bold px-1.5" title="Niños (tarifa reducida/estudiante)"><i class="fas fa-child text-info me-1"></i>Chd</span>
                                            <button class="btn btn-outline-secondary px-1.5" type="button" data-maestro-chd-dec><i class="fas fa-minus"></i></button>
                                            <input type="number" id="maestro-chd-input" min="0" max="20" value="0" class="form-control text-center font-monospace fw-bold px-1" data-maestro-chd>
                                            <button class="btn btn-outline-secondary px-1.5" type="button" data-maestro-chd-inc><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="maestro-pax-input" value="2" data-maestro-pax>
                            </div>

                            <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end pt-2 pt-md-0">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="maestro-feriado-switch" data-maestro-feriado>
                                    <label class="form-check-label small fw-semibold text-dark" for="maestro-feriado-switch">
                                        <i class="fas fa-calendar-day text-warning me-1"></i> Feriado (+50% guía)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Búsqueda y Filtros de Categoría --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white">
                    <div class="card-body p-3">
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="maestro-search-input" data-maestro-search
                                   placeholder="Buscar servicio por código (ej. FDAWA, BTG, APTHTL) o nombre...">
                            <button type="button" class="btn btn-outline-secondary" data-maestro-clear-search title="Limpiar"><i class="fas fa-times"></i></button>
                        </div>

                        {{-- Categorías --}}
                        <div class="d-flex flex-wrap gap-1 align-items-center" data-maestro-categorias>
                            <button type="button" class="btn btn-xs btn-dark py-1 px-2 fw-semibold" data-maestro-cat="">
                                Todos <span class="badge bg-light text-dark ms-1">{{ \App\Models\TarifarioServicio::where('activo', true)->count() }}</span>
                            </button>
                            @foreach (\App\Models\TarifarioServicio::CATEGORIAS as $catKey => $catLabel)
                                @php
                                    $cnt = \App\Models\TarifarioServicio::where('categoria', $catKey)->where('activo', true)->count();
                                @endphp
                                @if ($cnt > 0)
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" data-maestro-cat="{{ $catKey }}">
                                        {{ $catLabel }} <span class="badge bg-light text-muted border ms-1">{{ $cnt }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Listado de Resultados --}}
                <div class="bg-white rounded-3 shadow-sm border p-0 overflow-hidden" style="max-height: 380px; overflow-y: auto;">
                    <div id="maestro-loading" class="p-4 text-center text-muted" hidden>
                        <i class="fas fa-circle-notch fa-spin fa-2x text-primary mb-2"></i>
                        <p class="small mb-0">Cargando catálogo maestro...</p>
                    </div>

                    <div id="maestro-empty" class="p-4 text-center text-muted" hidden>
                        <i class="fas fa-search fa-2x text-secondary opacity-50 mb-2"></i>
                        <p class="small mb-0">No se encontraron servicios que coincidan con la búsqueda.</p>
                    </div>

                    <div class="list-group list-group-flush" id="maestro-lista-resultados" data-maestro-resultados>
                        {{-- Items dinámicos generados por JS --}}
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top py-2 px-3 d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size: 0.75rem;">
                    <i class="fas fa-info-circle text-primary me-1"></i> Al elegir un servicio, se aplicará el costo unitario según el número de pasajeros.
                </small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
