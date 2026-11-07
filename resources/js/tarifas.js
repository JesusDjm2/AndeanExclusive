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

// Filas de itinerario en el formulario de programa: muestra botones de tarifas al elegir proveedor
async function cargarTarifasFila(row, proveedorId) {
    let contenedor = row.querySelector('[data-svc-quick-rates]');
    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.className = 'svc-quick-rates mt-1';
        contenedor.dataset.svcQuickRates = 'true';
        const selectProv = row.querySelector('[id$="-prov"], [data-svc-proveedor-select]');
        if (selectProv) selectProv.parentNode.appendChild(contenedor);
    }

    contenedor.replaceChildren();
    if (!proveedorId) return;

    const fechaInput = row.querySelector('[data-svc-fecha]') || row.closest('[data-dia-item]')?.querySelector('[data-dia-fecha]');
    const fecha = fechaInput ? fechaInput.value : '';

    const tarifas = await buscar('/tarifario/sugerir', {
        proveedor_id: proveedorId,
        ...(fecha ? { fecha } : {}),
    });

    if (tarifas.length === 0) {
        const hint = document.createElement('small');
        hint.className = 'text-muted d-block mt-1';
        hint.style.fontSize = '0.74rem';
        hint.innerHTML = '<i class="fas fa-info-circle me-1"></i> Este proveedor no tiene tarifas fijas registradas aún. Escribe el precio directamente a la derecha.';
        contenedor.appendChild(hint);
        return;
    }

    const box = document.createElement('div');
    box.className = 'd-flex flex-wrap gap-1 align-items-center mt-1';

    const label = document.createElement('span');
    label.className = 'badge bg-gold-subtle text-gold-text border small';
    label.style.fontSize = '0.72rem';
    label.innerHTML = '<i class="fas fa-tags me-1"></i>Tarifas de este proveedor (haz clic para aplicar):';
    box.appendChild(label);

    if (tarifas[0].fuera_de_temporada) {
        const aviso = document.createElement('small');
        aviso.className = 'text-warning-emphasis d-block w-100';
        aviso.style.fontSize = '0.72rem';
        aviso.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Ninguna tarifa cubre esta fecha: son de otra temporada, confirma el precio con el proveedor.';
        box.appendChild(aviso);
    }

    const claseBoton = tarifas[0].fuera_de_temporada ? 'btn-outline-warning' : 'btn-outline-primary';

    tarifas.forEach((tarifa) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `btn btn-xs ${claseBoton} text-truncate py-0 px-2 fw-medium`;
        btn.style.fontSize = '0.75rem';
        btn.title = `Aplicar tarifa: ${tarifa.concepto} (${tarifa.simbolo} ${tarifa.precio.toFixed(2)} ${tarifa.por}) · ${tarifa.temporada}`;
        btn.textContent = `${tarifa.concepto}: ${tarifa.simbolo}${tarifa.precio.toFixed(2)} (${tarifa.por_raw || tarifa.por})`;

        btn.addEventListener('click', () => {
            // Autocompletar concepto si está vacío o sugerido
            const tituloInput = row.querySelector('[data-svc-titulo]');
            if (tituloInput && (!tituloInput.value || tituloInput.value.trim() === '')) {
                tituloInput.value = tarifa.concepto;
                tituloInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // Costo estimado
            const estInput = row.querySelector('[id$="-estimado"]');
            if (estInput) {
                estInput.value = tarifa.precio;
                estInput.dispatchEvent(new Event('input', { bubbles: true }));
                estInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Costo real (si no está bloqueado)
            const realInput = row.querySelector('[id$="-costo"]');
            if (realInput && !realInput.disabled) {
                realInput.value = tarifa.precio;
                realInput.dispatchEvent(new Event('input', { bubbles: true }));
                realInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Moneda
            const monedaSelect = row.querySelector('[id$="-moneda"]');
            if (monedaSelect && !monedaSelect.disabled) {
                monedaSelect.value = tarifa.moneda;
                monedaSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Por grupo / persona
            const porSelect = row.querySelector('[id$="-por"]');
            if (porSelect && !porSelect.disabled && tarifa.por_raw) {
                porSelect.value = tarifa.por_raw;
                porSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Abrir detalles si estaba cerrado para que el usuario vea el costo asignado
            const details = row.querySelector('details');
            if (details) details.open = true;

            // Feedback visual breve
            btn.className = 'btn btn-xs btn-success text-truncate py-0 px-2 fw-bold';
            setTimeout(() => {
                btn.className = `btn btn-xs ${claseBoton} text-truncate py-0 px-2 fw-medium`;
            }, 1200);
        });

        box.appendChild(btn);
    });

    contenedor.appendChild(box);
}

function initItinerarioServicios() {
    // 1. Escuchar cambios de proveedor en cualquier fila (existente o agregada dinámicamente)
    document.addEventListener('change', (evento) => {
        const selectProv = evento.target.closest('[id$="-prov"], [data-svc-proveedor-select]');
        if (!selectProv) return;

        const row = selectProv.closest('[data-svc-item]');
        if (!row) return;

        cargarTarifasFila(row, selectProv.value);
    });

    // 2. Cargar tarifas de inicio para las filas que ya tengan proveedor seleccionado
    const cargarFilasExistentes = () => {
        document.querySelectorAll('[data-svc-item]').forEach((row) => {
            if (row.closest('template')) return;
            const selectProv = row.querySelector('[id$="-prov"], [data-svc-proveedor-select]');
            if (selectProv && selectProv.value) {
                cargarTarifasFila(row, selectProv.value);
            }
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
