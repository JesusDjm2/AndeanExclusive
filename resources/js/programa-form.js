// Formulario de programas (resources/views/programas/form.blade.php):
// pestañas, pasajeros dinámicos, alojamiento, presupuesto en vivo y aviso de cambios sin guardar.

const money = (n) => '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
const num = (input) => parseFloat(input?.value) || 0;

function initTabs(root) {
    const current = root.querySelector('[data-current-tab]');

    root.querySelectorAll('[data-bs-toggle="tab"]').forEach((tab) => {
        tab.addEventListener('shown.bs.tab', () => {
            const key = tab.dataset.tab;
            if (current) current.value = key;
            // La pestaña queda en la URL: al recargar o volver de guardar se abre la misma.
            const url = new URL(window.location.href);
            url.searchParams.set('tab', key);
            window.history.replaceState(null, '', url);
        });
    });

    // Con errores de validación, llevar el foco al primer campo marcado.
    root.querySelector('.tab-pane.active .is-invalid')?.focus({ preventScroll: false });
}

function initPassengers(root, onChange) {
    const list = root.querySelector('[data-pax-list]');
    const template = root.querySelector('[data-pax-template]');
    const addButton = root.querySelector('[data-pax-add]');
    const empty = root.querySelector('[data-pax-empty]');
    const counters = root.querySelectorAll('[data-pax-count]');
    if (!list || !template) return;

    let nextIndex = parseInt(list.dataset.nextIndex, 10) || 0;

    const refresh = () => {
        const items = list.querySelectorAll('[data-pax-item]');
        items.forEach((item, i) => {
            const n = item.querySelector('[data-pax-num]');
            if (n) n.textContent = String(i + 1).padStart(2, '0');
        });
        counters.forEach((c) => { c.textContent = items.length; });
        if (empty) empty.hidden = items.length > 0;
        onChange();
    };

    addButton?.addEventListener('click', () => {
        list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', nextIndex++));
        refresh();
        list.lastElementChild?.querySelector('[data-pax-nombre]')?.focus();
    });

    list.addEventListener('click', (event) => {
        const remove = event.target.closest('[data-pax-remove]');
        if (!remove) return;
        remove.closest('[data-pax-item]').remove();
        refresh();
    });

    list.addEventListener('input', (event) => {
        if (event.target.matches('[data-pax-nombre]')) {
            const title = event.target.closest('[data-pax-item]').querySelector('.prog-pax-card__title');
            if (title) title.textContent = event.target.value.trim() || 'Nuevo pasajero';
        }
    });
    list.addEventListener('change', (event) => {
        if (event.target.matches('[data-pax-nacimiento]')) onChange();
    });

    refresh();
}

// Itinerario: servicios por día (agregar desde la plantilla, quitar y color del estado).
function initItinerario(root) {
    const section = root.querySelector('[data-itinerario]');
    const template = root.querySelector('[data-svc-template]');
    if (!section || !template) return;

    let nextIndex = parseInt(section.dataset.nextIndex, 10) || 0;

    section.addEventListener('click', (event) => {
        const add = event.target.closest('[data-svc-add]');
        if (add) {
            const list = add.parentElement.querySelector('[data-svc-list]');
            const html = template.innerHTML.replaceAll('__INDEX__', nextIndex++).replaceAll('__FECHA__', add.dataset.fecha);
            list.insertAdjacentHTML('beforeend', html);
            list.lastElementChild?.querySelector('[data-svc-titulo]')?.focus();
            return;
        }
        const remove = event.target.closest('[data-svc-remove]');
        if (remove) remove.closest('[data-svc-item]').remove();
    });

    section.addEventListener('change', (event) => {
        const select = event.target.closest('[data-svc-estado]');
        if (!select) return;
        select.className = select.className.replace(/svc-estado--\S+/, `svc-estado--${select.value}`);
    });
}

