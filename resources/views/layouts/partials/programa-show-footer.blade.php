{{-- Pie público vista programa (show / show-en). $locale: 'es' | 'en' --}}
@php
    $isEn = ($locale ?? 'es') === 'en';
    $homeUrl = $isEn ? route('index') : route('inicio');
@endphp
<footer class="programa-show-footer" role="contentinfo">
    <div class="programa-show-footer__gradient"></div>
    <div class="container programa-show-footer__container">
        <div class="programa-show-footer__grid">
            <div class="programa-show-footer__brand">
                <a href="{{ $homeUrl }}" class="programa-show-footer__logo-link" target="_blank"
                    rel="noopener noreferrer">
                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" class="programa-show-footer__logo"
                        alt="Andean Exclusive Tours" loading="lazy" decoding="async">
                </a>
                @if ($isEn)
                    <p class="programa-show-footer__tagline">Luxury travel in Peru · Cusco-based DMC</p>
                    <p class="programa-show-footer__tagline programa-show-footer__tagline--muted">Viajes de lujo en el
                        Perú</p>
                @else
                    <p class="programa-show-footer__tagline">Viajes de lujo en el Perú · Operador en Cusco</p>
                    <p class="programa-show-footer__tagline programa-show-footer__tagline--muted">Luxury travel in Peru
                    </p>
                @endif
            </div>
            <div class="programa-show-footer__contact">
                <a href="mailto:operaciones@andeanexclusive.com" class="programa-show-footer__mail">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    operaciones@andeanexclusive.com
                </a>
                <a href="https://bit.ly/3kYXpXr" class="programa-show-footer__wa" target="_blank"
                    rel="noopener noreferrer">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                    +51 979 721 194
                </a>
                <p class="programa-show-footer__copy">
                    © {{ date('Y') }} Andean Exclusive
                    @if ($isEn)
                        <span class="programa-show-footer__dot">·</span> Design
                    @else
                        <span class="programa-show-footer__dot">·</span> Diseño
                    @endif
                    <a href="https://www.facebook.com/DjmWebMaster/" target="_blank" rel="noopener noreferrer"
                        class="programa-show-footer__djm">DJM2</a>
                </p>
            </div>
        </div>
    </div>
</footer>
