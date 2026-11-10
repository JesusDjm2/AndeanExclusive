// Tarifario: formulario de tarifas, sugerencia de costo al cotizar un hotel, en el itinerario y al asignar un proveedor.

const buscar = async (url, parametros) => {
    try {
        const respuesta = await fetch(`${url}?${new URLSearchParams(parametros)}`, { headers: { Accept: 'application/json' } });
        return respuesta.ok ? (await respuesta.json()).tarifas : [];
    } catch {
        return [];
    }
};

function initFormulario() {
    const form = document.querySelector('[data-tarifa-form]');
    if (!form) return;

    // Conmutador tipo: habitación vs proveedor
    const mostrarTipo = () => {
        const tipo = form.querySelector('[data-tarifa-tipo]:checked')?.value;
        form.querySelectorAll('[data-tarifa-panel]').forEach((panel) => {
            panel.hidden = panel.dataset.tarifaPanel !== tipo;
        });
    };
    form.querySelectorAll('[data-tarifa-tipo]').forEach((radio) => radio.addEventListener('change', mostrarTipo));

    // Conmutador vigencia: fija (todo el año) vs temporada vs fecha especial
    const toggleFija = form.querySelector('[data-tarifa-fija-toggle]');
    const vigenciaRadios = form.querySelectorAll('[data-vigencia-radio]');
    const fechasWrap = form.querySelectorAll('[data-tarifa-fechas]');
    const especialBox = form.querySelector('[data-tarifa-especial-box]');
    const inputEsFija = form.querySelector('#input-es-fija');
    const inputEsEspecial = form.querySelector('#input-es-especial');
    const inputNombreEspecial = form.querySelector('#t-nombre-especial');
    const inputDesde = form.querySelector('[data-tarifa-desde]');
    const inputHasta = form.querySelector('[data-tarifa-hasta]');

    const actualizarVigencia = () => {
        const checked = form.querySelector('[data-vigencia-radio]:checked')?.value || (toggleFija?.checked ? 'fija' : 'temporada');
        const esFija = checked === 'fija';
        const esEspecial = checked === 'especial';

        if (inputEsFija) inputEsFija.value = esFija ? '1' : '0';
        if (inputEsEspecial) inputEsEspecial.value = esEspecial ? '1' : '0';

        fechasWrap.forEach((el) => {
            el.style.display = esFija ? 'none' : '';
        });
        if (especialBox) {
            especialBox.style.display = esEspecial ? '' : 'none';
        }

        if (inputDesde && inputHasta) {
            inputDesde.required = !esFija;
            inputHasta.required = !esFija;
            if (esFija) {
                inputDesde.value = '';
                inputHasta.value = '';
            }
        }
        if (inputNombreEspecial) {
            inputNombreEspecial.required = esEspecial;
        }
    };

    if (vigenciaRadios.length) {
        vigenciaRadios.forEach((r) => r.addEventListener('change', actualizarVigencia));
        actualizarVigencia();
    } else if (toggleFija) {
        toggleFija.addEventListener('change', actualizarVigencia);
        actualizarVigencia();
    }
}

