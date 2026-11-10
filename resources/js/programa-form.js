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

// Itinerario: servicios por día y agregar / quitar días en caliente sin salir a pestaña Datos.
function initItinerario(root) {
    const section = root.querySelector('[data-itinerario]');
    const template = root.querySelector('[data-svc-template]');
    const dayTemplate = root.querySelector('[data-itin-day-template]');
    const daysList = section?.querySelector('[data-itin-days-list]');
    const finInput = root.querySelector('[data-fecha-fin]');
    const inicioInput = root.querySelector('[data-fecha-inicio]');
    if (!section || !template) return;

    let nextIndex = parseInt(section.dataset.nextIndex, 10) || 0;

    const aIso = (d) => {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    };

    const formatearFechaEs = (isoStr) => {
        try {
            const parts = isoStr.split('-');
            const date = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
            const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            return `${diasSemana[date.getDay()]} ${date.getDate()} de ${meses[date.getMonth()]}`;
        } catch {
            return isoStr;
        }
    };

    // Agregar nuevo día al final del itinerario
    const agregarDia = () => {
        if (!dayTemplate || !daysList) return;
        const dayItems = daysList.querySelectorAll('[data-itin-day-item]');
        let nextDateStr = '';
        let nextDayNum = dayItems.length + 1;

        if (dayItems.length > 0) {
            const lastDay = dayItems[dayItems.length - 1];
            const lastDate = lastDay.dataset.fecha;
            const d = new Date(lastDate + 'T00:00:00');
            d.setDate(d.getDate() + 1);
            nextDateStr = aIso(d);
        } else if (finInput?.value) {
            const d = new Date(finInput.value + 'T00:00:00');
            d.setDate(d.getDate() + 1);
            nextDateStr = aIso(d);
        } else if (inicioInput?.value) {
            nextDateStr = inicioInput.value;
        } else {
            const today = new Date();
            nextDateStr = aIso(today);
        }

        const fechaLabel = formatearFechaEs(nextDateStr);
        const html = dayTemplate.innerHTML
            .replaceAll('__FECHA__', nextDateStr)
            .replaceAll('__NUM__', nextDayNum)
            .replaceAll('__FECHA_LABEL__', fechaLabel);

        daysList.insertAdjacentHTML('beforeend', html);

        // Sincronizar fecha fin del programa
        if (finInput) {
            finInput.value = nextDateStr;
            finInput.dispatchEvent(new Event('input', { bubbles: true }));
            finInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        const nuevoDia = daysList.lastElementChild;
        nuevoDia?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        nuevoDia?.querySelector('input[name$="[titulo]"]')?.focus();
    };

    // Quitar el último día del itinerario con confirmación si tiene servicios o texto
    const quitarUltimoDia = () => {
        if (!daysList) return;
        const dayItems = daysList.querySelectorAll('[data-itin-day-item]');
        if (dayItems.length <= 1) {
            const msg = 'El programa debe conservar al menos 1 día en su itinerario.';
            if (window.Swal) {
                window.Swal.fire({ icon: 'info', title: 'Atención', text: msg, confirmButtonColor: '#0f172a' });
            } else {
                alert(msg);
            }
            return;
        }

        const ultimoDia = dayItems[dayItems.length - 1];
        const numDia = ultimoDia.dataset.diaNum || dayItems.length;
        const servicesCount = ultimoDia.querySelectorAll('[data-svc-item]').length;
        const titleVal = ultimoDia.querySelector('input[name$="[titulo]"]')?.value.trim();
        const descVal = ultimoDia.querySelector('textarea[name$="[descripcion]"]')?.value.trim();
        const tieneContenido = servicesCount > 0 || !!titleVal || !!descVal;

        const procederQuitar = () => {
            ultimoDia.remove();
            const remainingDays = daysList.querySelectorAll('[data-itin-day-item]');
            const nuevoUltimo = remainingDays[remainingDays.length - 1];
            if (nuevoUltimo && finInput) {
                finInput.value = nuevoUltimo.dataset.fecha;
                finInput.dispatchEvent(new Event('input', { bubbles: true }));
                finInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        };

        if (tieneContenido) {
            const confirmText = `El Día ${numDia} contiene ${servicesCount} servicio(s) o textos. ¿Seguro que deseas eliminarlo del viaje?`;
            if (window.Swal) {
                window.Swal.fire({
                    title: `¿Eliminar Día ${numDia}?`,
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, quitar día',
                    cancelButtonText: 'Cancelar'
                }).then((res) => {
                    if (res.isConfirmed) procederQuitar();
                });
            } else if (confirm(confirmText)) {
                procederQuitar();
            }
        } else {
            procederQuitar();
        }
    };

    section.addEventListener('click', (event) => {
        if (event.target.closest('[data-itin-add-day]')) {
            event.preventDefault();
            agregarDia();
            return;
        }
        if (event.target.closest('[data-itin-remove-day]')) {
            event.preventDefault();
            quitarUltimoDia();
            return;
        }
        const add = event.target.closest('[data-svc-add]');
        if (add) {
            const diaItem = add.closest('[data-itin-day-item]');
            const isLibre = diaItem?.querySelector('[data-dia-libre-check]')?.checked;
            if (isLibre) {
                event.preventDefault();
                const aviso = 'Este día está marcado como «Día libre». Para agregar servicios operativos, primero debes desmarcar la casilla «Día libre».';
                if (window.Swal) {
                    window.Swal.fire({ icon: 'info', title: 'Día libre', text: aviso, confirmButtonColor: '#0f172a' });
                } else {
                    alert(aviso);
                }
                return;
            }
            const list = add.parentElement.querySelector('[data-svc-list]');
            const html = template.innerHTML.replaceAll('__INDEX__', nextIndex++).replaceAll('__FECHA__', add.dataset.fecha);
            list.insertAdjacentHTML('beforeend', html);
            list.lastElementChild?.querySelector('[data-svc-titulo]')?.focus();
            return;
        }
        const remove = event.target.closest('[data-svc-remove]');
        if (remove) remove.closest('[data-svc-item]').remove();

        const btnCli = event.target.closest('[data-svc-toggle-cliente-pax]');
        if (btnCli) {
            const row = btnCli.closest('[data-svc-item]');
            if (!row) return;

            const estInput = row.querySelector('[data-svc-estimado]');
            const costInput = row.querySelector('[data-svc-costo]');
            const estadoSel = row.querySelector('[data-svc-estado]');
            const origen = row.querySelector('[data-svc-origen]');

            const isAlreadyCli = btnCli.classList.contains('btn-info');
            if (!isAlreadyCli) {
                btnCli.classList.remove('btn-outline-info');
                btnCli.classList.add('btn-info', 'text-white');
                btnCli.title = 'Servicio reservado por el cliente (Costo $0)';
                row.dataset.prevEstimado = estInput ? estInput.value : '';
                row.dataset.prevCosto = costInput ? costInput.value : '';
                if (estInput) { estInput.value = '0.00'; estInput.dispatchEvent(new Event('input', { bubbles: true })); }
                if (costInput) { costInput.value = '0.00'; costInput.dispatchEvent(new Event('input', { bubbles: true })); }
                if (estadoSel) { estadoSel.value = 'confirmado'; estadoSel.dispatchEvent(new Event('change', { bubbles: true })); }
                if (origen) {
                    origen.hidden = false;
                    origen.textContent = '✔ Reservado por el cliente (Costo US$ 0.00)';
                    origen.className = 'svc-fin__origen';
                }
            } else {
                btnCli.classList.remove('btn-info', 'text-white');
                btnCli.classList.add('btn-outline-info');
                btnCli.title = 'Marcar si este servicio fue contratado/reservado directamente por el cliente (Costo US$ 0.00)';
                if (estInput && row.dataset.prevEstimado !== undefined) {
                    estInput.value = row.dataset.prevEstimado;
                    estInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                if (costInput && row.dataset.prevCosto !== undefined) {
                    costInput.value = row.dataset.prevCosto;
                    costInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                if (origen) {
                    origen.hidden = true;
                    origen.textContent = '';
                }
            }
        }
    });

    section.addEventListener('change', (event) => {
        const select = event.target.closest('[data-svc-estado]');
        if (!select) return;
        select.className = select.className.replace(/svc-estado--\S+/, `svc-estado--${select.value}`);
    });

    // Día libre: marcar badge, ocultar botón de agregar servicio y sugerir título y texto
    section.addEventListener('change', (event) => {
        const check = event.target.closest('[data-dia-libre-check]');
        if (!check) return;
        const dia = check.closest('[data-itin-day-item]');
        if (!dia) return;
        const badge = dia.querySelector('[data-dia-libre-badge]');
        const titulo = dia.querySelector('[data-dia-titulo-input]');
        const desc = dia.querySelector('[data-dia-desc-input]');
        const addBtn = dia.querySelector('[data-svc-add]');
        const msg = dia.querySelector('[data-dia-libre-msg]');

        if (badge) badge.style.display = check.checked ? '' : 'none';
        if (addBtn) addBtn.style.display = check.checked ? 'none' : '';
        if (msg) msg.style.display = check.checked ? '' : 'none';

        if (check.checked) {
            const numDia = dia.dataset.diaNum || '';
            if (titulo && !titulo.value.trim()) {
                titulo.value = numDia ? `Día ${numDia}` : 'Día libre';
            }
            if (desc && !desc.value.trim()) {
                desc.value = 'Jornada libre para descansar, aclimatarse o recorrer la ciudad y sus atractivos a su propio ritmo.';
            }
        }
    });

    // Inicializar estado de día libre en todos los días existentes al cargar
    daysList?.querySelectorAll('[data-itin-day-item]').forEach((dia) => {
        const check = dia.querySelector('[data-dia-libre-check]');
        const addBtn = dia.querySelector('[data-svc-add]');
        const msg = dia.querySelector('[data-dia-libre-msg]');
        const badge = dia.querySelector('[data-dia-libre-badge]');
        if (check && check.checked) {
            if (addBtn) addBtn.style.display = 'none';
            if (msg) msg.style.display = '';
            if (badge) badge.style.display = '';
        }
    });

    // Moneda de cada servicio: un símbolo junto al precio; sigue a la del programa salvo que una tarifa o un clic la fijen.
    const SIMBOLOS = { USD: 'US$', PEN: 'S/' };
    const monedaPrograma = root.querySelector('[data-programa-moneda]');
    const pintarMoneda = (input) => {
        const boton = input.closest('.input-group')?.querySelector('[data-svc-moneda-toggle]');
        if (boton) boton.textContent = SIMBOLOS[input.value] ?? input.value;
    };
    const seguirMoneda = () => {
        if (!monedaPrograma) return;
        section.querySelectorAll('[data-svc-moneda][data-moneda-auto="1"]').forEach((input) => {
            if (input.closest('.input-group')?.querySelector('[data-svc-moneda-toggle]')?.disabled) return;
            input.value = monedaPrograma.value;
            pintarMoneda(input);
        });
        actualizarTipoCambio();
    };
    section.addEventListener('click', (event) => {
        const boton = event.target.closest('[data-svc-moneda-toggle]');
        if (!boton || boton.disabled) return;
        const input = boton.closest('.input-group').querySelector('[data-svc-moneda]');
        input.value = input.value === 'USD' ? 'PEN' : 'USD';
        input.dataset.monedaAuto = '0';
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
    // Cualquier cambio de moneda (clic, tarifa aplicada) repinta el símbolo y revisa si hace falta tipo de cambio.
    root.addEventListener('change', (event) => {
        if (event.target.matches('[data-svc-moneda]')) pintarMoneda(event.target);
        if (event.target.matches('[data-svc-moneda], [data-estadia-moneda]')) actualizarTipoCambio();
    });
    monedaPrograma?.addEventListener('change', seguirMoneda);

    // El tipo de cambio solo se pide si algún servicio u hotel se cobra en una moneda distinta a la del programa.
    const campoTc = root.querySelector('[data-tipo-cambio-campo]');
    const motivoTc = root.querySelector('[data-tipo-cambio-motivo]');
    function actualizarTipoCambio() {
        if (!campoTc || !monedaPrograma) return;
        const distintas = [...root.querySelectorAll('[data-svc-moneda], [data-estadia-moneda]')]
            .filter((el) => !el.closest('template') && el.value && el.value !== monedaPrograma.value).length;
        campoTc.hidden = distintas === 0 && !campoTc.querySelector('.is-invalid');
        if (motivoTc) motivoTc.textContent = distintas ? `${distintas} ${distintas === 1 ? 'costo está' : 'costos están'} en otra moneda; se convierten con este valor.` : '';
    }

    // Proveedor según el tipo de servicio: un traslado solo ofrece transportistas, una guía solo guías, etc.
    const opcionesProveedor = template.content.querySelector('[data-svc-proveedor-select]')?.cloneNode(true);
    const filtrarProveedores = (fila, limpiarSiNoCorresponde) => {
        const select = fila.querySelector('[data-svc-proveedor-select]');
        const tipo = fila.querySelector('[data-svc-tipo]')?.value;
        if (!select || select.disabled || !opcionesProveedor || !tipo) return;
        const grupos = [...opcionesProveedor.querySelectorAll('optgroup')];
        const delTipo = grupos.filter((g) => g.dataset.tipos.split(' ').includes(tipo));
        const actual = select.value;
        // Sin rubros del tipo (actividad, otro…) se ofrecen todos.
        const visibles = delTipo.length ? delTipo : grupos;
        const corresponde = !actual || visibles.some((g) => g.querySelector(`option[value="${actual}"]`));
        select.replaceChildren(opcionesProveedor.querySelector('option[value=""]').cloneNode(true));
        visibles.forEach((g) => select.append(g.cloneNode(true)));
        // Al cargar se conserva lo guardado aunque sea de otro rubro; al cambiar el tipo, se quita.
        if (!corresponde && !limpiarSiNoCorresponde) {
            const propio = grupos.find((g) => g.querySelector(`option[value="${actual}"]`));
            if (propio) select.append(propio.cloneNode(true));
        }
        select.value = corresponde || !limpiarSiNoCorresponde ? actual : '';
        if (select.value !== actual) select.dispatchEvent(new Event('change', { bubbles: true }));
    };
    section.addEventListener('change', (event) => {
        if (event.target.matches('[data-svc-tipo]')) filtrarProveedores(event.target.closest('[data-svc-item]'), true);
    });
    section.addEventListener('click', (event) => {
        if (!event.target.closest('[data-svc-add]')) return;
        queueMicrotask(() => {
            const fila = event.target.closest('[data-svc-add]').parentElement.querySelector('[data-svc-list]')?.lastElementChild;
            if (fila) filtrarProveedores(fila, true);
            seguirMoneda();
        });
    });
    section.querySelectorAll('[data-svc-item]').forEach((fila) => filtrarProveedores(fila, false));
    actualizarTipoCambio();
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
            const cantidadFila = parseInt(fila.querySelector('[data-estadia-cantidad]').value, 10) || 0;
            const costoNoche = parseFloat(fila.querySelector('[data-estadia-costo]')?.value) || 0;
            const simbolo = fila.querySelector('[data-estadia-moneda]')?.selectedOptions[0]?.textContent.trim() ?? '';
            const total = noches > 0 && costoNoche > 0 ? ` · ${simbolo} ${(costoNoche * cantidadFila * noches).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : '';
            fila.querySelector('[data-estadia-noches]').textContent = noches > 0 ? `${noches} ${noches === 1 ? 'noche' : 'noches'}${total}` : '';
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

    // Control de "Reservado por el cliente": oculta inputs de costo, muestra badge de costo $0 y recalcula presupuesto
    list.addEventListener('change', (event) => {
        const switchCli = event.target.closest('[data-estadia-reservado-cliente]');
        if (!switchCli) return;
        const fila = switchCli.closest('[data-estadia-item]');
        const inputsWrap = fila?.querySelector('[data-estadia-costo-inputs]');
        const badgeWrap = fila?.querySelector('[data-estadia-costo-badge]');
        const costoInput = fila?.querySelector('[data-estadia-costo]');

        if (switchCli.checked) {
            if (inputsWrap) inputsWrap.style.display = 'none';
            if (badgeWrap) badgeWrap.style.display = 'block';
            if (costoInput) {
                costoInput.dataset.prevCost = costoInput.value;
                costoInput.value = '0.00';
            }
        } else {
            if (inputsWrap) inputsWrap.style.display = 'flex';
            if (badgeWrap) badgeWrap.style.display = 'none';
            if (costoInput && costoInput.dataset.prevCost !== undefined) {
                costoInput.value = costoInput.dataset.prevCost || '';
            }
        }
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
        const cantAdultosInput = root.querySelector('[data-cantidad-adultos]');
        const cantNinosInput = root.querySelector('[data-cantidad-ninos]');
        const cantPaxInput = root.querySelector('[data-cantidad-pax]');
        const totalBadge = root.querySelector('#pf-total-pax-badge');

        const adtVal = cantAdultosInput ? Math.max(1, parseInt(cantAdultosInput.value, 10) || 1) : Math.max(1, parseInt(cantPaxInput?.value, 10) || 1);
        const chdVal = cantNinosInput ? Math.max(0, parseInt(cantNinosInput.value, 10) || 0) : 0;
        const totalPaxVal = adtVal + chdVal;

        if (cantPaxInput) cantPaxInput.value = totalPaxVal;
        if (totalBadge) totalBadge.textContent = `${totalPaxVal} Pax`;

        let adultos = edades.filter((e) => e >= edadAdulto).length;
        let ninos = edades.filter((e) => e >= edadNino && e < edadAdulto).length;
        let infantes = edades.filter((e) => e < edadNino).length;

        // Si aún no se han registrado pasajeros individuales en la lista, usamos la cantidad de adultos y niños del grupo
        if (edades.length === 0) {
            adultos = adtVal;
            ninos = chdVal;
        }

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

    root.querySelectorAll('[data-precio-adulto], [data-precio-nino], [data-ganancia], [data-fecha-inicio], [data-suplemento], [data-singles], [data-cantidad-pax], [data-cantidad-adultos], [data-cantidad-ninos]').forEach((input) => {
        input.addEventListener('input', compute);
        input.addEventListener('change', compute);
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
        const diffMs = new Date(fin.value + 'T00:00:00') - new Date(inicio.value + 'T00:00:00');
        const dias = Math.round(diffMs / 86400000) + 1;
        const noches = Math.max(0, dias - 1);
        if (dias > 0) {
            hint.innerHTML = `
                <div class="d-inline-flex flex-column gap-1">
                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2 text-start fs-6">
                        <i class="fas fa-check-circle me-1"></i> Duración fijada: <strong>${dias} ${dias === 1 ? 'día' : 'días'} / ${noches} ${noches === 1 ? 'noche' : 'noches'}</strong>
                    </span>
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> El año y mes se asignan automáticamente.</small>
                </div>
            `;
        } else {
            hint.innerHTML = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle p-2"><i class="fas fa-exclamation-triangle me-1"></i> La fecha de fin debe ser posterior al inicio.</span>';
        }
        fin.min = inicio.value;
    };
    inicio.addEventListener('change', update);
    fin.addEventListener('change', update);
    update();
}

// Control interactivo de vuelos de llegada y salida con aceptación explícita de fecha/hora
function initFlightControls(root) {
    const inicioInput = root.querySelector('[data-fecha-inicio]');
    const finInput = root.querySelector('[data-fecha-fin]');

    ['llegada', 'salida'].forEach((tipo) => {
        const dateInput = root.querySelector(`[data-flight-date="${tipo}"]`);
        const timeInput = root.querySelector(`[data-flight-time="${tipo}"]`);
        const targetInput = root.querySelector(`[data-flight-target="${tipo}"]`);
        const statusBox = root.querySelector(`[data-flight-status="${tipo}"]`);
        const acceptBtn = root.querySelector(`[data-flight-accept="${tipo}"]`);
        const clearBtn = root.querySelector(`[data-flight-clear="${tipo}"]`);
        const syncBtn = root.querySelector(`[data-flight-sync="${tipo}"]`);
        const presetBtns = root.querySelectorAll(`[data-flight-preset="${tipo}"]`);

        if (!dateInput || !timeInput || !targetInput || !statusBox) return;

        const formatHumanDate = (dateStr, timeStr) => {
            if (!dateStr) return '';
            try {
                const [y, m, d] = dateStr.split('-').map(Number);
                const fecha = new Date(y, m - 1, d);
                const formateada = fecha.toLocaleDateString('es-PE', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                const conMayus = formateada.charAt(0).toUpperCase() + formateada.slice(1);
                return timeStr ? `${conMayus} a las ${timeStr} hrs` : conMayus;
            } catch {
                return dateStr + (timeStr ? ` ${timeStr}` : '');
            }
        };

        const updateStatus = (confirmed = false) => {
            const dateVal = dateInput.value;
            const timeVal = timeInput.value || '';

            if (dateVal) {
                targetInput.value = timeVal ? `${dateVal}T${timeVal}` : `${dateVal}T00:00`;
                const human = formatHumanDate(dateVal, timeVal);
                statusBox.innerHTML = `
                    <div class="alert alert-success py-2 px-3 small d-flex align-items-center justify-content-between mb-0 mt-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success fs-6"></i>
                            <span><strong>${tipo === 'llegada' ? 'Arribo' : 'Salida'} aceptada:</strong> ${human}</span>
                        </span>
                        <span class="badge bg-success text-white">Listo</span>
                    </div>
                `;
            } else {
                targetInput.value = '';
                statusBox.innerHTML = `
                    <div class="text-muted small fst-italic py-1 mt-1">
                        <i class="fas fa-info-circle text-secondary me-1"></i> Sin fecha ni hora fijada aún.
                    </div>
                `;
            }
        };

        // Al pulsar "Aceptar fecha y hora"
        acceptBtn?.addEventListener('click', () => {
            if (!dateInput.value) {
                const defaultFecha = tipo === 'llegada' ? inicioInput?.value : finInput?.value;
                if (defaultFecha) dateInput.value = defaultFecha;
            }
            if (!timeInput.value) {
                timeInput.value = tipo === 'llegada' ? '09:00' : '16:00';
            }
            updateStatus(true);

            // Feedback visual en el botón
            acceptBtn.classList.remove('btn-primary');
            acceptBtn.classList.add('btn-success');
            acceptBtn.innerHTML = '<i class="fas fa-check-double me-1"></i> ¡Aceptado!';
            setTimeout(() => {
                acceptBtn.classList.remove('btn-success');
                acceptBtn.classList.add('btn-primary');
                acceptBtn.innerHTML = '<i class="fas fa-check me-1"></i> Aceptar fecha y hora';
            }, 1800);
        });

        // Botón limpiar
        clearBtn?.addEventListener('click', () => {
            dateInput.value = '';
            timeInput.value = '';
            targetInput.value = '';
            updateStatus(false);
        });

        // Botón sincronizar con día de inicio o fin
        syncBtn?.addEventListener('click', () => {
            const baseDate = tipo === 'llegada' ? inicioInput?.value : finInput?.value;
            if (baseDate) {
                dateInput.value = baseDate;
                if (!timeInput.value) {
                    timeInput.value = tipo === 'llegada' ? '09:30' : '17:00';
                }
                updateStatus(true);
            } else {
                window.Swal?.fire({
                    icon: 'info',
                    title: 'Fechas del viaje pendientes',
                    text: `Primero ingresa la fecha de ${tipo === 'llegada' ? 'inicio' : 'fin'} del viaje en la sección superior.`,
                    confirmButtonColor: '#0f172a'
                }) || alert(`Primero ingresa la fecha de ${tipo === 'llegada' ? 'inicio' : 'fin'} del viaje.`);
            }
        });

        // Píldoras de horas sugeridas
        presetBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                timeInput.value = btn.dataset.time;
                if (!dateInput.value) {
                    const baseDate = tipo === 'llegada' ? inicioInput?.value : finInput?.value;
                    if (baseDate) dateInput.value = baseDate;
                }
                updateStatus(true);
            });
        });

        dateInput.addEventListener('change', () => updateStatus(false));
        timeInput.addEventListener('change', () => updateStatus(false));

        // Inicialización
        if (targetInput.value) {
            const [d, t] = targetInput.value.split('T');
            if (d && !dateInput.value) dateInput.value = d;
            if (t && !timeInput.value) timeInput.value = t.substring(0, 5);
            updateStatus(true);
        } else {
            updateStatus(false);
        }
    });
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

// Modal para registrar nuevo hotel y habitación al vuelo vía AJAX sin perder datos
function initNuevoHotelModal(root) {
    const modalEl = document.getElementById('modalNuevoHotel');
    const form = modalEl?.querySelector('[data-nuevo-hotel-form]');
    if (!modalEl || !form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = form.querySelector('[data-nuevo-hotel-submit]');
        const errorBox = form.querySelector('[data-nuevo-hotel-error]');
        if (errorBox) {
            errorBox.style.display = 'none';
            errorBox.textContent = '';
        }

        const prevBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i> Guardando...';
        }

        try {
            const formData = new FormData(form);
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();

            if (!res.ok || !data.ok) {
                const errs = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.mensaje || 'Error al registrar hotel');
                if (errorBox) {
                    errorBox.innerHTML = errs;
                    errorBox.style.display = 'block';
                }
                return;
            }

            // Inyectar optgroup y option en todos los selects de habitación (incluyendo templates)
            const selects = root.querySelectorAll('select[data-estadia-habitacion]');
            selects.forEach((sel) => {
                let group = [...sel.querySelectorAll('optgroup')].find(
                    (g) => g.label.toLowerCase().trim() === data.hotel.nombre.toLowerCase().trim()
                );
                if (!group) {
                    group = document.createElement('optgroup');
                    group.label = data.hotel.nombre;
                    sel.appendChild(group);
                }
                const opt = document.createElement('option');
                opt.value = data.habitacion.id;
                opt.dataset.capacidad = data.habitacion.capacidad;
                opt.textContent = `${data.hotel.nombre} · ${data.habitacion.tipo} (${data.habitacion.capacidad} ${data.habitacion.capacidad === 1 ? 'persona' : 'personas'})`;
                group.appendChild(opt);
            });

            // Seleccionar automáticamente en la fila que no tenga habitación asignada o en la última
            const list = root.querySelector('[data-estadias-list]');
            const rows = list ? [...list.querySelectorAll('[data-estadia-item]')] : [];
            const targetRow = rows.find((r) => !r.querySelector('select[data-estadia-habitacion]').value) || rows[rows.length - 1];
            if (targetRow) {
                const sel = targetRow.querySelector('select[data-estadia-habitacion]');
                if (sel) {
                    sel.value = data.habitacion.id;
                    sel.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            // Cerrar el modal de Bootstrap y limpiar campos
            const bsModal = window.bootstrap?.Modal?.getInstance(modalEl) || (window.bootstrap?.Modal ? new window.bootstrap.Modal(modalEl) : null);
            bsModal?.hide();
            form.reset();

            if (window.Swal) {
                window.Swal.fire({
                    icon: 'success',
                    title: '¡Hotel registrado!',
                    text: `${data.hotel.nombre} ha sido agregado y seleccionado en alojamiento.`,
                    timer: 2800,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        } catch (err) {
            if (errorBox) {
                errorBox.textContent = 'Hubo un error de conexión al guardar el hotel.';
                errorBox.style.display = 'block';
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = prevBtnHtml;
            }
        }
    });
}

// Calculadora inteligente de cotización: suma servicios de cada día y noches de hotel para obtener el costo base por persona
function initCotizacionCalculator(root, recomputeBudget) {
    const btn = root.querySelector('[data-btn-calcular-cotizacion]');
    const btnAplicar = root.querySelector('[data-btn-aplicar-cotizacion]');
    const liveBox = root.querySelector('[data-cotizacion-live-box]');
    const liveText = root.querySelector('[data-cotizacion-live-text]');
    const liveTotal = root.querySelector('[data-cotizacion-live-total]');
    const adultoInput = root.querySelector('[data-precio-adulto]');
    const ninoInput = root.querySelector('[data-precio-nino]');

    const calcular = () => {
        const monedaPrograma = root.querySelector('[data-programa-moneda]')?.value || 'USD';
        const tc = parseFloat(root.querySelector('input[name="tipo_cambio"]')?.value) || 3.75;

        // Convertir cualquier importe de servicio u hotel a la moneda base del programa
        const toMonedaPrograma = (monto, monedaItem) => {
            if (monedaPrograma === 'PEN') {
                return (monedaItem === 'USD' || !monedaItem) ? (monto * tc) : monto;
            }
            return monedaItem === 'PEN' ? (monto / tc) : monto;
        };

        const simbolo = monedaPrograma === 'PEN' ? 'S/ ' : '$ ';
        const moneyFmt = (n) => simbolo + Number(n || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Pasajeros
        const inicio = root.querySelector('[data-fecha-inicio]')?.value;
        const referencia = inicio ? new Date(inicio + 'T00:00:00') : new Date();
        const paxItems = [...root.querySelectorAll('[data-pax-item]')].filter((el) => !el.closest('template'));
        let pagantes = 0;
        let adultos = 0;
        let ninos = 0;

        paxItems.forEach((item) => {
            const nacInput = item.querySelector('[data-pax-nacimiento]');
            if (nacInput && nacInput.value) {
                const nac = new Date(nacInput.value + 'T00:00:00');
                let edad = referencia.getFullYear() - nac.getFullYear();
                const cumple = new Date(referencia.getFullYear(), nac.getMonth(), nac.getDate());
                if (referencia < cumple) edad -= 1;
                if (edad >= 12) { adultos++; pagantes++; }
                else if (edad >= 5) { ninos++; pagantes++; }
            } else {
                adultos++;
                pagantes++;
            }
        });
        const cantAdultosInput = parseInt(root.querySelector('[data-cantidad-adultos]')?.value, 10);
        const cantNinosInput = parseInt(root.querySelector('[data-cantidad-ninos]')?.value, 10);
        const cantPaxInput = parseInt(root.querySelector('[data-cantidad-pax]')?.value, 10);

        if (paxItems.length === 0) {
            const adt = !isNaN(cantAdultosInput) && cantAdultosInput > 0 ? cantAdultosInput : (!isNaN(cantPaxInput) && cantPaxInput > 0 ? cantPaxInput : 1);
            const chd = !isNaN(cantNinosInput) && cantNinosInput >= 0 ? cantNinosInput : 0;
            adultos = adt;
            ninos = chd;
            pagantes = adt + chd;
        } else if (pagantes <= 0) {
            pagantes = Math.max(1, (!isNaN(cantPaxInput) && cantPaxInput > 0) ? cantPaxInput : (paxItems.length || 1));
        }

        // Servicios del itinerario
        let totalGrupales = 0;
        let totalIndividuales = 0;
        let numServicios = 0;
        const svcItems = [...root.querySelectorAll('[data-svc-item]')].filter((el) => !el.closest('template'));

        svcItems.forEach((item) => {
            // Ignorar servicios automáticos de estadía de hotel para no duplicar
            if (item.querySelector('.svc-row__de-estadia') || item.querySelector('.svc-row__estadia-bar')) return;

            const estado = item.querySelector('[data-svc-estado]')?.value;
            if (estado === 'cancelado') return;

            const estimadoInput = item.querySelector('input[name*="[costo_estimado]"]');
            const realInput = item.querySelector('input[name*="[costo]"]');
            const moneda = item.querySelector('select[name*="[moneda]"]')?.value || 'USD';
            const por = item.querySelector('[name*="[costo_por]"]')?.value || 'persona';

            let valor = parseFloat(estimadoInput?.value);
            if (isNaN(valor) || valor === null || valor === undefined) {
                valor = parseFloat(realInput?.value) || 0;
            }

            if (valor > 0) {
                const valorConvertido = toMonedaPrograma(valor, moneda);
                if (por === 'persona') {
                    totalIndividuales += valorConvertido;
                } else {
                    totalGrupales += valorConvertido;
                }
                numServicios++;
            }
        });

        // Alojamiento (Hoteles)
        let totalHoteles = 0;
        let totalNoches = 0;
        const estadiaItems = [...root.querySelectorAll('[data-estadia-item]')].filter((el) => !el.closest('template'));

        estadiaItems.forEach((item) => {
            const reservadoCli = item.querySelector('[data-estadia-reservado-switch]')?.checked;
            if (reservadoCli) return;

            const cant = parseInt(item.querySelector('[data-estadia-cantidad]')?.value, 10) || 0;
            const costoNoche = parseFloat(item.querySelector('[data-estadia-costo]')?.value) || 0;
            const moneda = item.querySelector('[data-estadia-moneda]')?.value || 'USD';
            const ingreso = item.querySelector('[data-estadia-ingreso]')?.value;
            const salida = item.querySelector('[data-estadia-salida]')?.value;

            if (ingreso && salida && salida > ingreso && costoNoche > 0 && cant > 0) {
                const noches = Math.round((new Date(salida + 'T00:00:00') - new Date(ingreso + 'T00:00:00')) / 86400000);
                if (noches > 0) {
                    const totalEstadia = toMonedaPrograma(costoNoche * cant * noches, moneda);
                    totalHoteles += totalEstadia;
                    totalNoches += noches;
                }
            }
        });

        const costoGrupalesPorPax = pagantes > 0 ? (totalGrupales / pagantes) : totalGrupales;
        const costoIndividualesPorPax = totalIndividuales;
        const costoHotelesPorPax = pagantes > 0 ? (totalHoteles / pagantes) : totalHoteles;
        const costoNetoTotalPorPax = costoGrupalesPorPax + costoIndividualesPorPax + costoHotelesPorPax;
        const totalServicios = totalGrupales + (totalIndividuales * pagantes);
        const totalPaquete = totalServicios + totalHoteles;

        const margenPct = parseFloat(root.querySelector('[data-ganancia]')?.value) || 0;
        const gananciaPax = costoNetoTotalPorPax * (margenPct / 100);
        const precioVentaSugerido = costoNetoTotalPorPax + gananciaPax;
        const gananciaTotalGrupo = gananciaPax * pagantes;

        return {
            monedaPrograma,
            simbolo,
            moneyFmt,
            totalGrupales,
            costoGrupalesPorPax,
            totalIndividuales,
            costoIndividualesPorPax,
            totalHoteles,
            costoHotelesPorPax,
            costoNetoTotalPorPax: Math.round(costoNetoTotalPorPax * 100) / 100,
            costoPorPax: Math.round(costoNetoTotalPorPax * 100) / 100,
            totalServicios,
            numServicios,
            totalNoches,
            totalPaquete,
            pagantes,
            adultos,
            ninos,
            margenPct,
            gananciaPax,
            precioVentaSugerido,
            gananciaTotalGrupo,
        };
    };

    const actualizarLive = () => {
        const res = calcular();
        const m = res.moneyFmt;

        // Actualizar etiquetas de moneda en la UI
        root.querySelectorAll('[data-currency-symbol]').forEach((el) => {
            el.textContent = res.monedaPrograma === 'PEN' ? 'S/' : 'US$';
        });
        root.querySelectorAll('[data-currency-name]').forEach((el) => {
            el.textContent = `${res.monedaPrograma} por persona`;
        });

        // Actualizar tarjetas de la Hoja de Cotización Interna
        const elGrupales = root.querySelector('[data-cost-grupales]');
        const elGrupalesPax = root.querySelector('[data-cost-grupales-pax]');
        const elIndivPax = root.querySelector('[data-cost-individuales-pax]');
        const elHoteles = root.querySelector('[data-cost-hoteles]');
        const elHotelesPax = root.querySelector('[data-cost-hoteles-pax]');
        const elNetoTotal = root.querySelector('[data-cost-neto-total]');
        const elPaxLabel = root.querySelector('[data-pax-count-label]');
        const elGananciaPax = root.querySelector('[data-ganancia-pax]');
        const elGananciaTotal = root.querySelector('[data-ganancia-total]');
        const elVentaSugerido = root.querySelector('[data-precio-venta-sugerido]');

        if (elGrupales) elGrupales.textContent = m(res.totalGrupales);
        if (elGrupalesPax) elGrupalesPax.textContent = m(res.costoGrupalesPorPax);
        if (elIndivPax) elIndivPax.textContent = m(res.costoIndividualesPorPax);
        if (elHoteles) elHoteles.textContent = m(res.totalHoteles);
        if (elHotelesPax) elHotelesPax.textContent = m(res.costoHotelesPorPax);
        if (elNetoTotal) elNetoTotal.textContent = m(res.costoNetoTotalPorPax);
        if (elPaxLabel) elPaxLabel.textContent = `Prorrateado para ${res.pagantes} ${res.pagantes === 1 ? 'pasajero' : 'pasajeros'}`;
        if (elGananciaPax) elGananciaPax.textContent = `+${m(res.gananciaPax)}`;
        if (elGananciaTotal) elGananciaTotal.textContent = m(res.gananciaTotalGrupo);
        if (elVentaSugerido) elVentaSugerido.textContent = m(res.precioVentaSugerido);

        // Retrocompatibilidad con alerta superior si existe
        if (liveTotal) liveTotal.textContent = m(res.costoPorPax);
        if (liveText) {
            if (res.numServicios === 0 && res.totalNoches === 0) {
                liveText.innerHTML = '<span class="text-muted">Aún no hay servicios ni hoteles con costo asignado en el itinerario.</span>';
            } else {
                liveText.innerHTML = `<strong>${res.numServicios}</strong> servicios (${m(res.totalServicios)}) + <strong>${res.totalNoches}</strong> noches de hotel (${m(res.totalHoteles)}) para <strong>${res.pagantes}</strong> ${res.pagantes === 1 ? 'pasajero' : 'pasajeros'}.`;
            }
        }
    };

    // Botones rápidos de margen de ganancia
    root.querySelectorAll('.btn-margen-preset').forEach((btnMargen) => {
        btnMargen.addEventListener('click', () => {
            const pct = btnMargen.dataset.pct;
            const gananciaInput = root.querySelector('[data-ganancia]');
            if (gananciaInput) {
                gananciaInput.value = pct;
                gananciaInput.dispatchEvent(new Event('input', { bubbles: true }));
                gananciaInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
            root.querySelectorAll('.btn-margen-preset').forEach((b) => {
                b.classList.remove('btn-warning');
                b.classList.add('btn-outline-warning');
            });
            btnMargen.classList.remove('btn-outline-warning');
            btnMargen.classList.add('btn-warning');
            actualizarLive();
        });
    });

    // Botón aplicar a precios oficiales
    const aplicarPrecios = () => {
        const res = calcular();
        if (adultoInput) {
            adultoInput.value = res.costoNetoTotalPorPax.toFixed(2);
            adultoInput.dispatchEvent(new Event('input', { bubbles: true }));
            adultoInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (ninoInput && res.ninos > 0 && (!parseFloat(ninoInput.value) || parseFloat(ninoInput.value) === 0)) {
            ninoInput.value = (Math.round(res.costoNetoTotalPorPax * 0.75 * 100) / 100).toFixed(2);
            ninoInput.dispatchEvent(new Event('input', { bubbles: true }));
            ninoInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (recomputeBudget) recomputeBudget();

        if (window.Swal) {
            window.Swal.fire({
                icon: 'success',
                title: '¡Tarifas oficiales aplicadas!',
                html: `
                    <p class="mb-2">Se fijó el costo base en <strong>${money(res.costoNetoTotalPorPax)}</strong>.</p>
                    <div class="small text-muted border-top pt-2">
                        <div>• Precio venta al pasajero: <strong>${money(res.precioVentaSugerido)}</strong> (${res.margenPct}% margen)</div>
                        <div>• Ganancia neta para la agencia: <strong>${money(res.gananciaTotalGrupo)}</strong> (${res.pagantes} pasajeros)</div>
                    </div>
                `,
                timer: 3500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        }
    };

    btnAplicar?.addEventListener('click', aplicarPrecios);

    btn?.addEventListener('click', () => {
        const res = calcular();
        if (res.numServicios === 0 && res.totalNoches === 0) {
            const msg = 'No se encontraron servicios ni noches de hotel con precio en el itinerario. Asigna precios en la pestaña Itinerario o Alojamiento.';
            if (window.Swal) {
                window.Swal.fire({ icon: 'info', title: 'Sin costos en itinerario', text: msg, confirmButtonColor: '#0f172a' });
            } else {
                alert(msg);
            }
            return;
        }

        if (window.Swal) {
            window.Swal.fire({
                title: '¿Aplicar cálculo a la cotización?',
                html: `
                    <div class="text-start fs-6">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span>Servicios grupales (${res.numServicios}):</span>
                            <strong>${money(res.totalGrupalesUsd)}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span>Servicios por pax:</span>
                            <strong>${money(res.totalIndividualesUsd)}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span>Hoteles (${res.totalNoches} noches):</span>
                            <strong>${money(res.totalHotelesUsd)}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 text-primary fw-bold fs-5">
                            <span>Costo neto base por adulto:</span>
                            <span>${money(res.costoNetoTotalPorPax)}</span>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Se actualizará el costo base y la venta estimada con tu margen actual.</small>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b48c58',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-check me-1"></i> Sí, aplicar a la cotización',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) aplicarPrecios();
            });
        } else {
            aplicarPrecios();
        }
    });

    // Actualizar live al cambiar precios de servicios, alojamiento, pasajeros, ganancia, moneda o tipo de cambio
    root.addEventListener('input', (e) => {
        if (e.target.matches('[data-svc-estimado], [data-svc-costo], [data-estadia-costo], [data-estadia-cantidad], [data-ganancia], [data-cantidad-pax], [data-cantidad-adultos], [data-cantidad-ninos], input[name="cantidad_pax"], input[name="cantidad_adultos"], input[name="cantidad_ninos"], input[name="tipo_cambio"]')) {
            actualizarLive();
        }
    });
    root.addEventListener('change', (e) => {
        if (e.target.matches('[data-cantidad-pax], [data-cantidad-adultos], [data-cantidad-ninos], input[name="cantidad_pax"], input[name="cantidad_adultos"], input[name="cantidad_ninos"], [data-svc-por]')) {
            actualizarLive();
        }
    });
    root.addEventListener('change', (e) => {
        if (e.target.matches('[data-svc-moneda], [data-svc-por], [data-svc-estado], [data-estadia-moneda], [data-estadia-ingreso], [data-estadia-salida], [data-estadia-reservado-switch], [data-ganancia], [data-programa-moneda]')) {
            actualizarLive();
        }
    });

    actualizarLive();
}

export function initProgramaForm() {
    const root = document.querySelector('[data-programa-form]');
    if (!root) return;

    const recompute = initBudget(root);
    initTabs(root);
    initPassengers(root, recompute);
    initEstadias(root);
    initItinerario(root);
    initNuevoHotelModal(root);
    initClientePicker(root);
    initDuration(root);
    initFlightControls(root);
    initDirtyGuard(root);
    initNotificaciones(root);
    initCotizacionCalculator(root, recompute);
    recompute();
}
