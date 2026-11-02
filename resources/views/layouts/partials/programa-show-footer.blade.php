{{-- Pie del itinerario del cliente y del formulario de datos de viajeros (ES/EN). $locale: 'es' | 'en' --}}
@php
    $isEn = ($locale ?? 'es') === 'en';
    $homeUrl = $isEn ? route('index') : route('inicio');
@endphp
<footer class="itin-footer" role="contentinfo">
    <div class="container itin-footer__grid">
        <div>
            <a href="{{ $homeUrl }}" target="_blank" rel="noopener noreferrer">
                <img src="{{ asset('img/Logo-blanco-andean.png') }}" class="itin-footer__logo" alt="Andean Exclusive Tours" width="150" height="55" loading="lazy" decoding="async">
            </a>
            <p class="itin-footer__tagline">{{ $isEn ? 'Luxury travel in Peru · Cusco-based DMC' : 'Viajes de lujo en el Perú · Operador en Cusco' }}</p>
        </div>
        <div class="itin-footer__contact">
            <a href="mailto:operaciones@andeanexclusive.com"><i class="fa fa-envelope-o" aria-hidden="true"></i> operaciones@andeanexclusive.com</a>
            <a href="https://wa.me/51979721194" target="_blank" rel="noopener noreferrer"><i class="fa fa-whatsapp" aria-hidden="true"></i> +51 979 721 194</a>
        </div>
        <p class="itin-footer__copy">
            © {{ date('Y') }} Andean Exclusive Tours · {{ $isEn ? 'Design' : 'Diseño' }}
            <a href="https://www.facebook.com/DjmWebMaster/" target="_blank" rel="noopener noreferrer">DJM2</a>
        </p>
    </div>
</footer>