// Alojamiento: filas de habitación con cantidad y fechas; avisa si faltan plazas para el grupo (sin bloquear: los infantes suelen compartir).
function initEstadias(root) {
    const section = root.querySelector('[data-estadias]');
    const list = section?.querySelector('[data-estadias-list]');
    const template = section?.querySelector('[data-estadias-template]');
    if (!list || !template) return;

    const resumen = section.querySelector('[data-estadias-resumen]');
    const empty = section.querySelector('[data-estadias-empty]');
    const inicioViaje = root.querySelector('[data-fecha-inicio]');
    const finViaje = root.querySelector('[data-fecha-fin]');
    let nextIndex = parseInt(section.dataset.nextIndex, 10) || 0;

    const aFecha = (texto) => new Date(texto + 'T00:00:00');
    const diaSiguiente = (fecha) => new Date(fecha.getFullYear(), fecha.getMonth(), fecha.getDate() + 1);
    const corta = (fecha) => fecha.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit' });

    const leerFilas = () => [...list.querySelectorAll('[data-estadia-item]')].map((fila) => {
        const select = fila.querySelector('[data-estadia-habitacion]');
        const capacidad = parseInt(select.selectedOptions[0]?.dataset.capacidad, 10) || 0;
        const cantidad = parseInt(fila.querySelector('[data-estadia-cantidad]').value, 10) || 0;
        return {
            fila,
            elegida: select.value !== '',
            cantidad,
            plazas: capacidad * cantidad,
            ingreso: fila.querySelector('[data-estadia-ingreso]').value,
            salida: fila.querySelector('[data-estadia-salida]').value,
        };
    });

    const actualizar = () => {
        const filas = leerFilas();
        if (empty) empty.hidden = filas.length > 0;

        filas.forEach(({ fila, ingreso, salida }) => {
            const noches = ingreso && salida ? Math.round((aFecha(salida) - aFecha(ingreso)) / 86400000) : 0;
            fila.querySelector('[data-estadia-noches]').textContent = noches > 0 ? `${noches} ${noches === 1 ? 'noche' : 'noches'}` : '';
            fila.querySelector('[data-estadia-salida]').min = ingreso;
        });

        const elegidas = filas.filter((f) => f.elegida);
        if (!elegidas.length) {
            resumen.textContent = '';
            resumen.className = 'estadia-resumen';
            return;
        }

        const pasajeros = [...root.querySelectorAll('[data-pax-item]')].filter((el) => !el.closest('template')).length;
        const conFechas = elegidas.filter((f) => f.ingreso && f.salida && f.salida > f.ingreso);

        // Plazas por noche: el grupo debe caber cada noche, no solo en total.
        const porNoche = new Map();
        const cuartosPorNoche = new Map();
        conFechas.forEach((f) => {
            for (let d = aFecha(f.ingreso), fin = aFecha(f.salida); d < fin; d = diaSiguiente(d)) {
                porNoche.set(d.getTime(), (porNoche.get(d.getTime()) || 0) + f.plazas);
                cuartosPorNoche.set(d.getTime(), (cuartosPorNoche.get(d.getTime()) || 0) + f.cantidad);
            }
        });
        const faltantes = [...porNoche.entries()].filter(([, plazas]) => plazas < pasajeros).sort(([a], [b]) => a - b);
        const plazasSinFechas = elegidas.reduce((total, f) => total + f.plazas, 0);

        const avisos = [];
        if (pasajeros > 0 && faltantes.length) {
            const [noche, plazas] = faltantes[0];
            avisos.push(`Faltan plazas: la noche del ${corta(new Date(noche))} hay ${plazas} para ${pasajeros} pasajeros${faltantes.length > 1 ? ` (y ${faltantes.length - 1} noches más)` : ''}.`);
        } else if (pasajeros > 0 && !conFechas.length && plazasSinFechas < pasajeros) {
            avisos.push(`Faltan plazas: hay ${plazasSinFechas} para ${pasajeros} pasajeros.`);
        }
        if (inicioViaje?.value && finViaje?.value && conFechas.some((f) => f.ingreso < inicioViaje.value || f.salida > finViaje.value)) {
            avisos.push('Hay estadías fuera de las fechas del viaje.');
        }

        const cuartos = [...cuartosPorNoche.values()];
        const [menos, mas] = cuartos.length ? [Math.min(...cuartos), Math.max(...cuartos)] : [0, 0];
        const totalSinFechas = elegidas.reduce((total, f) => total + f.cantidad, 0);
        let habitacionesTexto = `${totalSinFechas} ${totalSinFechas === 1 ? 'habitación' : 'habitaciones'}`;
        if (cuartos.length) {
            habitacionesTexto = menos === mas
                ? `${mas} ${mas === 1 ? 'habitación' : 'habitaciones'} por noche`
                : `Entre ${menos} y ${mas} habitaciones por noche`;
        }
        const base = habitacionesTexto + (pasajeros ? ` · ${pasajeros} ${pasajeros === 1 ? 'pasajero' : 'pasajeros'}` : '');
        resumen.textContent = avisos.length ? `${base}. ${avisos.join(' ')}` : `${base}.`;
        resumen.className = `estadia-resumen ${avisos.length ? 'is-warn' : ''}`;
    };

    section.querySelector('[data-estadias-add]')?.addEventListener('click', () => {
        list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', nextIndex++));
        const fila = list.lastElementChild;
        // Por defecto, las fechas del viaje; si hay filas, continúa desde la última salida.
        const ultima = leerFilas().slice(0, -1).reverse().find((f) => f.salida);
        fila.querySelector('[data-estadia-ingreso]').value = inicioViaje?.value || '';
        fila.querySelector('[data-estadia-salida]').value = finViaje?.value || '';
        if (ultima && finViaje?.value && ultima.salida < finViaje.value) {
            fila.querySelector('[data-estadia-ingreso]').value = ultima.salida;
        }
        fila.querySelector('[data-estadia-habitacion]').focus();
        actualizar();
    });

    list.addEventListener('click', (event) => {
        const quitar = event.target.closest('[data-estadia-remove]');
        if (!quitar) return;
        quitar.closest('[data-estadia-item]').remove();
        actualizar();
    });

    // También cambia con los pasajeros y las fechas del viaje, que están en otras pestañas.
    root.addEventListener('input', actualizar);
    root.addEventListener('change', actualizar);
    root.addEventListener('click', (event) => {
        if (event.target.closest('[data-pax-remove], [data-pax-add], [data-tab="alojamiento"]')) setTimeout(actualizar);
    });

    actualizar();
}

