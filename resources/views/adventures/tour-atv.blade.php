@php
    $locale = 'es';
    $adventure = [
        'route' => 'tour-atv',
        'title' => 'Tour en Cuatrimoto a Maras y Moray',
        'crumb' => 'Cuatrimoto Maras y Moray',
        'eyebrow' => 'Aventura en ATV',
        'lead' => 'La aventura empezará desde nuestra base en Chinchero, aproximadamente 40 minutos de la ciudad del Cusco. Recorrido en cuatrimoto por Maras, Moray y el Valle Sagrado.',
        'image' => 'img/tours/atv-cusco-tours-exclusive.jpg',
        'image_alt' => 'Tour en cuatrimoto a Maras y Moray',
        'days' => 1,
        'facts' => [
            ['icon' => 'fa-clock-o', 'label' => 'Duración', 'value' => 'Full Day'],
            ['icon' => 'fa-map-marker', 'label' => 'Recorrido', 'value' => 'Cusco - Maras - Moray'],
            ['icon' => 'fa-calendar-check-o', 'label' => 'Reserva', 'value' => 'Con 10 días de anticipación'],
        ],
        'includes' => '<ul><li>Guía de aventura.</li><li>Transporte desde Cusco (ida y retorno).</li><li>Base, gasolina, lecciones de manejo, equipo (casco, guantes, lentes).</li><li>Almuerzo en Salineras.</li><li>Dulce/snack en ruta.</li><li>Fotos.</li><li>Entradas a los sitios de Maras y Moray.</li></ul>',
        'excludes' => '<ul><li>Propinas.</li></ul>',
        'bring' => null,
    ];
@endphp
@extends('tours.adventure')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Tour en Cuatrimoto (ATV) a Maras y Moray | ' . config('seo.brand'),
        'description' => 'La aventura empezará desde nuestra base en Chinchero, aproximadamente 40 minutos de la ciudad del Cusco. Recorrido en cuatrimoto por Maras, Moray y el Valle Sagrado.',
        'canonical' => route('tour-atv', [], true),
        'og_image' => 'img/tours/atv-cusco-tours-exclusive.jpg',
        'og_type' => 'article',
    ])
@endsection

@section('overview')
    <p>
        La aventura empezará desde nuestra base en <strong>Chinchero</strong>, aproximadamente 40
        minutos de la ciudad del Cusco. Recorreremos lugares turísticos del Valle Sagrado.
        Haciendo nuestra primera parada en la iglesia de Tiobamba, seguidamente visitaremos las
        <strong>Minas de sal de Maras</strong>, y por último visitaremos Moray — terrazas
        agrícolas de los Incas, que concluye nuestro tour en cuatrimoto a Maras, Moray y el Valle
        Sagrado.
    </p>
@endsection
