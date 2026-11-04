// Gestión de servicios con proveedores (servicios/partials/acciones + modales): estado/confirmación, pago e historial.
// También la selección en lote de la bandeja de Reservas.

const fmt = (simbolo, n) => `${simbolo} ${Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

function initEstado(bootstrap, el) {
    const modal = bootstrap.Modal.getOrCreateInstance(el);
    const form = el.querySelector('[data-svc-form="estado"]');
    const conf = el.querySelector('[data-svc-confirmacion]');
    const confCampo = el.querySelector('[data-svc-confirmacion-campo]');

    // El n.º de confirmación es obligatorio solo al confirmar.
    const sync = () => {
        const estado = form.querySelector('[data-svc-estado-opcion]:checked')?.value;
        conf.required = estado === 'confirmado';
        confCampo.classList.toggle('is-required', estado === 'confirmado');
    };
    form.addEventListener('change', sync);

    return (btn) => {
        form.action = btn.dataset.url;
        form.reset();
        el.querySelectorAll('[data-svc-nombre]').forEach((n) => { n.textContent = btn.dataset.nombre; });
        conf.value = btn.dataset.confirmacion || '';
        // Sugerir el siguiente paso: por reservar → solicitado → confirmado.
        const siguiente = { pendiente: 'solicitado', solicitado: 'confirmado' }[btn.dataset.estado] ?? btn.dataset.estado;
        const radio = form.querySelector(`[data-svc-estado-opcion][value="${siguiente}"]`);
        if (radio) radio.checked = true;
        sync();
        modal.show();
        el.addEventListener('shown.bs.modal', () => (siguiente === 'confirmado' ? conf : radio)?.focus(), { once: true });
    };
}

function initAsignar(bootstrap, el) {
    const modal = bootstrap.Modal.getOrCreateInstance(el);
    const form = el.querySelector('[data-svc-form="asignar"]');
    const proveedor = el.querySelector('[data-svc-proveedor]');
    const costo = el.querySelector('[data-svc-costo-real]');
    const desvio = el.querySelector('[data-svc-desvio]');
    const desvioBox = el.querySelector('[data-svc-desvio-box]');
    let estimado = null;
    let simbolo = '$';

    // Diferencia con lo cotizado en vivo: avisa si el proveedor cobra más que el costo estimado.
    const sync = () => {
        const real = parseFloat(costo.value);
        if (estimado === null || Number.isNaN(real)) {
            desvio.textContent = '—';
            desvioBox.className = '';
            return;
        }
        const diff = real - estimado;
        desvio.textContent = (diff > 0 ? '+' : '') + fmt(simbolo, diff);
        desvioBox.className = diff > 0.009 ? 'is-due' : 'is-ok';
    };
    costo.addEventListener('input', sync);

    return (btn) => {
        form.action = btn.dataset.url;
        form.reset();
        simbolo = btn.dataset.simbolo || '$';
        estimado = btn.dataset.estimado !== '' ? parseFloat(btn.dataset.estimado) : null;
        el.querySelectorAll('[data-svc-nombre]').forEach((n) => { n.textContent = btn.dataset.nombre; });
        el.querySelector('[data-svc-estimado]').textContent = estimado === null ? 'Sin estimado' : fmt(simbolo, estimado) + (btn.dataset.por || '');
        el.querySelector('[data-svc-por]').textContent = btn.dataset.por ? `(${btn.dataset.por.trim()})` : '';
        el.querySelector('[data-svc-cambio]').hidden = !(btn.dataset.proveedor && ['solicitado', 'confirmado'].includes(btn.dataset.estado));
        el.querySelector('#svcAsignarTitle').textContent = btn.dataset.proveedor ? 'Cambiar proveedor' : 'Asignar proveedor';
        proveedor.value = btn.dataset.proveedor || '';
        el.dataset.fecha = btn.dataset.fecha || '';
        // Costo real sugerido: el que ya tenía o, si no, el estimado de la cotización.
        costo.value = btn.dataset.costo || btn.dataset.estimado || '';
        sync();
        modal.show();
        el.addEventListener('shown.bs.modal', () => proveedor.focus(), { once: true });
    };
}

function initPago(bootstrap, el) {
    const modal = bootstrap.Modal.getOrCreateInstance(el);
    const form = el.querySelector('[data-svc-form="pago"]');
    const monto = el.querySelector('[data-svc-monto]');
    let saldo = 0;

    el.querySelector('[data-svc-monto-saldo]').addEventListener('click', () => { monto.value = saldo.toFixed(2); });
    el.querySelector('[data-svc-monto-mitad]').addEventListener('click', () => { monto.value = (saldo / 2).toFixed(2); });

    return (btn) => {
        form.action = btn.dataset.url;
        form.reset();
        saldo = parseFloat(btn.dataset.saldo) || 0;
        const s = btn.dataset.simbolo;
        el.querySelectorAll('[data-svc-nombre]').forEach((n) => { n.textContent = btn.dataset.nombre; });
        el.querySelector('[data-svc-importe]').textContent = fmt(s, btn.dataset.importe);
        el.querySelector('[data-svc-pagado]').textContent = fmt(s, btn.dataset.pagado);
        el.querySelector('[data-svc-saldo]').textContent = fmt(s, saldo);
        el.querySelector('[data-svc-moneda]').textContent = `(${btn.dataset.moneda})`;
        monto.max = saldo.toFixed(2);
        monto.value = saldo.toFixed(2);
        modal.show();
        el.addEventListener('shown.bs.modal', () => monto.select(), { once: true });
    };
}

function initHistorial(bootstrap, el) {
    const modal = bootstrap.Modal.getOrCreateInstance(el);
    const body = el.querySelector('[data-svc-historial]');
    const cargando = body.innerHTML;

    return async (btn) => {
        el.querySelectorAll('[data-svc-nombre]').forEach((n) => { n.textContent = btn.dataset.nombre; });
        body.innerHTML = cargando;
        modal.show();
        try {
            const res = await fetch(btn.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error(res.status);
            body.innerHTML = await res.text();
        } catch {
            body.innerHTML = '<p class="text-danger mb-0">No se pudo cargar el historial. Intenta de nuevo.</p>';
        }
    };
}

// Selección en lote (bandeja de Reservas).
function initLote() {
    const bulk = document.querySelector('[data-res-bulk]');
    if (!bulk) return;

    const checks = [...document.querySelectorAll('[data-res-check]')];
    const all = bulk.querySelector('[data-res-all]');
    const count = bulk.querySelector('[data-res-count]');
    const apply = bulk.querySelector('[data-res-apply]');

    const sync = () => {
        const n = checks.filter((c) => c.checked).length;
        count.textContent = `${n} ${n === 1 ? 'seleccionado' : 'seleccionados'}`;
        apply.disabled = n === 0;
        all.checked = n > 0 && n === checks.length;
        all.indeterminate = n > 0 && n < checks.length;
        bulk.classList.toggle('has-selection', n > 0);
    };

    all.addEventListener('change', () => { checks.forEach((c) => { c.checked = all.checked; }); sync(); });
    checks.forEach((c) => c.addEventListener('change', sync));
    sync();
}

export function initServicioAcciones(bootstrap) {
    initLote();

    const abrir = {
        estado: document.getElementById('svcEstadoModal') && initEstado(bootstrap, document.getElementById('svcEstadoModal')),
        asignar: document.getElementById('svcAsignarModal') && initAsignar(bootstrap, document.getElementById('svcAsignarModal')),
        pago: document.getElementById('svcPagoModal') && initPago(bootstrap, document.getElementById('svcPagoModal')),
        historial: document.getElementById('svcHistorialModal') && initHistorial(bootstrap, document.getElementById('svcHistorialModal')),
    };

    document.addEventListener('click', (event) => {
        const btn = event.target.closest('[data-svc-accion]');
        if (!btn || !abrir[btn.dataset.svcAccion]) return;
        event.preventDefault();
        abrir[btn.dataset.svcAccion](btn);
    });
}
