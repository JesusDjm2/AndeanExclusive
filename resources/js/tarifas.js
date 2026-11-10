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

    const aviso = !proveedorId ? 'Elige o busca en el catálogo…' : (tarifas.length ? 'Elige el servicio…' : 'Sin tarifas del proveedor');
    const opciones = [new Option(aviso, '')];
    opciones.push(new Option('⚡ Del Tarifario Maestro 2027…', '__maestro'));
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
    const provId = row.querySelector('[data-svc-proveedor-select]')?.value;
    if (!select) return;
    const indice = (row.svcTarifas ?? []).findIndex((t) => t.concepto === titulo);
    if (indice >= 0 && provId) {
        select.value = String(indice);
        modoLibre(row, false);
    } else {
        select.value = '';
        modoLibre(row, true);
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

    // Enter en el campo de texto de servicio abre la búsqueda del catálogo
    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Enter' && evento.target.matches('[data-svc-titulo]')) {
            evento.preventDefault();
            const row = evento.target.closest('[data-svc-item]');
            if (row) abrirModalTarifarioMaestro(row);
        }
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
// Paso 1: elegir un servicio del catálogo. Paso 2: revisar y editar lo que incluye (guía, transporte, entradas,
// almuerzo…); cada parte se agrega como una fila del día con su tipo y sin proveedor, para asignarlo al confirmar.
// Lo editado en el paso 2 solo va a este programa: el catálogo no cambia.
let filaObjetivoMaestro = null;
let categoriaMaestroActiva = '';
let desgloseMaestro = null;
let servicioMaestro = null;

const SIMBOLO_MONEDA = { USD: 'US$', PEN: 'S/' };
const dosDecimales = (n) => (Math.round((Number(n) || 0) * 100) / 100).toFixed(2);

function mostrarPasoMaestro(modalEl, paso) {
    modalEl.dataset.paso = paso;
    modalEl.querySelectorAll('[data-maestro-paso]').forEach((el) => { el.hidden = el.dataset.maestroPaso !== paso; });
    const confirmar = modalEl.querySelector('[data-maestro-confirmar]');
    if (confirmar) confirmar.hidden = paso !== 'desglose';
    const pie = modalEl.querySelector('[data-maestro-pie-texto]');
    if (pie) {
        pie.lastChild.textContent = paso === 'desglose'
            ? ' Cada parte marcada se agrega como un servicio del día, sin proveedor asignado.'
            : ' Elige un servicio para revisar lo que incluye antes de agregarlo.';
    }
}

function abrirModalTarifarioMaestro(row) {
    filaObjetivoMaestro = row;
    const modalEl = document.getElementById('modalTarifarioMaestro');
    if (modalEl && window.bootstrap) {
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        const tituloActual = row.querySelector('[data-svc-titulo]')?.value.trim() ?? '';
        const searchInput = modalEl.querySelector('[data-maestro-search]');
        if (searchInput) {
            if (tituloActual && !tituloActual.startsWith('⚡')) {
                searchInput.value = tituloActual;
            } else {
                searchInput.value = '';
            }
        }

        // Resetear a "Todos" para mostrar todo el catálogo
        categoriaMaestroActiva = '';
        modalEl.querySelectorAll('[data-maestro-cat]').forEach((b) => {
            b.className = b.dataset.maestroCat === '' ? 'btn btn-xs btn-dark py-1 px-2 fw-semibold' : 'btn btn-xs btn-outline-secondary py-1 px-2';
        });
        mostrarPasoMaestro(modalEl, 'catalogo');

        const bsModal = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
        bsModal.show();
    }
}

// Inserta una fila nueva del itinerario justo debajo de `ref` (la crea programa-form.js, que lleva los índices).
function crearFilaDespues(ref) {
    const evento = new CustomEvent('svc:crear-despues', { bubbles: true, detail: {} });
    ref.dispatchEvent(evento);
    return evento.detail.fila ?? null;
}

