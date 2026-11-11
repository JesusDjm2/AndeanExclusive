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

    // Exponer función para que el reproductor de video u otros módulos indiquen
    // cuando su contenido específico ya está listo y reproduciéndose
    window.aeDismissPreloader = hidePreloader;

    const isAuthPage = document.body.classList.contains('auth-page');
    const hasHeroVideo = !!document.querySelector('.hero[data-hero-video-id]');

    if (isAuthPage || hasHeroVideo) {
        // En la pantalla de login/auth o en la landing page con video hero:
        // el preloader se mantiene visible hasta que el video empiece a reproducirse
        // activamente (onPlayerStateChange == PLAYING) y se llame a aeDismissPreloader.
        // Timeout de seguridad de 7s por si hay mala conexión o bloqueador de anuncios.
        setTimeout(hidePreloader, 7000);
    } else {
        // En las vistas normales sin video de fondo:
        if (document.readyState === 'complete') {
            setTimeout(hidePreloader, 350);
        } else {
            window.addEventListener('load', () => {
                setTimeout(hidePreloader, 350);
            });
        }

        // Timeout de seguridad de 1.8 segundos: jamás bloquear al usuario si una librería externa demora
        setTimeout(hidePreloader, 1800);
    }
}
