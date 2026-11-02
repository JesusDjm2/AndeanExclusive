// Campana de notificaciones: actualiza contador y lista cada minuto, sin recargar la página.
const INTERVALO = 60000;

export function initAvisos() {
    const raiz = document.querySelector('[data-avisos]');
    if (!raiz) return;

    const contador = raiz.querySelector('[data-avisos-count]');
    const lista = raiz.querySelector('[data-avisos-list]');
    const boton = raiz.querySelector('[data-avisos-btn]');
    let temporizador = null;

    const detener = () => {
        clearInterval(temporizador);
        temporizador = null;
    };

    const actualizar = async () => {
        try {
            const respuesta = await fetch(raiz.dataset.url, { headers: { Accept: 'application/json' } });
            // Sesión vencida o sin permiso: no insistir.
            if (!respuesta.ok) {
                detener();
                return;
            }
            const { noVistas, html } = await respuesta.json();
            contador.textContent = noVistas > 99 ? '99+' : noVistas;
            contador.hidden = noVistas === 0;
            boton.setAttribute('aria-label', `Notificaciones: ${noVistas} sin ver`);
            lista.innerHTML = html;
        } catch {
            // Sin conexión momentánea: se reintenta en el siguiente ciclo.
        }
    };

    const iniciar = () => {
        if (!temporizador) temporizador = setInterval(actualizar, INTERVALO);
    };

    // Pestaña oculta: no consultar. Al volver, ponerse al día de inmediato.
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            detener();
        } else {
            actualizar();
            iniciar();
        }
    });

    if (!document.hidden) iniciar();
}