function initBudget(root) {
    const out = (key) => root.querySelectorAll(`[data-b="${key}"]`);
    const set = (key, text) => out(key).forEach((el) => { el.textContent = text; });
    const edadAdulto = parseInt(root.dataset.edadAdulto, 10) || 12;
    const edadNino = parseInt(root.dataset.edadNino, 10) || 5;
    const depositoPct = parseFloat(root.dataset.deposito) || 50;

    const compute = () => {
        // Edad a la fecha de inicio del viaje (la tarifa depende de esa edad), igual que Pax::edad en el servidor.
        const inicio = root.querySelector('[data-fecha-inicio]')?.value;
        const referencia = inicio ? new Date(inicio + 'T00:00:00') : new Date();
        const edades = [...root.querySelectorAll('[data-pax-nacimiento]')]
            .filter((input) => !input.closest('template') && input.value !== '')
            .map((input) => {
                const nac = new Date(input.value + 'T00:00:00');
                let edad = referencia.getFullYear() - nac.getFullYear();
                const cumpleaños = new Date(referencia.getFullYear(), nac.getMonth(), nac.getDate());
                if (referencia < cumpleaños) edad -= 1;
                return edad;
            });
        const adultos = edades.filter((e) => e >= edadAdulto).length;
        const ninos = edades.filter((e) => e >= edadNino && e < edadAdulto).length;
        const infantes = edades.filter((e) => e < edadNino).length;

        const factor = 1 + num(root.querySelector('[data-ganancia]')) / 100;
        const costoAdulto = num(root.querySelector('[data-precio-adulto]'));
        const costoNino = num(root.querySelector('[data-precio-nino]'));
        const costo = adultos * costoAdulto + ninos * costoNino;
        const singles = parseInt(root.querySelector('[data-singles]')?.value, 10) || 0;
        const suplemento = singles * num(root.querySelector('[data-suplemento]'));
        const total = costo * factor + suplemento;

        set('adultos', adultos);
        set('ninos', ninos);
        set('infantes', infantes);
        set('ventaAdulto', money(costoAdulto * factor));
        set('ventaNino', money(costoNino * factor));
        set('subAdultos', money(adultos * costoAdulto * factor));
        set('subNinos', money(ninos * costoNino * factor));
        set('total', money(total));
        set('costo', money(costo));
        set('singles', singles);
        set('suplemento', money(suplemento));
        set('ganancia', money(costo * factor - costo));
        set('deposito', money(total * depositoPct / 100));
    };

    root.querySelectorAll('[data-precio-adulto], [data-precio-nino], [data-ganancia], [data-fecha-inicio], [data-suplemento], [data-singles]').forEach((input) => {
        input.addEventListener('input', compute);
    });

    return compute;
}

