/**
 * Andean Exclusive Tours - Hero Video Controller
 * Video Oficial: Peru Travel (https://www.youtube.com/watch?v=B_8bbKn3amE)
 * Reproducción de video completo en bucle nativo continuo (sin restricciones de tiempo).
 * Totalmente silencioso, sin botones ni controles de YouTube en pantalla.
 * Exclusivo para pantallas de escritorio (PC >= 992px).
 */

export function initHeroVideo() {
    const hero = document.querySelector('.hero');
    const videoWrap = document.getElementById('hero-video-wrap');
    const playerContainer = document.getElementById('hero-yt-player');
    if (!hero || !videoWrap || !playerContainer) return;

    // Respetar preferencias de accesibilidad de movimiento reducido
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        if (typeof window.aeDismissPreloader === 'function') {
            window.aeDismissPreloader();
        }
        return;
    }

    // Activo para tablets y pantallas de escritorio (>= 768px)
    const mediaQuery = window.matchMedia('(min-width: 768px)');
    if (!mediaQuery.matches) {
        // En celulares (< 768px) no cargamos video: mantenemos la fotografía optimizada para móviles
        if (typeof window.aeDismissPreloader === 'function') {
            setTimeout(window.aeDismissPreloader, 400);
        }
        return;
    }

    const videoId = hero.dataset.heroVideoId || 'B_8bbKn3amE';

    let player = null;
    let isCreated = false;
    let isPlaying = true;

    function onPlayerReady(event) {
        player = event.target;
        try {
            player.mute();
            player.playVideo();
        } catch (e) {
            console.debug('Hero video autoplay ready', e);
        }
    }

    function onPlayerStateChange(event) {
        if (!window.YT) return;

        // Cuando YouTube confirma reproducción activa
        if (event.data === window.YT.PlayerState.PLAYING) {
            if (!hero.classList.contains('is-video-active')) {
                // Con el <iframe> incrustado directamente en el HTML del servidor,
                // el video arranca de inmediato en paralelo con la carga de la página.
                // A los 3.2s de reproducción activa, los controles iniciales de YouTube
                // ya se han auto-ocultado por completo detrás del preloader de lujo.
                setTimeout(() => {
                    hero.classList.add('is-video-active');
                    if (typeof window.aeDismissPreloader === 'function') {
                        window.aeDismissPreloader();
                    }
                }, 3200);
            }
        } else if (event.data === window.YT.PlayerState.ENDED) {
            // Bucle de respaldo continuo
            if (player && typeof player.playVideo === 'function') {
                player.playVideo();
            }
        }
    }

    function createPlayer() {
        if (isCreated || !mediaQuery.matches) return;
        const target = document.getElementById('hero-yt-player');
        if (!target) return;

        isCreated = true;
        try {
            // Vinculación directa al <iframe> ya renderizado en el HTML
            player = new window.YT.Player('hero-yt-player', {
                events: {
                    onReady: onPlayerReady,
                    onStateChange: onPlayerStateChange
                }
            });
        } catch (err) {
            console.debug('Error instantiating Hero YouTube Player', err);
            if (typeof window.aeDismissPreloader === 'function') {
                window.aeDismissPreloader();
            }
        }
    }

    // Exponer globalmente para el callback temprano de la API de YouTube
    window.aeInitHeroVideo = createPlayer;

    if (window.YT && window.YT.Player) {
        createPlayer();
    } else {
        const prevCallback = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = function() {
            if (typeof prevCallback === 'function') prevCallback();
            createPlayer();
        };
    }

    // Detectar cambios de tamaño de pantalla (PC <-> Móvil)
    mediaQuery.addEventListener('change', (e) => {
        if (e.matches) {
            if (!isCreated) {
                createPlayer();
            } else if (player && typeof player.playVideo === 'function' && isPlaying) {
                player.playVideo();
            }
        } else if (player && typeof player.pauseVideo === 'function') {
            player.pauseVideo();
        }
    });
}