// Pestaña Alojamiento: propone el costo por noche, sin pisar lo que ya se escribió.
function initEstadias() {
    const seccion = document.querySelector('[data-estadias][data-tarifa-url]');
    if (!seccion) return;

    seccion.addEventListener('change', async (evento) => {
        if (!evento.target.matches('[data-estadia-habitacion], [data-estadia-ingreso]')) return;

        const fila = evento.target.closest('[data-estadia-item]');
        const costo = fila.querySelector('[data-estadia-costo]');
        const habitacion = fila.querySelector('[data-estadia-habitacion]').value;
        const fecha = fila.querySelector('[data-estadia-ingreso]').value;
        if (!costo || costo.value !== '' || !habitacion || !fecha) return;

        const [tarifa] = await buscar(seccion.dataset.tarifaUrl, { habitacion_id: habitacion, fecha });
        // Una tarifa de otra temporada no se propone sola: el precio de esa fecha puede ser otro.
        if (!tarifa || tarifa.fuera_de_temporada || costo.value !== '') return;

        costo.value = tarifa.precio;
        fila.querySelector('[data-estadia-moneda]').value = tarifa.moneda;
        costo.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

// Ventana de asignar proveedor (en servicios): muestra lo que cobra ese proveedor en la fecha del servicio.
function initAsignar() {
    const modal = document.getElementById('svcAsignarModal');
    const lista = modal?.querySelector('[data-svc-tarifas]');
    if (!lista) return;

    const proveedor = modal.querySelector('[data-svc-proveedor]');
    const costo = modal.querySelector('[data-svc-costo-real]');

    const cargar = async () => {
        lista.replaceChildren();
        lista.hidden = true;
        if (!proveedor.value) return;

        const parametros = { proveedor_id: proveedor.value };
        if (modal.dataset.fecha) {
            parametros.fecha = modal.dataset.fecha;
        }

        const tarifas = await buscar(modal.dataset.tarifaUrl || '/tarifas/sugerir', parametros);
        tarifas.forEach((tarifa) => {
            const boton = document.createElement('button');
            boton.type = 'button';
            boton.className = tarifa.fuera_de_temporada ? 'btn btn-sm btn-outline-warning' : 'btn btn-sm btn-outline-secondary';
            const etiquetaFija = tarifa.es_fija ? ' (fija)' : '';
            const etiquetaFuera = tarifa.fuera_de_temporada ? ` · fuera de temporada (${tarifa.temporada})` : '';
            boton.textContent = `${tarifa.concepto}: ${tarifa.simbolo} ${tarifa.precio.toFixed(2)} ${tarifa.por}${etiquetaFija}${etiquetaFuera}`;
            boton.addEventListener('click', () => {
                costo.value = tarifa.precio;
                costo.dispatchEvent(new Event('input', { bubbles: true }));
            });
            lista.append(boton);
        });
        lista.hidden = tarifas.length === 0;
    };

    proveedor.addEventListener('change', cargar);
    modal.addEventListener('shown.bs.modal', cargar);
}

// Aplica una tarifa del proveedor a la fila: nombre del servicio (va al PDF), precio, moneda y modo de cobro.
function aplicarTarifa(row, tarifa, automatica = false) {
    const disparar = (el) => {
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const tituloInput = row.querySelector('[data-svc-titulo]');
    if (tituloInput) {
        tituloInput.value = tarifa.concepto;
        disparar(tituloInput);
    }

    const estInput = row.querySelector('[data-svc-estimado]');
    if (estInput) {
        estInput.value = tarifa.precio;
        disparar(estInput);
    }

    // El costo real lo confirma Reservas; solo se propone si el campo está habilitado y vacío.
    const realInput = row.querySelector('[data-svc-costo]');
    if (realInput && !realInput.disabled && realInput.value === '') {
        realInput.value = tarifa.precio;
        disparar(realInput);
    }

    // La moneda queda fija si Reservas ya cargó el costo real (su botón está deshabilitado).
    const monedaInput = row.querySelector('[data-svc-moneda]');
    if (monedaInput && !row.querySelector('[data-svc-moneda-toggle]')?.disabled) {
        monedaInput.value = tarifa.moneda;
        monedaInput.dataset.monedaAuto = '0';
        disparar(monedaInput);
    }

    const porSelect = row.querySelector('[data-svc-por]');
    if (porSelect && !porSelect.disabled && tarifa.por_raw) {
        porSelect.value = tarifa.por_raw;
        disparar(porSelect);
    }

    // Recuerda lo que puso la tarifa para limpiarlo si luego se cambia de proveedor.
    row.dataset.tarifaPrecio = String(tarifa.precio);

    // Bajo el precio solo el origen y la vigencia: el nombre ya está en el selector de servicio.
    const origen = row.querySelector('[data-svc-origen]');
    if (origen) {
        const fuera = Boolean(tarifa.fuera_de_temporada);
        origen.hidden = false;
        origen.classList.toggle('is-warning', fuera);
        origen.title = `${tarifa.concepto}${tarifa.unidad_detalle ? ` (${tarifa.unidad_detalle})` : ''} · ${tarifa.temporada}`;
        const icono = document.createElement('i');
        if (tarifa.es_especial) {
            icono.className = 'fas fa-star text-warning me-1';
        } else {
            icono.className = `fas ${fuera ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-1`;
        }
        icono.setAttribute('aria-hidden', 'true');
        const vigencia = fuera
            ? 'otra temporada, confírmala'
            : (tarifa.es_especial ? `⭐ Fecha especial: ${tarifa.nombre_especial || 'Festividad'}` : (tarifa.es_fija ? 'tarifa fija' : `hasta ${tarifa.hasta}`));
        origen.replaceChildren(icono, `${automatica ? 'Del tarifario' : 'Tarifa aplicada'} · ${vigencia}`);
    }
}

// Elige sola la tarifa cuando no hay dudas: una sola vigente o una sola del mismo tipo de servicio.
function tarifaUnica(row, tarifas) {
    const vigentes = tarifas.filter((t) => !t.fuera_de_temporada);
    if (vigentes.length === 1) return vigentes[0];
    const tipo = row.querySelector('[data-svc-tipo]')?.value;
    const delTipo = vigentes.filter((t) => t.tipo_servicio && t.tipo_servicio === tipo);
    return delTipo.length === 1 ? delTipo[0] : null;
}

// ---- Selector de servicio: el tarifario del proveedor, o "Otro servicio" para escribirlo a mano ----
const OTRO_SERVICIO = '__otro';

function modoLibre(row, libre, enfocar = false) {
    const select = row.querySelector('[data-svc-servicio]');
    const libreBox = row.querySelector('[data-svc-titulo-libre]');
    const input = row.querySelector('[data-svc-titulo]');
    if (!select || !libreBox || !input) return;
    select.hidden = libre;
    libreBox.hidden = !libre;
    row.querySelector('[data-svc-servicio-label]')?.setAttribute('for', libre ? input.id : select.id);
    if (libre && enfocar) input.focus();
}

function llenarServicios(row, tarifas, proveedorId) {
    const select = row.querySelector('[data-svc-servicio]');
    if (!select) return;
    row.svcTarifas = tarifas;

    // Primero las tarifas del tipo de servicio de la fila.
    const tipo = row.querySelector('[data-svc-tipo]')?.value;
    const orden = tarifas.map((t, i) => i).sort((a, b) => Number(tarifas[b].tipo_servicio === tipo) - Number(tarifas[a].tipo_servicio === tipo));

    const aviso = !proveedorId ? 'Elige primero el proveedor' : (tarifas.length ? 'Elige el servicio…' : 'Sin tarifas: escribe el servicio');
    const opciones = [new Option(aviso, '')];
    orden.forEach((i) => {
        const t = tarifas[i];
        const detalle = t.unidad_detalle ? ` · ${t.unidad_detalle}` : '';
        const temporada = t.fuera_de_temporada ? ' (otra temporada)' : '';
        const prefijoEspecial = t.es_especial ? `⭐ [${t.nombre_especial || 'Especial'}] ` : '';
        opciones.push(new Option(`${prefijoEspecial}${t.concepto}${detalle} — ${t.simbolo} ${t.precio.toFixed(2)}${temporada}`, String(i)));
    });
    opciones.push(new Option('Otro servicio (escribir)…', OTRO_SERVICIO));
    select.replaceChildren(...opciones);

    const volver = row.querySelector('[data-svc-volver-lista]');
    if (volver) volver.hidden = tarifas.length === 0;
}

// Muestra en el selector el servicio guardado; si no es del tarifario, queda escrito a mano.
function mostrarActual(row) {
    const select = row.querySelector('[data-svc-servicio]');
    const titulo = row.querySelector('[data-svc-titulo]')?.value.trim() ?? '';
    if (!select) return;
    const indice = (row.svcTarifas ?? []).findIndex((t) => t.concepto === titulo);
    if (indice >= 0) {
        select.value = String(indice);
        modoLibre(row, false);
    } else {
        select.value = '';
        modoLibre(row, titulo !== '');
    }
}

// Al cambiar de proveedor se quita lo que había puesto la tarifa del anterior.
function limpiarTarifaAnterior(row) {
    if (row.dataset.tarifaPrecio === undefined) return;
    const precio = row.dataset.tarifaPrecio;
    ['[data-svc-estimado]', '[data-svc-costo]'].forEach((sel) => {
        const el = row.querySelector(sel);
        if (el && !el.disabled && el.value === precio) {
            el.value = '';
            el.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
    const titulo = row.querySelector('[data-svc-titulo]');
    if (titulo) titulo.value = '';
    const origen = row.querySelector('[data-svc-origen]');
    if (origen) origen.hidden = true;
    delete row.dataset.tarifaPrecio;
}

async function cargarTarifasFila(row, proveedorId, elegidoAhora = false) {
    if (!row.querySelector('[data-svc-servicio]')) return;
    if (!proveedorId) {
        llenarServicios(row, [], '');
        mostrarActual(row);
        return;
    }

    const fechaInput = row.querySelector('[data-svc-fecha]') || row.closest('[data-dia-item]')?.querySelector('[data-dia-fecha]');
    const fecha = fechaInput ? fechaInput.value : '';
    const tarifas = await buscar('/tarifario/sugerir', { proveedor_id: proveedorId, ...(fecha ? { fecha } : {}) });

    llenarServicios(row, tarifas, proveedorId);
    row.dataset.svcProveedorCargado = proveedorId;

    if (elegidoAhora) {
        // Proveedor recién elegido: si su tarifa es inequívoca se aplica sola; sin tarifas, se escribe el servicio.
        const estInput = row.querySelector('[data-svc-estimado]');
        const unica = estInput && estInput.value === '' ? tarifaUnica(row, tarifas) : null;
        if (unica) aplicarTarifa(row, unica, true);
        if (!tarifas.length) {
            modoLibre(row, true);
            return;
        }
    }
    mostrarActual(row);
}

function initItinerarioServicios() {
    document.addEventListener('change', (evento) => {
        const row = evento.target.closest('[data-svc-item]');
        if (!row || row.closest('template')) return;

        if (evento.target.matches('[data-svc-proveedor-select]')) {
            limpiarTarifaAnterior(row);
            cargarTarifasFila(row, evento.target.value, true);
            return;
        }

        if (evento.target.matches('[data-svc-servicio]')) {
            const valor = evento.target.value;
            if (valor === '__maestro') {
                abrirModalTarifarioMaestro(row);
                evento.target.value = '';
                return;
            }
            if (valor === OTRO_SERVICIO) {
                modoLibre(row, true, true);
            } else if (valor !== '' && row.svcTarifas?.[valor]) {
                aplicarTarifa(row, row.svcTarifas[valor]);
            }
        }
    });

    // Clics en el itinerario
    document.addEventListener('click', (evento) => {
        // Abrir modal Tarifario Maestro
        const btnMaestro = evento.target.closest('[data-svc-abrir-maestro]');
        if (btnMaestro) {
            const row = btnMaestro.closest('[data-svc-item]');
            if (row) abrirModalTarifarioMaestro(row);
            return;
        }

        // Volver del texto libre al tarifario del proveedor.
        const volver = evento.target.closest('[data-svc-volver-lista]');
        if (!volver) return;
        const row = volver.closest('[data-svc-item]');
        modoLibre(row, false);
        mostrarActual(row);
        row.querySelector('[data-svc-servicio]')?.focus();
    });

    // Filas ya guardadas: carga el tarifario de su proveedor una sola vez.
    const cargarFilasExistentes = () => {
        document.querySelectorAll('[data-svc-item]').forEach((row) => {
            if (row.closest('template')) return;
            const proveedor = row.querySelector('[data-svc-proveedor-select]')?.value ?? '';
            if (proveedor && row.dataset.svcProveedorCargado !== proveedor) cargarTarifasFila(row, proveedor);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', cargarFilasExistentes);
    } else {
        cargarFilasExistentes();
    }

    document.querySelectorAll('[data-bs-toggle="tab"], .nav-link').forEach((tab) => {
        tab.addEventListener('shown.bs.tab', cargarFilasExistentes);
    });
}

// ---- Integración con Modal de Tarifario Maestro Oficial 2027 ----
let filaObjetivoMaestro = null;
let categoriaMaestroActiva = '';

function abrirModalTarifarioMaestro(row) {
    filaObjetivoMaestro = row;
    const modalEl = document.getElementById('modalTarifarioMaestro');
    if (modalEl && window.bootstrap) {
        const bsModal = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
        bsModal.show();
    }
}

function aplicarServicioMaestro(item, pax, esFeriado) {
    if (!filaObjetivoMaestro) return;
    const row = filaObjetivoMaestro;

    const disparar = (el) => {
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    };

    // Nombre del servicio
    const tituloInput = row.querySelector('[data-svc-titulo]');
    if (tituloInput) {
        tituloInput.value = item.nombre;
        disparar(tituloInput);
    }

    // Código maestro
    const codigoInput = row.querySelector('[data-svc-tarifario-codigo]');
    if (codigoInput) {
        codigoInput.value = item.codigo;
        disparar(codigoInput);
    }

    // Costo estimado
    const estInput = row.querySelector('[data-svc-estimado]');
    if (estInput) {
        estInput.value = item.costo_pax.toFixed(2);
        disparar(estInput);
    }

    // Moneda
    const monedaInput = row.querySelector('[data-svc-moneda]');
    if (monedaInput) {
        monedaInput.value = item.moneda || 'USD';
        disparar(monedaInput);
    }

    // Costo por (persona vs grupo)
    const porSelect = row.querySelector('[data-svc-por]');
    if (porSelect) {
        porSelect.value = item.tipo_calculo === 'por_grupo' ? 'grupo' : 'persona';
        disparar(porSelect);
    }

    // Modo libre activo para que se vea el nombre
    modoLibre(row, true);

    // Origen visual
    const origen = row.querySelector('[data-svc-origen]');
    if (origen) {
        origen.hidden = false;
        origen.classList.remove('is-warning');
        const iconoFeriado = esFeriado ? ' · ⭐ Feriado' : '';
        origen.innerHTML = `<i class="fas fa-calculator text-primary me-1" aria-hidden="true"></i>Catálogo [${item.codigo}] · $${item.costo_pax.toFixed(2)} (${pax} pax${iconoFeriado})`;
    }

    // Cerrar modal
    const modalEl = document.getElementById('modalTarifarioMaestro');
    if (modalEl && window.bootstrap) {
        const bsModal = window.bootstrap.Modal.getInstance(modalEl);
        bsModal?.hide();
    }
}

function initModalTarifarioMaestro() {
    const modalEl = document.getElementById('modalTarifarioMaestro');
    if (!modalEl) return;

    const searchInput = modalEl.querySelector('[data-maestro-search]');
    const clearBtn = modalEl.querySelector('[data-maestro-clear-search]');
    const adtInput = modalEl.querySelector('[data-maestro-adt]');
    const chdInput = modalEl.querySelector('[data-maestro-chd]');
    const paxInput = modalEl.querySelector('[data-maestro-pax]');
    const totalBadge = modalEl.querySelector('#maestro-total-pax-badge');
    const feriadoSwitch = modalEl.querySelector('[data-maestro-feriado]');
    const catContainer = modalEl.querySelector('[data-maestro-categorias]');
    const listaResultados = modalEl.querySelector('[data-maestro-resultados]');
    const loadingEl = document.getElementById('maestro-loading');
    const emptyEl = document.getElementById('maestro-empty');

    const syncModalPax = () => {
        const adt = Math.max(1, parseInt(adtInput?.value, 10) || 1);
        const chd = Math.max(0, parseInt(chdInput?.value, 10) || 0);
        const total = adt + chd;
        if (paxInput) paxInput.value = total;
        if (totalBadge) totalBadge.textContent = `Total: ${total} Pax (${adt} Adt, ${chd} Chd)`;
        return { adt, chd, total };
    };

    const renderizarItems = async () => {
        if (!listaResultados) return;
        if (loadingEl) loadingEl.hidden = false;
        if (emptyEl) emptyEl.hidden = true;
        listaResultados.replaceChildren();

        const q = searchInput?.value.trim() || '';
        const { adt, chd, total } = syncModalPax();
        const feriado = feriadoSwitch?.checked ? 'true' : 'false';

        const params = { q, pax: total, adultos: adt, ninos: chd, feriado };
        if (categoriaMaestroActiva) params.categoria = categoriaMaestroActiva;

        let items = [];
        try {
            const res = await fetch(`/tarifario-maestro/buscar?${new URLSearchParams(params)}`, {
                headers: { Accept: 'application/json' }
            });
            if (res.ok) {
                const json = await res.json();
                items = json.items || [];
            }
        } catch {
            items = [];
        }

        if (loadingEl) loadingEl.hidden = true;

        if (!items.length) {
            if (emptyEl) emptyEl.hidden = false;
            return;
        }

        items.forEach((item) => {
            const div = document.createElement('div');
            div.className = 'list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3 gap-3';
            div.style.cursor = 'pointer';

            const badgeCatClass = {
                tour: 'bg-info-subtle text-info border border-info-subtle',
                traslado: 'bg-success-subtle text-success border border-success-subtle',
                trek: 'bg-danger-subtle text-danger border border-danger-subtle',
                boleto: 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                alimentacion: 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                viatico: 'bg-dark-subtle text-dark border',
            }[item.categoria] || 'bg-light text-dark border';

            let detalleTarifa = '';
            if (item.tipo_calculo === 'por_persona' && item.precio_nino) {
                if (chd > 0) {
                    detalleTarifa = `<span class="badge bg-warning-subtle text-dark border me-1">Adt: $${item.precio_adulto.toFixed(2)}</span><span class="badge bg-info-subtle text-info border">Chd: $${item.precio_nino.toFixed(2)}</span> · Tarifa ponderada`;
                } else {
                    detalleTarifa = `<span class="badge bg-light text-dark border">Adulto: $${item.precio_adulto.toFixed(2)}</span>`;
                }
            } else if (item.tipo_calculo === 'escala_pax') {
                detalleTarifa = `<i class="fas fa-chart-line text-primary me-1"></i>Escala para ${total} pax`;
            } else {
                detalleTarifa = `<i class="fas fa-users text-secondary me-1"></i>Tarifa grupal`;
            }

            div.innerHTML = `
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-dark font-monospace text-white px-2 py-1">${item.codigo}</span>
                        <span class="badge ${badgeCatClass} px-2 py-1 text-capitalize">${item.categoria}</span>
                        ${item.duracion_horas ? `<span class="badge bg-light text-muted border px-2 py-1"><i class="far fa-clock me-1"></i>${item.duracion_horas}h</span>` : ''}
                    </div>
                    <div class="fw-bold text-dark text-truncate" title="${item.nombre}">${item.nombre}</div>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                        ${detalleTarifa} · Comisión ${item.comision_pct}%
                    </small>
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="fs-5 fw-bold text-primary font-monospace">${item.moneda} ${item.costo_pax.toFixed(2)}</div>
                    <small class="text-muted d-block" style="font-size: 0.72rem;">costo neto unitario</small>
                    <button type="button" class="btn btn-primary btn-sm mt-1 px-3 py-1 fw-semibold shadow-sm">
                        <i class="fas fa-check me-1"></i> Aplicar
                    </button>
                </div>
            `;

            div.addEventListener('click', () => {
                aplicarServicioMaestro(item, total, feriado === 'true');
            });

            listaResultados.append(div);
        });
    };

    let debounceTimer = null;
    searchInput?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(renderizarItems, 250);
    });

    clearBtn?.addEventListener('click', () => {
        if (searchInput) {
            searchInput.value = '';
            renderizarItems();
        }
    });

    modalEl.querySelector('[data-maestro-adt-inc]')?.addEventListener('click', () => {
        if (adtInput) {
            adtInput.value = Math.min(20, (parseInt(adtInput.value, 10) || 1) + 1);
            renderizarItems();
        }
    });

    modalEl.querySelector('[data-maestro-adt-dec]')?.addEventListener('click', () => {
        if (adtInput) {
            adtInput.value = Math.max(1, (parseInt(adtInput.value, 10) || 1) - 1);
            renderizarItems();
        }
    });

    modalEl.querySelector('[data-maestro-chd-inc]')?.addEventListener('click', () => {
        if (chdInput) {
            chdInput.value = Math.min(20, (parseInt(chdInput.value, 10) || 0) + 1);
            renderizarItems();
        }
    });

    modalEl.querySelector('[data-maestro-chd-dec]')?.addEventListener('click', () => {
        if (chdInput) {
            chdInput.value = Math.max(0, (parseInt(chdInput.value, 10) || 0) - 1);
            renderizarItems();
        }
    });

    adtInput?.addEventListener('change', renderizarItems);
    chdInput?.addEventListener('change', renderizarItems);
    feriadoSwitch?.addEventListener('change', renderizarItems);

    catContainer?.querySelectorAll('[data-maestro-cat]').forEach((btn) => {
        btn.addEventListener('click', () => {
            categoriaMaestroActiva = btn.dataset.maestroCat;
            catContainer.querySelectorAll('[data-maestro-cat]').forEach((b) => {
                b.className = b === btn ? 'btn btn-xs btn-dark py-1 px-2 fw-semibold' : 'btn btn-xs btn-outline-secondary py-1 px-2';
            });
            renderizarItems();
        });
    });

    modalEl.addEventListener('show.bs.modal', () => {
        // Inicializar número de adultos y niños según el programa
        const progAdt = document.querySelector('input[name="cantidad_adultos"]')?.value;
        const progChd = document.querySelector('input[name="cantidad_ninos"]')?.value;
        const progPax = document.querySelector('input[name="cantidad_pax"]')?.value;

        if (adtInput) adtInput.value = progAdt ? Math.max(1, parseInt(progAdt, 10)) : (progPax ? Math.max(1, parseInt(progPax, 10)) : 2);
        if (chdInput) chdInput.value = progChd !== undefined && progChd !== null ? Math.max(0, parseInt(progChd, 10) || 0) : 0;
        syncModalPax();
        renderizarItems();
    });
}

export function initTarifas() {
    initFormulario();
    initEstadias();
    initAsignar();
    initItinerarioServicios();
    initModalTarifarioMaestro();
}
