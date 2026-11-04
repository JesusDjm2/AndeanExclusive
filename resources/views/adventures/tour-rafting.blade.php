@php
    $locale = 'es';
    $adventure = [
        'route' => 'tour-rafting',
        'title' => 'Rafting en Cusco',
        'crumb' => 'Rafting',
        'eyebrow' => 'Aventura en el río',
        'lead' => 'Rafting en Cusco, uno de los mejores ríos para el canotaje en el Sur del Perú, cerca de las montañas alrededor de Cusco.',
        'image' => 'img/tours/canotaje-tour-cusco.jpg',
        'image_alt' => 'Rafting en Cusco',
        'days' => 1,
        'facts' => [
            ['icon' => 'fa-map-marker', 'label' => 'Recorrido', 'value' => 'Cusco - Vilcanota'],
            ['icon' => 'fa-clock-o', 'label' => 'Duración', 'value' => 'Full Day'],
            ['icon' => 'fa-calendar-check-o', 'label' => 'Reserva', 'value' => 'Con 15 días de anticipación'],
        ],
        'includes' => '<ul><li>Traslado desde el hotel en Cusco.</li><li>Transporte al río.</li><li>Guía profesional.</li><li>Briefing de seguridad.</li><li>Equipo de canotaje: wetsuit, chaleco salvavidas, remo y casco.</li><li>Fotos y video bajo previa solicitud.</li><li>Instalaciones (vestidores).</li><li>Ducha caliente, baño.</li><li>Almuerzo.</li></ul>',
        'excludes' => '<ul><li>Propinas.</li></ul>',
        'bring' => '<ul><li>Sandalias (con pasadores) o zapatillas de correr.</li><li>Sombrero.</li><li>Bloqueador solar.</li><li>Ropa de baño.</li><li>Toalla.</li><li>Ropa de abrigo para después del canotaje.</li><li>Agua.</li><li>Snacks.</li><li>Bolsa plástica para su ropa húmeda.</li><li>Papel higiénico.</li></ul>',
    ];
@endphp
@extends('tours.adventure')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Rafting en Cusco | ' . config('seo.brand'),
        'description' => 'Rafting en Cusco, uno de los mejores ríos para el canotaje en el Sur del Perú, cerca de las montañas alrededor de Cusco.',
        'canonical' => route('tour-rafting', [], true),
        'og_image' => 'img/tours/canotaje-tour-cusco.jpg',
        'og_type' => 'article',
    ])
@endsection

@section('overview')
    <p>
        No se necesita ninguna experiencia previa para el canotaje (al menos debe saber nadar).
        Sin embargo, si tiene experiencia y quiere algo más desafiante, también podemos ofrecerle
        recorridos adecuados para usted.
    </p>
    <ul>
        <li><strong>Nivel I:</strong> realmente fácil, sin ningún riesgo, solo dejamos que la corriente nos lleve.</li>
        <li><strong>Nivel II:</strong> corrientes algo agitadas y con pequeñas rocas.</li>
        <li><strong>Nivel III:</strong> corrientes más agitadas, rocas, algunos remolinos (agradable).</li>
        <li><strong>Nivel IV:</strong> rápidos emocionantes, con algo de riesgo.</li>
        <li><strong>Nivel V:</strong> más fuerte y más divertido que el nivel IV, con riesgo — es el más comercial entre los niveles de canotaje. Para estos rápidos es necesario contar con un kayak de seguridad en el grupo.</li>
    </ul>
    <p>
        <strong>Cusipata</strong> — esta sección del río es la más tranquila que se ofrece en canotaje;
        se adapta bien a principiantes y a niños. Es bastante llana, niveles I y II en la estación seca
        (mayo–septiembre). En época de lluvias puede alcanzar el nivel III.
    </p>
    <p>
        <strong>Chuquicahuana</strong> — esta sección del río es de mayor aventura. En la estación seca
        sigue siendo bastante agradable, con rápidos de hasta el nivel III; en época de lluvias los
        rápidos pueden alcanzar el nivel IV.
    </p>
    <ul>
        <li><strong>Salida:</strong> a partir de las 08:30 am, recojo desde el hotel en Cusco.</li>
        <li><strong>Retorno:</strong> aprox. 5:00 pm.</li>
        <li><strong>Tiempo en el río:</strong> aprox. 2 horas y media.</li>
    </ul>
@endsection
