{{--
    Layout de acceso al panel (login, recuperar contraseña, verificación).
    Diseño Cinemático de Lujo con fondo de video YouTube en bucle (2:20 - 3:50) y tarjeta central glassmorphic.
--}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('titulo', 'Acceso') · Panel Andean Exclusive</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}">
    @vite(['resources/sass/admin.scss', 'resources/js/admin.js'])
</head>

<body class="auth-page">
    {{-- Pantalla de Carga y Transición Cinemática --}}
    @include('layouts.partials.preloader')

    {{-- Fondo de video con poster fotográfico de fallback y velo cinematográfico --}}
    <div class="auth-bg" aria-hidden="true">
        <img src="{{ asset('img/machupicchu.webp') }}" alt="" class="auth-bg__poster" id="auth-bg-poster">
        <div class="auth-bg__video-wrap" id="auth-video-wrap">
            <div id="auth-yt-player"></div>
        </div>
        <div class="auth-bg__overlay"></div>
    </div>

    {{-- Contenedor Central Flotante --}}
    <div class="auth-viewport">
        <main class="auth-card" role="main">
            {{-- Cabecera con Logotipo Oficial y Lema de Marca --}}
            <header class="auth-card__header">
                <a href="{{ route('index') }}" class="auth-card__brand" title="Volver al sitio oficial">
                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" alt="Andean Exclusive Tours" width="180" height="48" class="auth-card__logo">
                </a>
                <span class="auth-card__tagline">Tradition of excellence.</span>
            </header>

            {{-- Contenido de autenticación --}}
            <div class="auth-card__body">
                @yield('contenido')
            </div>

            {{-- Pie de tarjeta --}}
            <footer class="auth-card__footer">
                <a href="{{ route('index') }}" class="auth-card__back">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Volver al sitio web
                </a>
            </footer>
        </main>

        {{-- Sello de Seguridad y Prestigio --}}
        <div class="auth-badge-secure">
            <i class="fas fa-shield-alt text-teal me-1"></i>
            <span>Acceso Seguro · Andean Exclusive Tours</span>
        </div>
    </div>

    {{-- Controles flotantes del video en esquina inferior --}}
    <div class="auth-video-controls" id="auth-video-controls" aria-label="Controles del video">
        <button type="button" class="auth-video-btn" id="auth-video-toggle" title="Pausar video" aria-label="Pausar o reanudar video">
            <i class="fas fa-pause" id="auth-video-icon"></i>
        </button>
        <button type="button" class="auth-video-btn" id="auth-audio-toggle" title="Activar audio" aria-label="Silenciar o activar audio">
            <i class="fas fa-volume-mute" id="auth-audio-icon"></i>
        </button>
    </div>

    {{-- API de YouTube para control preciso del segmento y reproducción fluida --}}
    <script src="https://www.youtube.com/iframe_api"></script>
</body>

</html>
