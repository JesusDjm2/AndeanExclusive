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
                window.Swal.fire({ icon: 'info', title: 'Atención', text: msg, confirmButtonColor: '#078f84' });
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
                    confirmButtonColor: '#078f84'
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
    recompute();
}