function rellenarFilaConParte(row, parte, ctx) {
    const disparar = (el) => {
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    };
    const poner = (selector, valor) => {
        const el = row.querySelector(selector);
        if (el) {
            el.value = valor;
            disparar(el);
        }
        return el;
    };

    // Si estaba marcado como reservado por el cliente, reactivar modo cotización
    if (row.classList.contains('is-cliente-pax')) {
        row.classList.remove('is-cliente-pax');
        const btnCli = row.querySelector('[data-svc-toggle-cliente-pax]');
        if (btnCli) {
            btnCli.classList.remove('btn-info', 'text-white', 'is-active');
            btnCli.classList.add('btn-outline-info');
            btnCli.title = 'Marcar si este servicio fue contratado/reservado directamente por el cliente';
        }
    }

    poner('[data-svc-titulo]', parte.nombre);
    poner('[data-svc-tarifario-codigo]', (parte.codigo || ctx.codigo).slice(0, 30));
    poner('[data-svc-tipo]', parte.tipo);
    poner('[data-svc-estimado]', parte.costo === null ? '' : dosDecimales(parte.costo));
    const moneda = poner('[data-svc-moneda]', ctx.moneda);
    if (moneda) moneda.dataset.monedaAuto = '0';

    // Los costos del desglose son siempre por pasajero (los grupales ya vienen prorrateados).
    poner('[data-svc-por]', 'persona');
    const porSelect = row.querySelector('select[id$="-por-sel"]');
    if (porSelect) porSelect.value = 'persona';

    const visible = row.querySelector('input[type="checkbox"][name$="[visible_cliente]"]');
    if (visible) visible.checked = parte.visible_cliente;

    const descripcion = row.querySelector('textarea[name$="[descripcion]"]');
    if (descripcion && parte.descripcion && !descripcion.value.trim()) descripcion.value = parte.descripcion;

    const notas = row.querySelector('textarea[name$="[notas]"]');
    if (notas && ctx.varias) {
        const linea = `Parte de ${ctx.codigo} · ${ctx.nombre}${parte.nota ? ` — ${parte.nota}` : ''}`;
        if (!notas.value.includes(linea)) notas.value = notas.value.trim() ? `${notas.value.trim()}\n${linea}` : linea;
    }

    modoLibre(row, true);

    const origen = row.querySelector('[data-svc-origen]');
    if (origen) {
        origen.hidden = false;
        origen.classList.toggle('is-warning', parte.costo === null);
        const icono = document.createElement('i');
        icono.className = 'fas fa-calculator text-primary me-1';
        icono.setAttribute('aria-hidden', 'true');
        const codigoParte = parte.codigo && parte.codigo !== ctx.codigo ? ` · ${parte.codigo}` : '';
        const estado = parte.costo === null ? 'sin precio' : (parte.incluido ? 'incluido' : 'adicional');
        origen.replaceChildren(icono, `Catálogo [${ctx.codigo}]${codigoParte} · ${estado} · ${ctx.pax} pax${ctx.feriado ? ' · ⭐ Feriado' : ''}`);
    }
}

function textoOrigenParte(parte) {
    if (parte.origen === 'catalogo') {
        return parte.incluido
            ? `Precio del catálogo${parte.codigo ? ` (${parte.codigo})` : ''} · incluido en la tarifa`
            : `Adicional · precio del catálogo${parte.codigo ? ` (${parte.codigo})` : ''}, se suma a la tarifa`;
    }
    if (parte.origen === 'reparto') return 'Parte de la tarifa · reparto estimado entre transporte y guía, ajústalo si lo sabes';
    if (parte.origen === 'manual') return 'Agregada a mano · se suma a la tarifa';
    return parte.nota || 'Sin precio en el catálogo: complétalo si tiene costo';
}

