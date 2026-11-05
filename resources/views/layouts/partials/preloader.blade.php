{{--
    Andean Exclusive - Pantalla de Carga y Transición Cinemática (Senior UI/UX)
    Logotipo oficial, eslogan de marca y animación fluida para login y dashboard
--}}
<div id="ae-preloader" class="ae-preloader" aria-hidden="false" role="status" aria-label="Cargando panel de gestión Andean Exclusive">
    <div class="ae-preloader__backdrop" aria-hidden="true"></div>
    <div class="ae-preloader__content">
        {{-- Logotipo Principal con resplandor sutil --}}
        <div class="ae-preloader__logo-box">
            <div class="ae-preloader__glow" aria-hidden="true"></div>
            <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="Andean Exclusive Tours" class="ae-preloader__logo" width="220" height="60">
        </div>

        {{-- Eslogan de Marca --}}
        <div class="ae-preloader__slogan">
            Tradition of excellence.
        </div>

        {{-- Barra de Progreso de Alta Gama (Teal Andino y Oro Cálido) --}}
        <div class="ae-preloader__track" aria-hidden="true">
            <div class="ae-preloader__indicator"></div>
        </div>

        {{-- Micro-indicador de estado --}}
        <div class="ae-preloader__status">
            <span class="ae-preloader__status-dot"></span>
            <span class="ae-preloader__status-text">Cargando experiencia exclusiva</span>
        </div>
    </div>
</div>