function initDuration(root) {
    const inicio = root.querySelector('[data-fecha-inicio]');
    const fin = root.querySelector('[data-fecha-fin]');
    const hint = root.querySelector('[data-duracion]');
    if (!inicio || !fin || !hint) return;

    const base = hint.innerHTML;
    const update = () => {
        if (!inicio.value || !fin.value) { hint.innerHTML = base; return; }
        const dias = Math.round((new Date(fin.value) - new Date(inicio.value)) / 86400000) + 1;
        hint.innerHTML = dias > 0
            ? `<i class="fas fa-calendar-check" aria-hidden="true"></i> Duración: <strong>${dias} ${dias === 1 ? 'día' : 'días'}</strong>. Año y mes se asignan solos.`
            : '<i class="fas fa-exclamation-triangle" aria-hidden="true"></i> La fecha de fin es anterior al inicio.';
        fin.min = inicio.value;
    };
    inicio.addEventListener('change', update);
    fin.addEventListener('change', update);
    update();
}

// Cliente: elegir uno existente o crearlo en el mismo formulario.
function initClientePicker(root) {
    const picker = root.querySelector('[data-cliente-picker]');
    if (!picker) return;

    const sync = () => {
        const modo = picker.querySelector('input[name="cliente_modo"]:checked')?.value;
        picker.querySelectorAll('[data-cliente-panel]').forEach((panel) => {
            panel.hidden = panel.dataset.clientePanel !== modo;
        });
    };
    picker.querySelectorAll('input[name="cliente_modo"]').forEach((radio) => radio.addEventListener('change', sync));
    sync();
}

function initDirtyGuard(root) {
    const form = root.querySelector('#programa-form');
    const indicator = root.querySelector('[data-dirty-indicator]');
    if (!form) return;

    let dirty = false;
    const markDirty = () => {
        dirty = true;
        if (indicator) indicator.hidden = false;
    };

    form.addEventListener('input', markDirty);
    form.addEventListener('change', markDirty);
    root.querySelectorAll('[data-pax-add], [data-svc-add], [data-svc-remove]').forEach((b) => b.addEventListener('click', markDirty));
    root.querySelector('[data-itinerario]')?.addEventListener('click', (event) => {
        if (event.target.closest('[data-svc-add], [data-svc-remove]')) markDirty();
    });
    form.addEventListener('submit', () => { dirty = false; });

    // CKEditor no dispara eventos en el <textarea>: escuchar su propio cambio.
    window.CKEDITOR?.on('instanceReady', (event) => event.editor.on('change', markDirty));

    window.addEventListener('beforeunload', (event) => {
        if (!dirty) return;
        event.preventDefault();
        event.returnValue = '';
    });
}

// Los botones de correo de la pestaña Pasajeros vuelven a esa pestaña tras enviar.
function initNotificaciones(root) {
    const tab = root.querySelector('[data-notif-tab]');
    root.querySelectorAll('[data-notif-from]').forEach((button) => {
        button.addEventListener('click', () => { if (tab) tab.value = button.dataset.notifFrom; });
    });
}

export function initProgramaForm() {
    const root = document.querySelector('[data-programa-form]');
    if (!root) return;

    const recompute = initBudget(root);
    initTabs(root);
    initPassengers(root, recompute);
    initEstadias(root);
    initItinerario(root);
    initClientePicker(root);
    initDuration(root);
    initDirtyGuard(root);
    initNotificaciones(root);
    recompute();
}
