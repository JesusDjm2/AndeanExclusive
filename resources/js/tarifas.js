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

// Aplica una tarifa del proveedor a la fila: precio cotizado, moneda, modo de cobro y nombre si estaba vacío.
function aplicarTarifa(row, tarifa, automatica = false) {
    const disparar = (el) => {
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const tituloInput = row.querySelector('[data-svc-titulo]');
    if (tituloInput && !tituloInput.value.trim()) {
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

    // Bajo el precio solo el origen y la vigencia: el nombre ya está en el campo del servicio.
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
    const tipo = row.querySelector('[id$="-tipo"]')?.value;
    const delTipo = vigentes.filter((t) => t.tipo_servicio && t.tipo_servicio === tipo);
    return delTipo.length === 1 ? delTipo[0] : null;
}

// Filas de itinerario en el formulario de programa: al elegir proveedor aplica su tarifa o muestra las opciones.
async function cargarTarifasFila(row, proveedorId, autoAplicar = false) {
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
        hint.innerHTML = '<i class="fas fa-info-circle me-1"></i> Sin tarifas en el tarifario';
        contenedor.appendChild(hint);
        return;
    }

    // Al elegir el proveedor, si su tarifa es inequívoca se aplica sola (sin pisar un precio ya escrito).
    const estInput = row.querySelector('[data-svc-estimado]');
    const unica = autoAplicar && estInput && estInput.value === '' ? tarifaUnica(row, tarifas) : null;
    if (unica) aplicarTarifa(row, unica, true);

    // Tarifario compacto: "Tarifario · N" abre la lista; la tarifa en uso queda marcada.
    const fuera = tarifas[0].fuera_de_temporada;
    const monedaFila = row.querySelector('[data-svc-moneda]')?.value;
    const enUso = unica ?? tarifas.find((t) => estInput?.value !== '' && Number(estInput?.value) === t.precio && t.moneda === monedaFila);

    const box = document.createElement('div');
    box.className = 'dropdown svc-tarifario';

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = `svc-tarifario__toggle${fuera ? ' is-warning' : ''}${enUso ? ' is-applied' : ''}`;
    toggle.dataset.bsToggle = 'dropdown';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.title = fuera ? 'Ninguna tarifa cubre esta fecha: son de otra temporada' : 'Ver y aplicar las tarifas de este proveedor';
    toggle.innerHTML = `<i class="fas ${fuera ? 'fa-exclamation-triangle' : 'fa-tags'}" aria-hidden="true"></i> Tarifario <span class="svc-tarifario__count">${tarifas.length}</span><i class="fas fa-chevron-down svc-tarifario__caret" aria-hidden="true"></i>`;

    const menu = document.createElement('ul');
    menu.className = 'dropdown-menu svc-tarifario__menu';
    if (fuera) {
        const aviso = document.createElement('li');
        aviso.className = 'svc-tarifario__aviso';
        aviso.textContent = 'Ninguna tarifa cubre esta fecha: son de otra temporada, confirma el precio con el proveedor.';
        menu.append(aviso);
    }

    const marcar = (item, activo) => {
        item.classList.toggle('active', activo);
        item.setAttribute('aria-current', activo ? 'true' : 'false');
    };

    tarifas.forEach((tarifa) => {
        const li = document.createElement('li');
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'dropdown-item svc-tarifario__item';

        const texto = document.createElement('span');
        texto.className = 'svc-tarifario__texto';
        const concepto = document.createElement('span');
        concepto.className = 'svc-tarifario__concepto';
        concepto.textContent = tarifa.concepto;
        const meta = document.createElement('span');
        meta.className = 'svc-tarifario__meta';
        meta.textContent = [tarifa.unidad_detalle, tarifa.por, tarifa.es_fija ? 'fija' : tarifa.temporada].filter(Boolean).join(' · ');
        texto.append(concepto, meta);

        const precio = document.createElement('span');
        precio.className = 'svc-tarifario__precio';
        precio.textContent = `${tarifa.simbolo} ${tarifa.precio.toFixed(2)}`;

        item.append(texto, precio);
        marcar(item, tarifa === enUso);
        item.addEventListener('click', () => {
            aplicarTarifa(row, tarifa);
            menu.querySelectorAll('.svc-tarifario__item').forEach((i) => marcar(i, i === item));
            toggle.classList.add('is-applied');
        });

        li.append(item);
        menu.append(li);
    });

    box.append(toggle, menu);
    contenedor.appendChild(box);
}

function initItinerarioServicios() {
    // 1. Escuchar cambios de proveedor en cualquier fila (existente o agregada dinámicamente)
    document.addEventListener('change', (evento) => {
        const selectProv = evento.target.closest('[id$="-prov"], [data-svc-proveedor-select]');
        if (!selectProv) return;

        const row = selectProv.closest('[data-svc-item]');
        if (!row) return;

        cargarTarifasFila(row, selectProv.value, true);
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
