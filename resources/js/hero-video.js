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

    // Solo para pantallas de escritorio (>= 992px)
    const mediaQuery = window.matchMedia('(min-width: 992px)');
    if (!mediaQuery.matches) {
        // En pantallas móviles (< 992px) no cargamos el video de YouTube (ahorro de datos/batería)
        // Desvanecemos el preloader de marca tras breve bienvenida para revelar la foto móvil
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
                // La interfaz de inicio de YouTube (botones de rebobinado, play y título)
                // tarda exactamente ~3.5s en auto-ocultarse por completo.
                // Mantenemos tanto el preloader como la fotografía del poster activos durante 3.8s
                // para que la disolución revele el video cuando ya esté 100% limpio y en movimiento.
                setTimeout(() => {
                    hero.classList.add('is-video-active');
                    if (typeof window.aeDismissPreloader === 'function') {
                        window.aeDismissPreloader();
                    }
                }, 3800);
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
            player = new window.YT.Player('hero-yt-player', {
                videoId: videoId,
                playerVars: {
                    autoplay: 1,
                    controls: 0,
                    mute: 1,
                    loop: 1,
                    playlist: videoId,
                    playsinline: 1,
                    rel: 0,
                    showinfo: 0,
                    modestbranding: 1,
                    disablekb: 1,
                    fs: 0,
                    iv_load_policy: 3,
                    enablejsapi: 1,
                    origin: window.location.origin,
                    widget_referrer: window.location.origin
                },
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
