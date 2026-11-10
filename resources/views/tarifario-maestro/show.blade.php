@extends('layouts.app')
@section('titulo', 'Servicio Maestro: ' . $servicio->codigo . ' · ' . $servicio->nombre)

@section('contenido')
<div class="container-fluid px-3 px-md-4 py-3">

    {{-- Breadcrumb y Navegación --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tarifario-maestro.index') }}" class="text-decoration-none">Tarifario Maestro</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $servicio->codigo }}</li>
        </ol>
    </nav>

    {{-- Encabezado de la Ficha --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-primary-subtle text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                        @php
                            $iconoCat = match($servicio->categoria) {
                                'tour' => 'fa-map-marked-alt',
                                'traslado' => 'fa-shuttle-van',
                                'trek' => 'fa-hiking',
                                'boleto' => 'fa-ticket-alt',
                                'alimentacion' => 'fa-utensils',
                                'viatico' => 'fa-hotel',
                                'propina' => 'fa-hand-holding-usd',
                                default => 'fa-calculator',
                            };
                        @endphp
                        <i class="fas {{ $iconoCat }} fs-3"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge bg-dark text-white font-monospace fs-6 px-2 py-1">{{ $servicio->codigo }}</span>
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $servicio->categoria_nombre }}</span>
                            @if ($servicio->duracion_horas)
                                <span class="badge bg-light text-muted border px-2 py-1"><i class="far fa-clock me-1"></i>{{ (float) $servicio->duracion_horas }} Horas</span>
                            @endif
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                Comisión Base: {{ (float) $servicio->comision_pct }}% {{ $servicio->aplica_igv_comision ? '+ IGV Fee' : '(Neto)' }}
                            </span>
                        </div>
                        <h1 class="h3 fw-bold text-dark mb-1">{{ $servicio->nombre }}</h1>
                        @if ($servicio->lugares_visita)
                            <p class="text-muted small mb-0 d-flex align-items-center gap-1">
                                <i class="fas fa-map-marker-alt text-primary"></i> <strong>Lugares incluidos:</strong> {{ $servicio->lugares_visita }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('tarifario-maestro.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Catálogo
                    </a>
                    @can('proveedores.gestionar')
                        <a href="{{ route('tarifario-maestro.edit', $servicio) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Editar Parámetros
                        </a>
                    @endcan
                </div>
            </div>

            @if ($servicio->descripcion)
                <div class="mt-3 pt-3 border-top text-muted small">
                    <strong class="text-dark">Descripción y Notas Operativas:</strong>
                    <p class="mb-0 mt-1">{{ $servicio->descripcion }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- SIMULADOR EN VIVO DE COTIZACIÓN --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white border-top border-4 border-primary">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-calculator text-primary"></i> Simulador Financiero en Tiempo Real
                </h5>
                <span class="badge bg-light text-muted border">
                    Fórmula Oficial Aracari (Costo + {{ (float) $servicio->comision_pct }}% fee + 18% IGV del fee)
                </span>
            </div>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="row g-4 align-items-center">
                {{-- Controles del Simulador --}}
                <div class="col-12 col-lg-5">
                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-sliders-h text-primary me-2"></i>Parámetros de Cotización</h6>
                        
                        {{-- Control Pasajeros --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="sim-pax" class="form-label small fw-semibold text-dark mb-0">Número de Pasajeros (Pax):</label>
                                <span class="badge bg-primary fs-6 px-3 py-1 font-monospace" id="sim-pax-badge">2 Pax</span>
                            </div>
                            <input type="range" class="form-range" id="sim-pax" min="1" max="20" value="2" oninput="actualizarSimulador()">
                            <div class="d-flex justify-content-between small text-muted" style="font-size: 0.72rem;">
                                <span>1 Pax</span>
                                <span>5 Pax</span>
                                <span>10 Pax</span>
                                <span>15 Pax</span>
                                <span>20 Pax</span>
                            </div>
                        </div>

                        {{-- Control Feriado --}}
                        <div class="mb-3 pt-2 border-top">
                            <div class="form-check form-switch d-flex align-items-center justify-content-between ps-0">
                                <label class="form-check-label small fw-semibold text-dark" for="sim-feriado">
                                    <i class="fas fa-calendar-day text-warning me-1"></i> Tarifa Feriado / Festivo (+50% guía)
                                </label>
                                <input class="form-check-input ms-2" type="checkbox" role="switch" id="sim-feriado" onchange="actualizarSimulador()">
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                Aplica suplemento festivo oficial según calendario nacional y de Cusco.
                            </small>
                        </div>

                        {{-- Control Tipo Pasajero (solo si aplica adulto/niño) --}}
                        @if ($servicio->precio_nino)
                            <div class="mb-0 pt-2 border-top">
                                <label class="form-label small fw-semibold text-dark mb-1">Categoría Pasajero:</label>
                                <div class="btn-group btn-group-sm w-100" role="group">
                                    <input type="radio" class="btn-check" name="sim-tipo-pax" id="sim-pax-adulto" value="adulto" checked onchange="actualizarSimulador()">
                                    <label class="btn btn-outline-secondary" for="sim-pax-adulto">Adulto</label>
                                    <input type="radio" class="btn-check" name="sim-tipo-pax" id="sim-pax-nino" value="nino" onchange="actualizarSimulador()">
                                    <label class="btn btn-outline-secondary" for="sim-pax-nino">Niño / Estudiante</label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Resultados en Vivo --}}
                <div class="col-12 col-lg-7">
                    <div class="row g-3">
                        <div class="col-6 col-md-6">
                            <div class="p-3 bg-white border rounded-3 text-center shadow-sm">
                                <span class="text-muted small text-uppercase fw-semibold d-block" style="font-size: 0.7rem;">Costo Neto Unitario</span>
                                <span class="fs-4 fw-bold text-secondary font-monospace" id="res-costo-unitario">$ 0.00</span>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Neto por persona</small>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="p-3 bg-white border rounded-3 text-center shadow-sm">
                                <span class="text-muted small text-uppercase fw-semibold d-block" style="font-size: 0.7rem;">Comisión + IGV Fee</span>
                                <span class="fs-4 fw-bold text-info font-monospace" id="res-fee">$ 0.00</span>
                                <small class="text-muted d-block" style="font-size: 0.72rem;" id="res-fee-detalle">Fee {{ (float)$servicio->comision_pct }}%</small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-primary-subtle border border-primary-subtle rounded-3 text-center shadow-sm">
                                <span class="text-primary small text-uppercase fw-bold d-block" style="font-size: 0.72rem;">
                                    <i class="fas fa-user-tag me-1"></i> Precio Venta Sugerido (Pax)
                                </span>
                                <span class="fs-3 fw-bolder text-primary font-monospace" id="res-venta-pax">$ 0.00</span>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Para cotización por persona</small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-dark-subtle border border-dark-subtle rounded-3 text-center shadow-sm">
                                <span class="text-dark small text-uppercase fw-bold d-block" style="font-size: 0.72rem;">
                                    <i class="fas fa-users me-1"></i> Total Venta del Grupo
                                </span>
                                <span class="fs-3 fw-bolder text-dark font-monospace" id="res-venta-grupo">$ 0.00</span>
                                <small class="text-muted d-block" style="font-size: 0.75rem;" id="res-grupo-detalle">Total para 2 pax</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MATRIZ COMPLETA DE 1 A 20 PASAJEROS (Para servicios de Escala) --}}
    @if ($servicio->tipo_calculo === 'escala_pax' && !empty($servicio->precios_escalonados))
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-table text-primary"></i> Matriz Completa de Tarifas Escalonadas (1 a 20 Pax)
                </h5>
                <span class="badge bg-light text-muted border">Vigente 2027 · Tarifas en USD</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" style="font-size: 0.88rem;">
                    <thead class="table-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                        <tr>
                            <th class="text-start ps-4" style="width: 140px;">Rango / Pax</th>
                            <th>Costo Neto Regular (Pax)</th>
                            <th class="table-primary fw-bold text-primary">Venta Aracari Regular (Pax)</th>
                            <th>Costo Neto Feriado (Pax)</th>
                            <th class="table-warning fw-bold text-dark">Venta Aracari Feriado (Pax)</th>
                            <th class="text-end pe-4">Total Grupo Regular</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $paxList = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 14, 16, 18, 20];
                        @endphp
                        @foreach ($paxList as $p)
                            @php
                                $calcNormal = $servicio->calcularFinanciero($p, false);
                                $calcFeriado = $servicio->calcularFinanciero($p, true);
                            @endphp
                            <tr>
                                <td class="text-start ps-4 fw-bold">
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                        {{ $p }} {{ $p === 1 ? 'Pasajero' : 'Pasajeros' }}
                                    </span>
                                </td>
                                <td class="font-monospace text-secondary">
                                    ${{ number_format($calcNormal['costo_unitario'], 2) }}
                                </td>
                                <td class="font-monospace fw-bold text-primary table-primary">
                                    ${{ number_format($calcNormal['precio_venta_pax'], 2) }}
                                </td>
                                <td class="font-monospace text-muted">
                                    ${{ number_format($calcFeriado['costo_unitario'], 2) }}
                                </td>
                                <td class="font-monospace fw-bold text-dark table-warning">
                                    ${{ number_format($calcFeriado['precio_venta_pax'], 2) }}
                                </td>
                                <td class="text-end pe-4 font-monospace fw-semibold text-dark">
                                    ${{ number_format($calcNormal['precio_venta_grupo'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- DETALLE DE BOLETOS / TARIFAS POR PERSONA --}}
    @if ($servicio->tipo_calculo === 'por_persona')
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-ticket-alt text-primary me-2"></i>Tarifas Oficiales Individuales</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted">Tarifa Adulto General:</span>
                            <span class="fs-5 fw-bold text-dark font-monospace">${{ number_format($servicio->precio_adulto ?? 0, 2) }}</span>
                        </div>
                        @if ($servicio->precio_nino)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted">Tarifa Niño / Estudiante (CHD):</span>
                                <span class="fs-5 fw-bold text-info font-monospace">${{ number_format($servicio->precio_nino, 2) }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted">Comisión de Agencia:</span>
                            <span class="fw-semibold text-success">{{ (float) $servicio->comision_pct }}%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted">Aplica IGV sobre Comisión:</span>
                            <span class="badge {{ $servicio->aplica_igv_comision ? 'bg-success-subtle text-success' : 'bg-light text-muted' }}">
                                {{ $servicio->aplica_igv_comision ? 'Sí (18%)' : 'Exonerado / No aplica' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-info-circle text-primary me-2"></i>Requisitos y Condiciones Oficiales</h6>
                    </div>
                    <div class="card-body p-4 small text-muted">
                        <p class="mb-2"><strong class="text-dark">Documentación Obligatoria:</strong> Pasaporte vigente o DNI para nacionales. Menores de edad requieren acreditación de parentesco y documento de identidad.</p>
                        <p class="mb-2"><strong class="text-dark">Estudiantes:</strong> Requieren carné universitario vigente emitido por institución reconocida por SUNEDU o ISIC según normativa del MINCUL.</p>
                        <p class="mb-0"><strong class="text-dark">Políticas de Emisión:</strong> Las entradas a Machu Picchu y caminos incas son nominativas, intransferibles y no reembolsables una vez emitidas.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
const SERVICIO_DATA = {
    id: {{ $servicio->id }},
    codigo: @json($servicio->codigo),
    tipo_calculo: @json($servicio->tipo_calculo),
    comision_pct: {{ (float) $servicio->comision_pct }},
    aplica_igv_comision: {{ $servicio->aplica_igv_comision ? 'true' : 'false' }},
    precio_adulto: {{ (float) ($servicio->precio_adulto ?? 0) }},
    precio_nino: {{ (float) ($servicio->precio_nino ?? 0) }},
    costo_grupo: {{ (float) ($servicio->costo_grupo ?? 0) }},
    escalonados: @json($servicio->precios_escalonados ?? []),
};

function calcularCostoLocal(pax, feriado, tipoPax) {
    if (SERVICIO_DATA.tipo_calculo === 'por_persona') {
        const precio = (tipoPax === 'nino' && SERVICIO_DATA.precio_nino > 0) ? SERVICIO_DATA.precio_nino : SERVICIO_DATA.precio_adulto;
        return { unitario: precio, total: precio * pax };
    }
    if (SERVICIO_DATA.tipo_calculo === 'por_grupo') {
        const costo = SERVICIO_DATA.costo_grupo;
        return { unitario: pax > 0 ? (costo / pax) : costo, total: costo };
    }
    if (SERVICIO_DATA.tipo_calculo === 'escala_pax' && SERVICIO_DATA.escalonados) {
        const esc = SERVICIO_DATA.escalonados;
        const seccion = (feriado && esc.feriado) ? esc.feriado : (esc.normal || esc);
        if (seccion) {
            const paxKey = String(Math.min(20, Math.max(1, pax)));
            let costo = seccion[paxKey];
            if (costo === undefined) {
                const keys = Object.keys(seccion).map(Number).filter(k => !isNaN(k)).sort((a, b) => a - b);
                let closestKey = keys[0];
                for (let k of keys) {
                    if (k <= pax) closestKey = k;
                    else break;
                }
                costo = closestKey !== undefined ? seccion[String(closestKey)] : 0;
            }
            costo = parseFloat(costo) || 0;
            return { unitario: costo, total: costo * pax };
        }
    }
    return { unitario: 0, total: 0 };
}

function actualizarSimulador() {
    const paxInput = document.getElementById('sim-pax');
    if (!paxInput) return;
    const pax = parseInt(paxInput.value, 10) || 1;
    const feriado = document.getElementById('sim-feriado')?.checked || false;
    let tipoPax = 'adulto';
    const tipoPaxInput = document.querySelector('input[name="sim-tipo-pax"]:checked');
    if (tipoPaxInput) tipoPax = tipoPaxInput.value;

    const badgeEl = document.getElementById('sim-pax-badge');
    if (badgeEl) badgeEl.textContent = `${pax} ${pax === 1 ? 'Pax' : 'Pax'}`;
    const detalleEl = document.getElementById('res-grupo-detalle');
    if (detalleEl) detalleEl.textContent = `Total para ${pax} ${pax === 1 ? 'pasajero' : 'pasajeros'}`;

    const { unitario, total } = calcularCostoLocal(pax, feriado, tipoPax);
    const comision = unitario * (SERVICIO_DATA.comision_pct / 100);
    const igv = SERVICIO_DATA.aplica_igv_comision ? (comision * 0.18) : 0;
    const ventaPax = unitario + comision + igv;
    const ventaGrupo = ventaPax * pax;

    const elUnitario = document.getElementById('res-costo-unitario');
    if (elUnitario) elUnitario.textContent = `$ ${unitario.toFixed(2)}`;

    const elFee = document.getElementById('res-fee');
    if (elFee) elFee.textContent = `$ ${(comision + igv).toFixed(2)}`;

    const elFeeDetalle = document.getElementById('res-fee-detalle');
    if (elFeeDetalle) elFeeDetalle.textContent = `Comisión $ ${comision.toFixed(2)} + IGV $ ${igv.toFixed(2)}`;

    const elVentaPax = document.getElementById('res-venta-pax');
    if (elVentaPax) elVentaPax.textContent = `$ ${ventaPax.toFixed(2)}`;

    const elVentaGrupo = document.getElementById('res-venta-grupo');
    if (elVentaGrupo) elVentaGrupo.textContent = `$ ${ventaGrupo.toFixed(2)}`;
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', actualizarSimulador);
} else {
    actualizarSimulador();
}
</script>
@endsection
