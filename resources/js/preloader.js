/**
 * Andean Exclusive - Luxury Preloader Controller
 * Transición suave de entrada para el panel de administración y pantalla de acceso
 */

export function initPreloader() {
    const preloader = document.getElementById('ae-preloader');
    if (!preloader) return;

    let isDismissed = false;

    function hidePreloader() {
        if (isDismissed) return;
        isDismissed = true;

        preloader.classList.add('ae-preloader--loaded');
        preloader.setAttribute('aria-hidden', 'true');

        setTimeout(() => {
            if (preloader.parentNode) {
                preloader.style.display = 'none';
            }
        }, 700);
    }

    // Exponer función para que otros módulos (como el reproductor de video de auth)
    // puedan indicar cuando el contenido específico ya está listo
    window.aeDismissPreloader = hidePreloader;

    // Si el documento ya terminó de cargar
    if (document.readyState === 'complete') {
        setTimeout(hidePreloader, 400);
    } else {
        window.addEventListener('load', () => {
            setTimeout(hidePreloader, 400);
        });
    }

    // Timeout de seguridad de 1.8 segundos: jamás bloquear al usuario si una librería externa demora
    setTimeout(hidePreloader, 1800);
}
