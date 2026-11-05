/**
 * Andean Exclusive Tours - Background Video Controller for Authentication
 * Video Oficial: https://www.youtube.com/watch?v=ARdGg1ivACM
 * Segmento Cinemático: 2:20 (140s) al 3:50 (230s) en bucle continuo
 * Arranque automático sincronizado con el preloader de marca.
 */

export function initAuthVideo() {
    const videoWrap = document.getElementById('auth-video-wrap');
    const playerEl = document.getElementById('auth-yt-player');
    if (!videoWrap || !playerEl) return;

    // Respetar preferencias de accesibilidad de movimiento reducido
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        if (typeof window.aeDismissPreloader === 'function') {
            window.aeDismissPreloader();
        }
        return;
    }

    const VIDEO_ID = 'ARdGg1ivACM';
    const START_SEC = 140; // 2:20
    const END_SEC = 230;   // 3:50
    let player = null;
    let isPlaying = true;
    let isMuted = true;
    let loopInterval = null;
    let isCreated = false;

    function onPlayerReady(event) {
        player = event.target;
        try {
            player.mute();
            player.seekTo(START_SEC);
            player.playVideo();
        } catch (e) {
            console.debug('Auth video autoplay ready', e);
        }

        // Bucle exacto: si alcanza 3:50 (230s), rebobina a 2:20 (140s) sin cortes
        if (loopInterval) clearInterval(loopInterval);
        loopInterval = setInterval(() => {
            if (player && typeof player.getCurrentTime === 'function' && isPlaying) {
                const cur = player.getCurrentTime();
                if (cur >= END_SEC || cur < (START_SEC - 3)) {
                    player.seekTo(START_SEC);
                }
            }
        }, 400);
    }

    function onPlayerStateChange(event) {
        if (!window.YT) return;

        // El video ya está reproduciendo frames reales en pantalla
        if (event.data === window.YT.PlayerState.PLAYING) {
            document.body.classList.add('video-active');

            // Desvanecer el preloader suavemente para revelar el video ya en movimiento
            setTimeout(() => {
                if (typeof window.aeDismissPreloader === 'function') {
                    window.aeDismissPreloader();
                }
            }, 120);
        } else if (event.data === window.YT.PlayerState.ENDED) {
            if (player) {
                player.seekTo(START_SEC);
                player.playVideo();
            }
        }
    }

    function createPlayer() {
        if (isCreated || !window.YT || !window.YT.Player) return;
        const target = document.getElementById('auth-yt-player');
        if (!target) return;

        isCreated = true;
        try {
            player = new window.YT.Player('auth-yt-player', {
                videoId: VIDEO_ID,
                playerVars: {
                    autoplay: 1,
                    controls: 0,
                    mute: 1,
                    loop: 1,
                    playlist: VIDEO_ID,
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
            console.debug('Error instantiating YouTube Player', err);
            if (typeof window.aeDismissPreloader === 'function') {
                window.aeDismissPreloader();
            }
        }
    }

    // Exponer globalmente para el callback temprano de la API de YouTube
    window.aeInitAuthVideo = createPlayer;

    if (window.YT && window.YT.Player) {
        createPlayer();
    } else {
        const prevCallback = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = function() {
            if (typeof prevCallback === 'function') prevCallback();
            createPlayer();
        };
    }

    // Botón Pausar / Reanudar Video
    const toggleBtn = document.getElementById('auth-video-toggle');
    const toggleIcon = document.getElementById('auth-video-icon');
    if (toggleBtn && toggleIcon) {
        toggleBtn.addEventListener('click', () => {
            if (!player || typeof player.pauseVideo !== 'function') return;
            if (isPlaying) {
                player.pauseVideo();
                isPlaying = false;
                toggleIcon.className = 'fas fa-play';
                toggleBtn.setAttribute('title', 'Reanudar video');
            } else {
                player.playVideo();
                isPlaying = true;
                toggleIcon.className = 'fas fa-pause';
                toggleBtn.setAttribute('title', 'Pausar video');
            }
        });
    }

    // Botón Silenciar / Activar Audio
    const audioBtn = document.getElementById('auth-audio-toggle');
    const audioIcon = document.getElementById('auth-audio-icon');
    if (audioBtn && audioIcon) {
        audioBtn.addEventListener('click', () => {
            if (!player || typeof player.isMuted !== 'function') return;
            if (isMuted) {
                player.unMute();
                player.setVolume(60);
                isMuted = false;
                audioIcon.className = 'fas fa-volume-up';
                audioBtn.setAttribute('title', 'Silenciar audio');
            } else {
                player.mute();
                isMuted = true;
                audioIcon.className = 'fas fa-volume-mute';
                audioBtn.setAttribute('title', 'Activar audio');
            }
        });
    }
}
