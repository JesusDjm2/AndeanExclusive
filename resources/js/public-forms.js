// Formularios públicos: errores junto a cada campo, botones de viajeros, fechas enlazadas y envío sin doble clic.

function mensajeDe(form, campo) {
    if (campo.validity.valueMissing) return form.dataset.msgRequerido;
    if (campo.validity.typeMismatch) return form.dataset.msgCorreo;
    return campo.validationMessage;
}

function marcar(campo, mensaje) {
    const contenedor = campo.closest('[data-aet-field]');
    const error = contenedor?.querySelector('[data-aet-error]');
    if (!error) return;

    contenedor.classList.toggle('is-invalid', Boolean(mensaje));
    error.hidden = !mensaje;
    error.querySelector('[data-aet-error-texto]').textContent = mensaje || '';
    if (mensaje) {
        campo.setAttribute('aria-invalid', 'true');
        if (!campo.getAttribute('aria-describedby')?.includes(error.id)) {
            campo.setAttribute('aria-describedby', `${campo.getAttribute('aria-describedby') ?? ''} ${error.id}`.trim());
        }
    } else {
        campo.removeAttribute('aria-invalid');
    }
}

function fechasEnlazadas(form) {
    const llegada = form.querySelector('[data-aet-llegada]');
    const salida = form.querySelector('[data-aet-salida]');
    if (!llegada || !salida) return null;

    const sincronizar = () => {
        salida.min = llegada.value || llegada.min;
        if (salida.value && llegada.value && salida.value < llegada.value) salida.value = '';
    };
    llegada.addEventListener('change', sincronizar);
    sincronizar();

    return salida;
}

function initStepper(raiz) {
    const campo = raiz.querySelector('input');
    const menos = raiz.querySelector('[data-aet-menos]');
    const mas = raiz.querySelector('[data-aet-mas]');
    const min = Number(campo.min || 0);
    const max = Number(campo.max || 99);

    const actualizar = () => {
        const valor = Number(campo.value || 0);
        menos.disabled = valor <= min;
        mas.disabled = valor >= max;
    };
    const cambiar = (delta) => {
        campo.value = Math.min(max, Math.max(min, Number(campo.value || 0) + delta));
        campo.dispatchEvent(new Event('input', { bubbles: true }));
        actualizar();
    };

    // Sin JavaScript queda el campo numérico normal.
    menos.hidden = false;
    mas.hidden = false;
    raiz.classList.add('is-activo');
    menos.addEventListener('click', () => cambiar(-1));
    mas.addEventListener('click', () => cambiar(1));
    campo.addEventListener('input', actualizar);
    actualizar();
}

function initFormulario(form) {
    const campos = [...form.querySelectorAll('input:not([type=hidden]), textarea')].filter((c) => !c.closest('.aet-trampa'));
    const boton = form.querySelector('[data-aet-enviar]');
    const texto = form.querySelector('[data-aet-enviar-texto]');
    const textoOriginal = texto?.textContent;
    fechasEnlazadas(form);
    form.querySelectorAll('[data-aet-stepper]').forEach(initStepper);

    campos.forEach((campo) => {
        // No regaña mientras se escribe: valida al salir y corrige en cuanto queda bien.
        campo.addEventListener('blur', () => {
            if (campo.value !== '' || campo.closest('.is-invalid')) marcar(campo, campo.checkValidity() ? '' : mensajeDe(form, campo));
        });
        campo.addEventListener('input', () => {
            if (campo.closest('.is-invalid') && campo.checkValidity()) marcar(campo, '');
        });
    });

    form.addEventListener('submit', (evento) => {
        const invalidos = campos.filter((campo) => !campo.checkValidity());
        campos.forEach((campo) => marcar(campo, campo.checkValidity() ? '' : mensajeDe(form, campo)));
        if (invalidos.length) {
            evento.preventDefault();
            invalidos[0].focus();
            return;
        }
        if (form.dataset.enviando) {
            evento.preventDefault();
            return;
        }
        form.dataset.enviando = '1';
        boton.classList.add('is-enviando');
        boton.setAttribute('aria-busy', 'true');
        if (texto) texto.textContent = boton.dataset.aetEnviando;
    });

    // Al volver con el botón Atrás, el navegador restaura la página: el botón debe quedar utilizable.
    window.addEventListener('pageshow', () => {
        delete form.dataset.enviando;
        boton.classList.remove('is-enviando');
        boton.removeAttribute('aria-busy');
        if (texto) texto.textContent = textoOriginal;
    });
}

export function initPublicForms() {
    document.querySelectorAll('[data-aet-form]').forEach(initFormulario);

    // Al llegar a la portada con el ancla de consulta rápida (#consulta-rapida),
    // aseguramos que la página quede fija en la parte superior (0) para que
    // el título del hero y el mensaje queden perfectamente enmarcados sin meterse bajo el header.
    if (window.location.hash === '#consulta-rapida') {
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
        requestAnimationFrame(() => {
            window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
        });
    }

    const primerError = document.querySelector('[data-aet-field].is-invalid input, [data-aet-field].is-invalid textarea');
    if (primerError && !primerError.closest('[data-contact-modal]')) primerError.focus({ preventScroll: true });
}
