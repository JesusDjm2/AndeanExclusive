/**
 * Andean Exclusive Tours - Background Video Controller for Authentication
 * Video Oficial: https://www.youtube.com/watch?v=ARdGg1ivACM
 * Segmento Cinemático: 2:20 (140s) al 3:50 (230s) en bucle continuo
 */

export function initAuthVideo() {
    const videoWrap = document.getElementById('auth-video-wrap');
    const playerEl = document.getElementById('auth-yt-player');
    if (!videoWrap || !playerEl) return;

    // Respetar preferencias de accesibilidad de movimiento reducido
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const VIDEO_ID = 'ARdGg1ivACM';
    const START_SEC = 140; // 2:20
    const END_SEC = 230;   // 3:50
    let player = null;
    let isPlaying = true;
    let isMuted = true;
    let loopInterval = null;

    function onPlayerReady(event) {
        player = event.target;
        try {
            player.mute();
            player.seekTo(START_SEC);
            player.playVideo();
        } catch (e) {
            console.debug('Auth video autoplay ready', e);
        }

        // Transición cinematográfica suave del fondo
        setTimeout(() => {
            document.body.classList.add('video-active');
        }, 800);

        // Bucle exacto: si alcanza 3:50 (230s), rebobina a 2:20 (140s) sin cortes
        if (loopInterval) clearInterval(loopInterval);
        loopInterval = setInterval(() => {
            if (player && typeof player.getCurrentTime === 'function' && isPlaying) {
                const cur = player.getCurrentTime();
                if (cur >= END_SEC || cur < (START_SEC - 3)) {
                    player.seekTo(START_SEC);
                }
            }
        }, 500);
    }

    function onPlayerStateChange(event) {
        if (!window.YT) return;
        if (event.data === window.YT.PlayerState.ENDED) {
            if (player) {
                player.seekTo(START_SEC);
                player.playVideo();
            }
        } else if (event.data === window.YT.PlayerState.PLAYING) {
            document.body.classList.add('video-active');
        }
    }

    function setupPlayer() {
        if (!window.YT || !window.YT.Player) return;
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
                    origin: window.location.origin
                },
                events: {
                    onReady: onPlayerReady,
                    onStateChange: onPlayerStateChange
                }
            });
        } catch (err) {
            console.debug('Error instantiating YouTube Player', err);
        }
    }

    // Inicializar cuando el script de la API de YouTube esté listo
    if (window.YT && window.YT.Player) {
        setupPlayer();
    } else {
        const prevCallback = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = function() {
            if (typeof prevCallback === 'function') prevCallback();
            setupPlayer();
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