function marcarClienteParte(el, visible) {
    const boton = el.querySelector('[data-parte-cliente]');
    if (!boton) return;
    boton.setAttribute('aria-pressed', visible ? 'true' : 'false');
    boton.classList.toggle('is-active', visible);
    boton.title = visible ? 'Visible para el cliente en el itinerario (clic para ocultar)' : 'Oculto al cliente: solo uso interno (clic para mostrar)';
    boton.setAttribute('aria-label', boton.title);
    const icono = boton.querySelector('i');
    if (icono) icono.className = visible ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function crearParteMaestro(modalEl, parte, moneda) {
    const plantilla = modalEl.querySelector('[data-maestro-parte-template]');
    const el = plantilla.content.firstElementChild.cloneNode(true);
    const tipo = el.querySelector('[data-parte-tipo]');
    tipo.value = [...tipo.options].some((o) => o.value === parte.tipo) ? parte.tipo : 'otro';
    el.querySelector('[data-parte-nombre]').value = parte.nombre || '';
    el.querySelector('[data-parte-costo]').value = parte.costo === null || parte.costo === undefined ? '' : dosDecimales(parte.costo);
    el.querySelector('[data-parte-moneda]').textContent = SIMBOLO_MONEDA[moneda] ?? moneda;
    el.dataset.codigo = parte.codigo || '';
    el.dataset.origen = parte.origen || 'manual';
    el.dataset.incluido = parte.incluido ? '1' : '0';
    el.dataset.descripcion = parte.descripcion || '';
    el.dataset.nota = parte.nota || '';
    el.classList.toggle('is-adicional', !parte.incluido);
    el.querySelector('[data-parte-origen]').textContent = textoOrigenParte(parte);
    marcarClienteParte(el, !!parte.visible_cliente);
    return el;
}

function leerPartesMaestro(modalEl) {
    return [...modalEl.querySelectorAll('[data-maestro-partes] [data-parte]')].map((el) => {
        const costoTxt = el.querySelector('[data-parte-costo]').value.trim();
        return {
            usar: el.querySelector('[data-parte-usar]').checked,
            tipo: el.querySelector('[data-parte-tipo]').value,
            nombre: el.querySelector('[data-parte-nombre]').value.trim(),
            costo: costoTxt === '' ? null : Math.max(0, parseFloat(costoTxt) || 0),
            codigo: el.dataset.codigo || null,
            incluido: el.dataset.incluido === '1',
            visible_cliente: el.querySelector('[data-parte-cliente]').getAttribute('aria-pressed') === 'true',
            descripcion: el.dataset.descripcion || null,
            nota: el.dataset.nota || null,
        };
    });
}

function actualizarResumenMaestro(modalEl) {
    const cont = modalEl.querySelector('[data-maestro-resumen]');
    const confirmarTexto = modalEl.querySelector('[data-maestro-confirmar-texto]');
    const confirmar = modalEl.querySelector('[data-maestro-confirmar]');
    if (!cont || !desgloseMaestro) return;

    const simbolo = SIMBOLO_MONEDA[desgloseMaestro.moneda] ?? desgloseMaestro.moneda;
    const dinero = (n) => `${simbolo} ${dosDecimales(n)}`;

    const partes = leerPartesMaestro(modalEl).filter((p) => p.usar);
    const tarifa = Number(desgloseMaestro.total) || 0;
    const incluidas = partes.filter((p) => p.incluido).reduce((s, p) => s + (p.costo ?? 0), 0);
    const adicionales = partes.filter((p) => !p.incluido).reduce((s, p) => s + (p.costo ?? 0), 0);
    const totalPax = Math.round((incluidas + adicionales) * 100) / 100;
    const pax = (desgloseMaestro.adultos || 0) + (desgloseMaestro.ninos || 0);
    const diferencia = Math.round((incluidas - tarifa) * 100) / 100;
    const sinPrecio = partes.filter((p) => p.costo === null).length;

    const fila = (etiqueta, valor, clase = '') => {
        const div = document.createElement('div');
        div.className = `tm-desglose__fila ${clase}`;
        const a = document.createElement('span');
        a.textContent = etiqueta;
        const b = document.createElement('strong');
        b.textContent = valor;
        div.append(a, b);
        return div;
    };

    const nodos = [fila('Tarifa base del catálogo (por pasajero)', dinero(tarifa))];
    if (adicionales > 0) {
        nodos.push(fila('Partes incluidas', dinero(incluidas)));
        nodos.push(fila('Adicionales', `+ ${dinero(adicionales)}`));
    }
    if (Math.abs(diferencia) >= 0.01) {
        const aviso = document.createElement('div');
        aviso.className = 'tm-desglose__aviso';
        aviso.textContent = diferencia > 0
            ? `Las partes incluidas suman ${dinero(diferencia)} más que la tarifa.`
            : `Faltan ${dinero(-diferencia)} por repartir para llegar a la tarifa.`;
        nodos.push(aviso);
    }
    nodos.push(fila('Costo neto por pasajero', dinero(totalPax), 'is-total'));
    if (pax > 1) nodos.push(fila(`Costo neto total (${pax} pasajeros)`, dinero(totalPax * pax), 'is-sub'));
    if (sinPrecio) {
        const nota = document.createElement('div');
        nota.className = 'tm-desglose__nota';
        nota.textContent = `${sinPrecio} ${sinPrecio === 1 ? 'parte queda' : 'partes quedan'} sin precio: se agregan igual y se cotizan después.`;
        nodos.push(nota);
    }
    cont.replaceChildren(...nodos);

    const n = partes.filter((p) => p.nombre).length;
    if (confirmarTexto) confirmarTexto.textContent = n === 1 ? 'Agregar 1 servicio al día' : `Agregar ${n} servicios al día`;
    if (confirmar) confirmar.disabled = n === 0;
}

function aplicarDesgloseMaestro(modalEl) {
    if (!filaObjetivoMaestro || !desgloseMaestro) return;
    const partes = leerPartesMaestro(modalEl).filter((p) => p.usar && p.nombre);
    if (!partes.length) return;

    const ctx = {
        codigo: desgloseMaestro.codigo,
        nombre: desgloseMaestro.nombre,
        moneda: desgloseMaestro.moneda || 'USD',
        pax: (desgloseMaestro.adultos || 0) + (desgloseMaestro.ninos || 0),
        feriado: !!desgloseMaestro.feriado,
        varias: partes.length > 1,
    };
    const hora = filaObjetivoMaestro.querySelector('input[name$="[hora]"]')?.value ?? '';

    let anterior = filaObjetivoMaestro;
    partes.forEach((parte, i) => {
        const fila = i === 0 ? anterior : crearFilaDespues(anterior);
        if (!fila) return;
        if (i > 0 && hora) {
            const campoHora = fila.querySelector('input[name$="[hora]"]');
            if (campoHora) campoHora.value = hora;
        }
        rellenarFilaConParte(fila, parte, ctx);
        anterior = fila;
    });

    window.bootstrap?.Modal.getInstance(modalEl)?.hide();
}

function initModalTarifarioMaestro() {
    const modalEl = document.getElementById('modalTarifarioMaestro');
    if (!modalEl) return;

    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }

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
    const partesEl = modalEl.querySelector('[data-maestro-partes]');
    const partesLoading = modalEl.querySelector('[data-maestro-partes-loading]');

    const syncModalPax = () => {
        const adt = Math.max(1, parseInt(adtInput?.value, 10) || 1);
        const chd = Math.max(0, parseInt(chdInput?.value, 10) || 0);
        const total = adt + chd;
        if (paxInput) paxInput.value = total;
        if (totalBadge) totalBadge.textContent = `Total: ${total} Pax (${adt} Adt, ${chd} Chd)`;
        return { adt, chd, total };
    };

    // Paso 2: pide al servidor las partes propuestas para el servicio y los pasajeros actuales.
    const cargarDesglose = async () => {
        if (!servicioMaestro || !partesEl) return;
        const { adt, chd, total } = syncModalPax();
        const feriado = !!feriadoSwitch?.checked;

        modalEl.querySelector('[data-maestro-desglose-codigo]').textContent = servicioMaestro.codigo;
        modalEl.querySelector('[data-maestro-desglose-nombre]').textContent = servicioMaestro.nombre;
        modalEl.querySelector('[data-maestro-desglose-pax]').textContent =
            `${adt} adulto${adt === 1 ? '' : 's'} · ${chd} niño${chd === 1 ? '' : 's'} · ${total} pax${feriado ? ' · Feriado' : ''}`;

        partesEl.replaceChildren();
        modalEl.querySelector('[data-maestro-resumen]')?.replaceChildren();
        modalEl.querySelector('[data-maestro-aviso-adicionales]').hidden = true;
        if (partesLoading) partesLoading.hidden = false;
        const confirmar = modalEl.querySelector('[data-maestro-confirmar]');
        if (confirmar) confirmar.disabled = true;

        try {
            const params = new URLSearchParams({ adultos: adt, ninos: chd, feriado: feriado ? 'true' : 'false' });
            const res = await fetch(`/tarifario-maestro/${servicioMaestro.id}/componentes?${params}`, { headers: { Accept: 'application/json' } });
            if (!res.ok) throw new Error(String(res.status));
            desgloseMaestro = await res.json();
        } catch {
            desgloseMaestro = null;
            if (partesLoading) partesLoading.hidden = true;
            const error = document.createElement('div');
            error.className = 'alert alert-danger py-2 px-3 small mb-0';
            error.textContent = 'No se pudo preparar lo que incluye este servicio. Vuelve al catálogo e inténtalo de nuevo.';
            partesEl.replaceChildren(error);
            return;
        }

        if (partesLoading) partesLoading.hidden = true;
        partesEl.replaceChildren(...desgloseMaestro.partes.map((p) => crearParteMaestro(modalEl, p, desgloseMaestro.moneda)));
        modalEl.querySelector('[data-maestro-aviso-adicionales]').hidden = !desgloseMaestro.adicionales;
        actualizarResumenMaestro(modalEl);
    };

    const abrirDesglose = (item) => {
        servicioMaestro = item;
        mostrarPasoMaestro(modalEl, 'desglose');
        cargarDesglose();
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
                propina: 'bg-success-subtle text-success border border-success-subtle',
                componente: 'bg-primary-subtle text-primary border border-primary-subtle',
                otro: 'bg-light text-dark border',
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
                        <span class="badge ${badgeCatClass} px-2 py-1 text-capitalize">${item.categoria_label || item.categoria}</span>
                        ${item.duracion_horas ? `<span class="badge bg-light text-muted border px-2 py-1"><i class="far fa-clock me-1"></i>${item.duracion_horas}h</span>` : ''}
                    </div>
                    <div class="fw-bold text-dark tm-modal__item-titulo" title="${item.nombre}">${item.nombre}</div>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                        ${detalleTarifa}
                    </small>
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="fs-5 fw-bold text-primary font-monospace">${item.moneda} ${item.costo_pax.toFixed(2)}</div>
                    <small class="text-muted d-block" style="font-size: 0.72rem;">costo neto unitario</small>
                    <button type="button" class="btn btn-primary btn-sm mt-1 px-3 py-1 fw-semibold shadow-sm">
                        <i class="fas fa-tasks me-1"></i> Ver qué incluye
                    </button>
                </div>
            `;

            div.addEventListener('click', () => abrirDesglose(item));

            listaResultados.append(div);
        });
    };

    // Los cambios de pasajeros o feriado recalculan el paso que esté abierto.
    const refrescar = () => (modalEl.dataset.paso === 'desglose' ? cargarDesglose() : renderizarItems());

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

    const ajustar = (input, delta, min) => {
        if (!input) return;
        input.value = Math.min(20, Math.max(min, (parseInt(input.value, 10) || min) + delta));
        refrescar();
    };
    modalEl.querySelector('[data-maestro-adt-inc]')?.addEventListener('click', () => ajustar(adtInput, 1, 1));
    modalEl.querySelector('[data-maestro-adt-dec]')?.addEventListener('click', () => ajustar(adtInput, -1, 1));
    modalEl.querySelector('[data-maestro-chd-inc]')?.addEventListener('click', () => ajustar(chdInput, 1, 0));
    modalEl.querySelector('[data-maestro-chd-dec]')?.addEventListener('click', () => ajustar(chdInput, -1, 0));

    adtInput?.addEventListener('change', refrescar);
    chdInput?.addEventListener('change', refrescar);
    feriadoSwitch?.addEventListener('change', refrescar);

    catContainer?.querySelectorAll('[data-maestro-cat]').forEach((btn) => {
        btn.addEventListener('click', () => {
            categoriaMaestroActiva = btn.dataset.maestroCat;
            catContainer.querySelectorAll('[data-maestro-cat]').forEach((b) => {
                b.className = b === btn ? 'btn btn-xs btn-dark py-1 px-2 fw-semibold' : 'btn btn-xs btn-outline-secondary py-1 px-2';
            });
            renderizarItems();
        });
    });

    // Paso 2: edición de las partes
    modalEl.querySelector('[data-maestro-volver]')?.addEventListener('click', () => {
        mostrarPasoMaestro(modalEl, 'catalogo');
        renderizarItems();
    });

    modalEl.querySelector('[data-maestro-parte-agregar]')?.addEventListener('click', () => {
        if (!partesEl || !desgloseMaestro) return;
        const nueva = crearParteMaestro(modalEl, {
            tipo: 'otro', nombre: '', costo: null, origen: 'manual', incluido: false, visible_cliente: false,
        }, desgloseMaestro.moneda);
        partesEl.append(nueva);
        nueva.querySelector('[data-parte-nombre]')?.focus();
        actualizarResumenMaestro(modalEl);
    });

    partesEl?.addEventListener('input', () => actualizarResumenMaestro(modalEl));
    partesEl?.addEventListener('change', (evento) => {
        const usar = evento.target.closest('[data-parte-usar]');
        if (usar) usar.closest('[data-parte]')?.classList.toggle('is-off', !usar.checked);
        actualizarResumenMaestro(modalEl);
    });
    partesEl?.addEventListener('click', (evento) => {
        const cliente = evento.target.closest('[data-parte-cliente]');
        if (cliente) {
            const parte = cliente.closest('[data-parte]');
            marcarClienteParte(parte, cliente.getAttribute('aria-pressed') !== 'true');
            return;
        }
        const quitar = evento.target.closest('[data-parte-quitar]');
        if (quitar) {
            quitar.closest('[data-parte]')?.remove();
            actualizarResumenMaestro(modalEl);
        }
    });

    modalEl.querySelector('[data-maestro-confirmar]')?.addEventListener('click', () => aplicarDesgloseMaestro(modalEl));

    modalEl.addEventListener('show.bs.modal', () => {
        // Inicializar número de adultos y niños según el programa
        const progAdt = document.querySelector('input[name="cantidad_adultos"]')?.value;
        const progChd = document.querySelector('input[name="cantidad_ninos"]')?.value;
        const progPax = document.querySelector('input[name="cantidad_pax"]')?.value;

        if (adtInput) adtInput.value = progAdt ? Math.max(1, parseInt(progAdt, 10)) : (progPax ? Math.max(1, parseInt(progPax, 10)) : 2);
        if (chdInput) chdInput.value = progChd !== undefined && progChd !== null ? Math.max(0, parseInt(progChd, 10) || 0) : 0;
        syncModalPax();
        mostrarPasoMaestro(modalEl, 'catalogo');
        renderizarItems();
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        desgloseMaestro = null;
        servicioMaestro = null;
        partesEl?.replaceChildren();
    });
}

export function initTarifas() {
    initFormulario();
    initEstadias();
    initAsignar();
    initItinerarioServicios();
    initModalTarifarioMaestro();
}
