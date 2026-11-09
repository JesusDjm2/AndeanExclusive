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

    // Conmutador tarifa fija (todo el año) vs temporada (desde/hasta)
    const toggleFija = form.querySelector('[data-tarifa-fija-toggle]');
    const fechasWrap = form.querySelectorAll('[data-tarifa-fechas]');
    const inputDesde = form.querySelector('[data-tarifa-desde]');
    const inputHasta = form.querySelector('[data-tarifa-hasta]');

    const actualizarFechas = () => {
        const esFija = toggleFija ? toggleFija.checked : false;
        fechasWrap.forEach((el) => {
            el.style.display = esFija ? 'none' : '';
        });
        if (inputDesde && inputHasta) {
            inputDesde.required = !esFija;
            inputHasta.required = !esFija;
            if (esFija) {
                inputDesde.value = '';
                inputHasta.value = '';
            }
        }
    };

    if (toggleFija) {
        toggleFija.addEventListener('change', actualizarFechas);
        actualizarFechas();
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
        icono.className = `fas ${fuera ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-1`;
        icono.setAttribute('aria-hidden', 'true');
        const vigencia = fuera ? 'otra temporada, confírmala' : (tarifa.es_fija ? 'tarifa fija' : `hasta ${tarifa.hasta}`);
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
        opciones.push(new Option(`${t.concepto}${detalle} — ${t.simbolo} ${t.precio.toFixed(2)}${temporada}`, String(i)));
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
            if (valor === OTRO_SERVICIO) {
                modoLibre(row, true, true);
            } else if (valor !== '' && row.svcTarifas?.[valor]) {
                aplicarTarifa(row, row.svcTarifas[valor]);
            }
        }
    });

    // Volver del texto libre al tarifario del proveedor.
    document.addEventListener('click', (evento) => {
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

export function initTarifas() {
    initFormulario();
    initEstadias();
    initAsignar();
    initItinerarioServicios();
}
