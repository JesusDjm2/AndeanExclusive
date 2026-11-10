/**
 * Andean Exclusive Tours - Hero Video Controller
 * Video Oficial: Peru Cinematic Video 4K (https://www.youtube.com/watch?v=9mS_91IgqbA)
 * Segmento Cinemático: Segundo 15 al 53 en bucle continuo.
 * Carga diferida inteligente para no penalizar el First Contentful Paint.
 * La imagen de Machu Picchu actúa como precarga hasta que el video está reproduciendo.
 * Transición cinematográfica elegante (cross-dissolve suave).
 * Activo exclusivamente en pantallas de escritorio (PC >= 992px).
 */

export function initHeroVideo() {
    const hero = document.querySelector('.hero');
    const videoWrap = document.getElementById('hero-video-wrap');
    const playerContainer = document.getElementById('hero-yt-player');
    if (!hero || !videoWrap || !playerContainer) return;

    // Respetar preferencias de accesibilidad de movimiento reducido
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (motionQuery.matches) return;

    // Solo para tamaños de PC (escritorio >= 992px)
    const mediaQuery = window.matchMedia('(min-width: 992px)');
    const videoId = hero.dataset.heroVideoId || '9mS_91IgqbA';

    // Segmento solicitado: segundo 15 al 53
    const START_SEC = 15;
    const END_SEC = 53;

    let player = null;
    let isCreated = false;
    let isPlaying = true;
    let isMuted = true;
    let loopInterval = null;

    // Carga asíncrona de la API de YouTube sin bloquear el renderizado inicial
    function loadYouTubeAPI(onReady) {
        if (window.YT && window.YT.Player) {
            onReady();
            return;
        }

        const prevCallback = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = function() {
            if (typeof prevCallback === 'function') prevCallback();
            onReady();
        };

        if (!document.querySelector('script[src*="youtube.com/iframe_api"]')) {
            const script = document.createElement('script');
            script.src = 'https://www.youtube.com/iframe_api';
            script.async = true;
            document.head.appendChild(script);
        }
    }

    function onPlayerReady(event) {
        player = event.target;
        try {
            player.mute();
            player.seekTo(START_SEC);
            player.playVideo();
        } catch (e) {
            console.debug('Hero video autoplay ready', e);
        }

        // Bucle continuo del segmento: 15s a 53s
        if (loopInterval) clearInterval(loopInterval);
        loopInterval = setInterval(() => {
            if (player && typeof player.getCurrentTime === 'function' && isPlaying) {
                const cur = player.getCurrentTime();
                if (cur >= END_SEC || cur < (START_SEC - 2)) {
                    player.seekTo(START_SEC);
                }
            }
        }, 250);
    }

    function onPlayerStateChange(event) {
        if (!window.YT) return;

        // Cuando el video ya está emitiendo fotogramas reales
        if (event.data === window.YT.PlayerState.PLAYING) {
            // Breve margen (150ms) para garantizar que los fotogramas del segundo 15 están listos
            setTimeout(() => {
                hero.classList.add('is-video-active');
                const controls = document.getElementById('hero-video-controls');
                if (controls) controls.hidden = false;
            }, 150);
        } else if (event.data === window.YT.PlayerState.ENDED) {
            if (player && typeof player.seekTo === 'function') {
                player.seekTo(START_SEC);
                player.playVideo();
            }
        }
    }

    function createPlayer() {
        if (isCreated || !mediaQuery.matches || motionQuery.matches) return;

        loadYouTubeAPI(() => {
            if (isCreated || !window.YT || !window.YT.Player) return;
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
                        start: START_SEC,
                        end: END_SEC,
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
            }
        });
    }

    // Carga diferida: esperar a que la página termine de pintar su contenido crítico
    function scheduleInit() {
        if (!mediaQuery.matches || motionQuery.matches) return;

        if ('requestIdleCallback' in window) {
            window.requestIdleCallback(() => createPlayer(), { timeout: 2500 });
        } else {
            setTimeout(createPlayer, 800);
        }
    }

    if (document.readyState === 'complete') {
        scheduleInit();
    } else {
        window.addEventListener('load', scheduleInit, { once: true });
    }

    // Botón Pausar / Reanudar Video
    const toggleBtn = document.getElementById('hero-video-toggle');
    const toggleIcon = document.getElementById('hero-video-icon');
    if (toggleBtn && toggleIcon) {
        toggleBtn.addEventListener('click', () => {
            if (!player || typeof player.pauseVideo !== 'function') return;
            if (isPlaying) {
                player.pauseVideo();
                isPlaying = false;
                toggleIcon.className = 'fa fa-play';
                toggleBtn.setAttribute('title', toggleBtn.dataset.labelPlay || 'Reanudar video');
            } else {
                player.playVideo();
                isPlaying = true;
                toggleIcon.className = 'fa fa-pause';
                toggleBtn.setAttribute('title', toggleBtn.dataset.labelPause || 'Pausar video');
            }
        });
    }

    // Botón Silenciar / Activar Audio
    const audioBtn = document.getElementById('hero-audio-toggle');
    const audioIcon = document.getElementById('hero-audio-icon');
    if (audioBtn && audioIcon) {
        audioBtn.addEventListener('click', () => {
            if (!player || typeof player.isMuted !== 'function') return;
            if (isMuted) {
                player.unMute();
                player.setVolume(50);
                isMuted = false;
                audioIcon.className = 'fa fa-volume-up';
                audioBtn.setAttribute('title', audioBtn.dataset.labelMute || 'Silenciar audio');
            } else {
                player.mute();
                isMuted = true;
                audioIcon.className = 'fa fa-volume-off';
                audioBtn.setAttribute('title', audioBtn.dataset.labelUnmute || 'Activar audio');
            }
        });
    }

    // Pausar reproducción cuando el hero no está a la vista (ahorro de recursos de GPU/batería)
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!player || typeof player.pauseVideo !== 'function') return;
                if (!entry.isIntersecting) {
                    if (isPlaying) player.pauseVideo();
                } else if (isPlaying) {
                    player.playVideo();
                }
            });
        }, { threshold: 0.1 });

        observer.observe(hero);
    }

    // Pausar si la pestaña pasa a segundo plano
    document.addEventListener('visibilitychange', () => {
        if (!player || typeof player.pauseVideo !== 'function') return;
        if (document.hidden) {
            player.pauseVideo();
        } else if (isPlaying && mediaQuery.matches) {
            player.playVideo();
        }
    });

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
