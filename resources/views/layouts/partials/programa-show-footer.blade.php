{{-- Pie compacto del itinerario del cliente y del formulario de datos de viajeros (ES/EN) --}}
@php
    $isEn = ($locale ?? 'es') === 'en';
    $homeUrl = $isEn ? route('index') : route('inicio');
@endphp
<footer class="itin-footer" role="contentinfo">
    <div class="container itin-footer__inner">
        <div class="itin-footer__brand">
            <a href="{{ $homeUrl }}" target="_blank" rel="noopener noreferrer" class="itin-footer__logo-link" title="Andean Exclusive Tours">
                <img src="{{ asset('img/Logo-blanco-andean.png') }}" class="itin-footer__logo" alt="Andean Exclusive Tours" width="120" height="32" loading="lazy" decoding="async">
            </a>
            <span class="itin-footer__divider" aria-hidden="true">|</span>
            <span class="itin-footer__tagline">{{ $isEn ? 'Luxury travel in Peru' : 'Viajes de lujo en Perú' }}</span>
        </div>

        <div class="itin-footer__contact">
            <a href="mailto:operaciones@andeanexclusive.com" class="itin-footer__link" title="{{ $isEn ? 'Send us an email' : 'Envíanos un correo' }}">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                <span>operaciones@andeanexclusive.com</span>
            </a>
            <span class="itin-footer__dot" aria-hidden="true">·</span>
            <a href="https://wa.me/51979721194" target="_blank" rel="noopener noreferrer" class="itin-footer__link itin-footer__link--wa" title="WhatsApp">
                <i class="fa fa-whatsapp" aria-hidden="true"></i>
                <span>+51 979 721 194</span>
            </a>
        </div>

        <div class="itin-footer__legal">
            <span class="itin-footer__copy">© {{ date('Y') }} Andean Exclusive Tours</span>
            <span class="itin-footer__dot" aria-hidden="true">·</span>
            <a href="https://www.facebook.com/DjmWebMaster/" target="_blank" rel="noopener noreferrer" class="itin-footer__credit" title="DJM2">DJM2</a>
            <span class="itin-footer__dot" aria-hidden="true">·</span>
            @auth
                <a href="{{ route('home') }}" class="itin-footer__login-link" rel="nofollow" title="{{ $isEn ? 'Management panel' : 'Panel de gestión' }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="itin-footer__login-link" rel="nofollow" aria-label="{{ $isEn ? 'Staff login' : 'Acceso al panel' }}" title="{{ $isEn ? 'Staff login' : 'Acceso al panel' }}">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </a>
            @endauth
        </div>
    </div>
</footer>
