{{--
    Banda de pilares de confianza y exclusividad para colecciones de tours y categorías.
    Transmite el sello boutique de Andean Exclusive (100% privado, Belmond/5★, concierge en Cusco).
    @param string $locale 'es'|'en'
--}}
@php
    $isEs = ($locale ?? 'es') === 'es';
    $pillars = $isEs
        ? [
            [
                'icon' => 'shield',
                'title' => '100% En Privado',
                'desc' => 'Guías dedicados y traslados exclusivos solo para ti y tus acompañantes.',
            ],
            [
                'icon' => 'bed',
                'title' => 'Hoteles 5★ y Trenes VIP',
                'desc' => 'Selección Belmond, Hiram Bingham, hoteles boutique y lodges con encanto.',
            ],
            [
                'icon' => 'sliders',
                'title' => 'Itinerarios a Medida',
                'desc' => 'Personalizamos cada fecha, parada y ritmo según tu estilo de viaje.',
            ],
            [
                'icon' => 'comments-o',
                'title' => 'Concierge 24/7 en Cusco',
                'desc' => 'Asistencia directa y soporte local permanente antes y durante todo tu viaje.',
            ],
        ]
        : [
            [
                'icon' => 'shield',
                'title' => '100% Private & Tailored',
                'desc' => 'Dedicated private guides and vehicle solely for you and your travel party.',
            ],
            [
                'icon' => 'bed',
                'title' => '5-Star Stays & VIP Trains',
                'desc' => 'Belmond properties, Hiram Bingham luxury train, and handpicked boutique lodges.',
            ],
            [
                'icon' => 'sliders',
                'title' => 'Fully Customizable',
                'desc' => 'We tailor every date, pace, and highlight around your personal wishlist.',
            ],
            [
                'icon' => 'comments-o',
                'title' => '24/7 Cusco Concierge',
                'desc' => 'Direct round-the-clock local support before and throughout your entire journey.',
            ],
        ];
@endphp

<section class="collection-trust-strip" aria-label="{{ $isEs ? 'Garantías de servicio de lujo' : 'Luxury service guarantees' }}">
    <div class="site-container">
        <div class="collection-trust-strip__grid">
            @foreach ($pillars as $pillar)
                <div class="collection-trust-item">
                    <div class="collection-trust-item__icon" aria-hidden="true">
                        <i class="fa fa-{{ $pillar['icon'] }}"></i>
                    </div>
                    <div class="collection-trust-item__content">
                        <h4 class="collection-trust-item__title">{{ $pillar['title'] }}</h4>
                        <p class="collection-trust-item__desc">{{ $pillar['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
