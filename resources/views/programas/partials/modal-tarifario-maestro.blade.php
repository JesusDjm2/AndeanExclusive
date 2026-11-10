{{--
    Modal para consultar y aplicar servicios del Tarifario Maestro 2027 (Aracari)
    en cualquier fila del itinerario en el formulario de programas.
--}}
<div class="modal fade tm-modal" id="modalTarifarioMaestro" tabindex="-1" aria-labelledby="modalTarifarioMaestroLabel" aria-hidden="true" data-modal-tarifario-maestro>
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header tm-modal__header py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-calculator fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title tm-modal__title fs-6 fw-bold mb-0" id="modalTarifarioMaestroLabel">
                            Tarifario Maestro Oficial 2027 · Andean Aracari
                        </h5>
                        <small class="tm-modal__subtitle" style="font-size: 0.75rem;">
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

                {{-- Paso 1: elegir el servicio del catálogo --}}
                <div data-maestro-paso="catalogo">
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
                {{-- Sin .overflow-hidden: su !important anulaba el scroll y solo se veían los primeros resultados. --}}
                <div class="tm-modal__resultados bg-white rounded-3 shadow-sm border p-0">
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

                {{-- Paso 2: revisar y editar lo que incluye antes de agregarlo (solo afecta a este programa) --}}
                <div data-maestro-paso="desglose" hidden>
                    <div class="tm-desglose__head card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-3 d-flex align-items-start gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary flex-shrink-0" data-maestro-volver title="Volver al catálogo" aria-label="Volver al catálogo">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <div class="min-w-0">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <span class="badge bg-dark font-monospace px-2 py-1" data-maestro-desglose-codigo></span>
                                    <span class="small text-muted" data-maestro-desglose-pax></span>
                                </div>
                                <div class="fw-bold" data-maestro-desglose-nombre></div>
                                <small class="text-muted d-block mt-1">
                                    Qué incluye este servicio. Cada parte se agrega como una fila del día, con su tipo y sin proveedor:
                                    Reservas elige el proveedor de cada una cuando el programa se confirme. Lo que cambies aquí no modifica el Tarifario.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning py-2 px-3 small mb-3" data-maestro-aviso-adicionales hidden>
                        <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>
                        Las entradas y comidas de este servicio no caben en la tarifa del catálogo, así que se proponen como <strong>adicionales</strong>: se suman aparte.
                        Desmárcalas si el proveedor ya las incluye.
                    </div>

                    <div class="tm-desglose__list" data-maestro-partes aria-live="polite"></div>
                    <div class="p-4 text-center text-muted" data-maestro-partes-loading hidden>
                        <i class="fas fa-circle-notch fa-spin fa-2x text-primary mb-2" aria-hidden="true"></i>
                        <p class="small mb-0">Preparando lo que incluye…</p>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" data-maestro-parte-agregar>
                        <i class="fas fa-plus me-1" aria-hidden="true"></i> Agregar parte
                    </button>

                    <div class="tm-desglose__resumen mt-3" data-maestro-resumen></div>
                </div>

                <template data-maestro-parte-template>
                    <div class="tm-parte" data-parte>
                        <label class="tm-parte__usar" title="Incluir esta parte">
                            <input type="checkbox" class="form-check-input" data-parte-usar checked aria-label="Incluir esta parte">
                        </label>
                        <select class="form-select form-select-sm tm-parte__tipo" data-parte-tipo aria-label="Tipo de servicio">
                            @foreach (\App\Models\Servicio::TIPOS as $clave => [$etiqueta])
                                <option value="{{ $clave }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control form-control-sm tm-parte__nombre" maxlength="255" data-parte-nombre aria-label="Nombre de la parte" placeholder="Nombre del servicio">
                        <div class="input-group input-group-sm tm-parte__costo">
                            <span class="input-group-text" data-parte-moneda>US$</span>
                            <input type="number" step="0.01" min="0" class="form-control" data-parte-costo aria-label="Costo neto por pasajero" placeholder="Sin precio">
                        </div>
                        <button type="button" class="btn btn-sm tm-parte__cliente" data-parte-cliente aria-pressed="false" title="Mostrar al cliente en el itinerario">
                            <i class="fas fa-eye-slash" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger tm-parte__quitar" data-parte-quitar title="Quitar parte" aria-label="Quitar parte">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                        <small class="tm-parte__origen"><span data-parte-origen></span></small>
                    </div>
                </template>
            </div>

            <div class="modal-footer bg-light border-top py-2 px-3 d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size: 0.75rem;" data-maestro-pie-texto>
                    <i class="fas fa-info-circle text-primary me-1"></i> Elige un servicio para revisar lo que incluye antes de agregarlo.
                </small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm fw-semibold" data-maestro-confirmar hidden>
                        <i class="fas fa-check me-1" aria-hidden="true"></i> <span data-maestro-confirmar-texto>Agregar al día</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
