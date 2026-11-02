{{--
    Footer público unificado ES/EN. Reemplaza el footer duplicado inline en
    general-es.blade.php/general-en.blade.php y los duplicados foot(-castellano)/foot-blog(-castellano).

    @param string $locale 'es'|'en'
--}}
@php
    $t = $locale === 'es'
        ? [
            'query' => 'Consulta', 'about' => 'Nosotros', 'testimonials' => 'Testimonios',
            'terms' => 'Términos & Condiciones', 'faqs' => 'Preguntas frecuentes',
            'contact' => 'Contacto', 'touch' => 'Contáctenos',
            'tagline' => 'Viajes de lujo, hechos exclusivamente para ti: los mejores tours y experiencias en el Perú, a tu medida.',
            'cta' => 'Planifica tu viaje con nuestros expertos.',
            'rights' => 'Todos los derechos reservados',
        ]
        : [
            'query' => 'Query', 'about' => 'About Us', 'testimonials' => 'Testimonials',
            'terms' => 'Terms & Conditions', 'faqs' => 'FAQs',
            'contact' => 'Contact', 'touch' => 'Get in Touch',
            'tagline' => 'Luxury travel, made exclusively for you — the finest tours and experiences in Peru, tailored to you.',
            'cta' => 'Plan your trip with our travel experts.',
            'rights' => 'All Rights Reserved',
        ];
    $homeRoute = $locale === 'es' ? 'inicio' : 'index';
    $aboutRoute = $locale === 'es' ? 'nosotros' : 'about';
    $testimonialsRoute = $locale === 'es' ? 'testimonios' : 'testimonials';
    $termsRoute = $locale === 'es' ? 'terminos' : 'terms';
    $faqsRoute = $locale === 'es' ? 'preguntas-frecuentes' : 'faqs';
@endphp
<footer class="site-footer">
    <div class="site-footer__grid">
        <div class="site-footer__brand">
            <a href="{{ route($homeRoute) }}" class="site-footer__logo">
                <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="Andean Exclusive Tours" loading="lazy">
            </a>
            <p class="site-footer__desc">{{ $t['tagline'] }}</p>
            <div class="site-footer__social">
                <a href="https://www.facebook.com/AndeanExclusiveTours" target="_blank" rel="nofollow noopener" aria-label="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href="https://twitter.com/AndeanExclusive" target="_blank" rel="nofollow noopener" aria-label="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="https://n9.cl/cjx4" target="_blank" rel="nofollow noopener" aria-label="TripAdvisor"><i class="fa fa-tripadvisor" aria-hidden="true"></i></a>
                <a href="https://www.instagram.com/andean.exclusive/" target="_blank" rel="nofollow noopener" aria-label="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                <a href="https://www.pinterest.com/andeanexclusive/" target="_blank" rel="nofollow noopener" aria-label="Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
            </div>
        </div>
        <div>
            <h5 class="site-footer__title">{{ $t['query'] }}</h5>
            <ul class="site-footer__links">
                <li><a href="{{ route($aboutRoute) }}">{{ $t['about'] }}</a></li>
                <li><a href="{{ route($testimonialsRoute) }}">{{ $t['testimonials'] }}</a></li>
                <li><a href="{{ route($termsRoute) }}">{{ $t['terms'] }}</a></li>
                <li><a href="{{ route($faqsRoute) }}">{{ $t['faqs'] }}</a></li>
            </ul>
        </div>
        <div>
            <h5 class="site-footer__title">{{ $t['contact'] }}</h5>
            <div class="site-footer__contact">
                <a href="mailto:operaciones@andeanexclusive.com"><i class="fa fa-envelope" aria-hidden="true"></i>operaciones@andeanexclusive.com</a>
                <a href="https://wa.me/51979721194" target="_blank" rel="noopener"><i class="fa fa-whatsapp" aria-hidden="true"></i>+51 979 721 194</a>
                <span><i class="fa fa-phone" aria-hidden="true"></i>+51 084 242791</span>
                <span><i class="fa fa-map-marker" aria-hidden="true"></i>Balconcillo Alto c-6 Cusco - Perú</span>
            </div>
        </div>
        <div>
            <h5 class="site-footer__title">{{ $t['touch'] }}</h5>
            <p class="site-footer__hint">{{ $t['cta'] }}</p>
            <div class="site-footer__cta">
                <a href="mailto:operaciones@andeanexclusive.com" class="btn-brand btn-brand--primary btn-brand--sm" data-contact-open>{{ $t['contact'] }}</a>
            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <p class="mb-0">
            Copyright &copy; {{ date('Y') }} Andean Exclusive Tours | {{ $t['rights'] }} | Diseño:
            <a href="https://www.facebook.com/DjmWebMaster" target="_blank" rel="nofollow noopener">DJM2</a>
        </p>
    </div>
</footer>
