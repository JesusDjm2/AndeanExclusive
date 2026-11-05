/**
 * Andean Exclusive Tours - Background Video Controller for Authentication
 * Carga bajo demanda (on-demand) para garantizar acceso instantáneo (0ms de retraso) al login.
 * Video Oficial: https://www.youtube.com/watch?v=ARdGg1ivACM
 * Segmento Cinemático: 2:20 (140s) al 3:50 (230s) en bucle continuo
 */

export function initAuthVideo() {
    const videoWrap = document.getElementById('auth-video-wrap');
    const playerEl = document.getElementById('auth-yt-player');
    const activateBtn = document.getElementById('auth-video-activate');
    const playbackGroup = document.getElementById('auth-video-playback-group');
    const toggleBtn = document.getElementById('auth-video-toggle');
    const toggleIcon = document.getElementById('auth-video-icon');
    const audioBtn = document.getElementById('auth-audio-toggle');
    const audioIcon = document.getElementById('auth-audio-icon');
    const closeBtn = document.getElementById('auth-video-close');

    if (!videoWrap || !playerEl || !activateBtn) return;

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
    let isScriptLoaded = false;
    let isLoading = false;

    function onPlayerReady(event) {
        player = event.target;
        try {
            player.mute();
            player.seekTo(START_SEC);
            player.playVideo();
        } catch (e) {
            console.debug('Auth video autoplay ready', e);
        }

        setTimeout(() => {
            document.body.classList.add('video-active');
            isLoading = false;
            if (activateBtn) activateBtn.classList.add('d-none');
            if (playbackGroup) playbackGroup.classList.remove('d-none');
        }, 500);

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
            isLoading = false;
            if (activateBtn) activateBtn.classList.add('d-none');
            if (playbackGroup) playbackGroup.classList.remove('d-none');
        }
    }

    function createPlayer() {
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
            isLoading = false;
            resetActivateButton();
        }
    }

    function resetActivateButton() {
        const icon = document.getElementById('auth-video-play-icon');
        const text = document.getElementById('auth-video-play-text');
        if (icon) icon.className = 'fas fa-play me-1';
        if (text) text.textContent = 'Video de fondo';
    }

    function loadYouTubeApiAndPlay() {
        if (isLoading) return;
        isLoading = true;

        const icon = document.getElementById('auth-video-play-icon');
        const text = document.getElementById('auth-video-play-text');
        if (icon) icon.className = 'fas fa-spinner fa-spin me-1';
        if (text) text.textContent = 'Cargando video...';

        if (window.YT && window.YT.Player) {
            if (player) {
                player.seekTo(START_SEC);
                player.playVideo();
                document.body.classList.add('video-active');
                if (activateBtn) activateBtn.classList.add('d-none');
                if (playbackGroup) playbackGroup.classList.remove('d-none');
                isLoading = false;
            } else {
                createPlayer();
            }
            return;
        }

        if (!isScriptLoaded) {
            isScriptLoaded = true;
            const prevCallback = window.onYouTubeIframeAPIReady;
            window.onYouTubeIframeAPIReady = function() {
                if (typeof prevCallback === 'function') prevCallback();
                createPlayer();
            };

            const tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            tag.async = true;
            document.head.appendChild(tag);
        }
    }

    function returnToPhoto() {
        if (player && typeof player.pauseVideo === 'function') {
            player.pauseVideo();
        }
        document.body.classList.remove('video-active');
        if (playbackGroup) playbackGroup.classList.add('d-none');
        if (activateBtn) {
            activateBtn.classList.remove('d-none');
            resetActivateButton();
        }
        isPlaying = false;
        if (toggleIcon) toggleIcon.className = 'fas fa-play';
        if (toggleBtn) toggleBtn.setAttribute('title', 'Reanudar video');
    }

    activateBtn.addEventListener('click', loadYouTubeApiAndPlay);

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

    if (closeBtn) {
        closeBtn.addEventListener('click', returnToPhoto);
    }
}
