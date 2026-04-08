{{-- Logo arriba en vista programa (pax). $locale: 'es' | 'en' — enlace al sitio público --}}
@php
    $isEn = ($locale ?? 'es') === 'en';
    $homeUrl = $isEn ? route('index') : route('inicio');
@endphp
<div>
    <img width="200" src="{{ asset('img/andean-exclusive-logo.png') }}" alt="Andean Exclusive Tours"
        loading="eager" decoding="async">
</div>
