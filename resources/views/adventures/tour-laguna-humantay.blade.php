@php
    $locale = 'es';
    $adventure = [
        'route' => 'tour-laguna-humantay',
        'title' => 'Laguna Humantay',
        'crumb' => 'Laguna Humantay',
        'eyebrow' => 'Cusco - Soraypampa - Salkantay - Humantay',
        'lead' => 'La laguna Humantay es un atractivo natural ubicado en el poblado de Soraypampa, de aguas color turquesa, una de las caminatas de un día más espectaculares cerca de Cusco.',
        'image' => 'img/tours/laguna-lake-humantya.jpg',
        'image_alt' => 'Laguna Humantay',
        'days' => 1,
        'facts' => [
            ['icon' => 'fa-clock-o', 'label' => 'Duración', 'value' => 'Full Day'],
            ['icon' => 'fa-calendar-check-o', 'label' => 'Reserva', 'value' => 'Con 30 días de anticipación'],
        ],
        'includes' => '<ul><li>Información previa a la partida.</li><li>Recojo del hotel por la mañana y traslado Cusco - Mollepata - Soraypampa - Cusco (punto de partida de la caminata).</li><li>Desayuno continental y almuerzo buffet en Mollepata.</li><li>Guía profesional español-inglés.</li><li>Botiquín de primeros auxilios, incluyendo botella de oxígeno de emergencia.</li></ul>',
        'excludes' => '<ul><li>Caballo para montar.</li><li>Propinas.</li></ul>',
        'bring' => null,
    ];
@endphp
@extends('tours.adventure')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Tour Laguna Humantay | ' . config('seo.brand'),
        'description' => 'La laguna Humantay es un atractivo natural ubicado en el poblado de Soraypampa, de aguas color turquesa, una de las caminatas de un día más espectaculares cerca de Cusco.',
        'canonical' => route('tour-laguna-humantay', [], true),
        'og_image' => 'img/tours/laguna-lake-humantya.jpg',
        'og_type' => 'article',
    ])
@endsection

@section('overview')
    <p>
        En este programa visitaremos uno de los nuevos circuitos turísticos del Cusco. La laguna
        Humantay es un atractivo natural ubicado en el poblado de Soraypampa; antiguamente solo la
        visitaban quienes se aventuraban a realizar la caminata al nevado Salkantay, pero ahora se
        ha convertido en uno de los lugares más visitados por los viajeros. Visitar la laguna
        Humantay no toma más de 1 día; la caminata es fácil si ya se aclimató previamente en
        Cusco, y la recompensa a este esfuerzo es el maravilloso paisaje de esta laguna de aguas
        color turquesa.
    </p>

    <figure class="article-figure">
        <img src="{{ asset('img/galeria/tour-humantay-laguna.webp') }}" alt="Laguna Humantay" loading="lazy">
    </figure>
@endsection

@section('itinerary')
    <p>
        El viaje empieza en su hotel en Cusco: nuestro transfer lo recogerá entre las 04:00 y
        05:00 de la mañana. Realizaremos un viaje de dos horas y media en carro hacia el pueblo de
        Mollepata, donde podremos desayunar y abastecernos de las últimas provisiones.
    </p>
    <p>
        Terminado el desayuno, continuaremos por una hora y media en carro por un camino de
        tierra (trocha) hasta llegar a la comunidad de <strong>Soraypampa</strong>, ubicada a
        3900 msnm. Durante el viaje veremos hermosos paisajes y el impresionante nevado
        <strong>Salkantay</strong>.
    </p>
    <p>
        Desde Soraypampa emprenderemos una caminata de 2 km. El camino es ascendente y tomará
        entre una hora y media y dos horas; veremos un panorama de montañas cubiertas de nieve,
        paisajes naturales impresionantes y gran diversidad de flora y fauna, hasta llegar a la
        laguna Humantay, a 4200 msnm.
    </p>

    <figure class="article-figure">
        <img src="{{ asset('img/galeria/tour-humantay-turista.webp') }}" alt="Tour a la laguna Humantay" loading="lazy">
    </figure>

    <p>
        La laguna Humantay es de color turquesa debido al mineral que se encuentra en el fondo; es
        alimentada por los glaciares de la montaña y considerada una joya de la naturaleza. Además
        de disfrutar de la laguna, podremos apreciar las impresionantes vistas de las montañas que
        la rodean. Descenderemos caminando hasta la comunidad de Soraypampa, donde disfrutaremos
        de un almuerzo, e iniciaremos el viaje de retorno a la ciudad del Cusco. El tour finaliza
        aproximadamente a las 6:30 de la tarde.
    </p>

    <figure class="article-figure">
        <img src="{{ asset('img/galeria/humantay-lake.webp') }}" alt="Tour laguna Humantay" loading="lazy">
    </figure>

    <h3>Sugerimos</h3>
    <p>
        Casi todo el camino está asfaltado; sin embargo, continuaremos por caminos de terracería
        empinados, por lo que recomendamos ir lo más ligero posible y no olvidar agua, linterna y
        bastones de trekking si es posible.
    </p>
@endsection
