{{--
    Layout de acceso al panel (login, recuperar contraseña, verificación).
    Mismos estilos que el panel (resources/sass/admin.scss → _auth.scss).
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

<body class="auth">
    <div class="auth-shell">
        <div class="auth-visual" aria-hidden="true">
            <img src="{{ asset('img/machupicchu.webp') }}" alt="" class="auth-visual__img">
            <div class="auth-visual__content">
                <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="" class="auth-visual__logo">
                <p class="auth-visual__quote">Tradition of excellence.</p>
            </div>
        </div>

        <main class="auth-panel">
            <div class="auth-panel__inner">
                <a href="{{ route('index') }}" class="auth-panel__logo">
                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" alt="Andean Exclusive Tours" width="170" height="45">
                </a>
                @yield('contenido')
                <p class="auth-panel__back">
                    <a href="{{ route('index') }}"><i class="fas fa-arrow-left" aria-hidden="true"></i> Volver al sitio web</a>
                </p>
            </div>
        </main>
    </div>
</body>

</html>
