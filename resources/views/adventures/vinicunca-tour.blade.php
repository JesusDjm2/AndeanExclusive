@php
    $locale = 'es';
    $adventure = [
        'route' => 'vinicunca-tour',
        'title' => 'Montaña de 7 Colores (Vinicunca)',
        'crumb' => 'Vinicunca',
        'eyebrow' => 'Por Cusipata · Full Day',
        'lead' => 'La montaña Vinicunca se ha convertido en uno de los destinos turísticos naturales más visitados en Perú, con una nueva ruta de acceso por Cusipata.',
        'image' => 'img/tours/rainbow-mountain-vinicunca.jpg',
        'image_alt' => 'Montaña de 7 Colores - Vinicunca',
        'days' => 1,
        'facts' => [
            ['icon' => 'fa-clock-o', 'label' => 'Duración', 'value' => 'Full Day'],
            ['icon' => 'fa-calendar-check-o', 'label' => 'Reserva', 'value' => 'Con 30 días de anticipación'],
        ],
        'includes' => '<ul><li>Guía profesional.</li><li>Tickets de ingreso a la Montaña de Colores.</li><li>Desayuno y almuerzo (indicar dietas especiales antes o durante la reserva).</li><li>Transporte turístico ida y retorno.</li><li>Botiquín de primeros auxilios.</li></ul>',
        'excludes' => '<ul><li>Propinas.</li><li>Alquiler de caballos.</li><li>Bebidas extras (agua, cerveza, gaseosa, etc.).</li></ul>',
        'bring' => '<ul><li>Mochila con capa protectora.</li><li>Zapatos de trekking.</li><li>Botella de agua.</li><li>Cámara fotográfica con baterías extra.</li><li>Lentes de sol, bloqueador solar, gorra o sombrero.</li><li>Ropa abrigadora y ligera.</li><li>Medicamentos personales.</li><li>Poncho para lluvia.</li><li>Snacks (barras de chocolate, frutos secos).</li><li>Dinero extra en soles.</li></ul>',
    ];
@endphp
@extends('tours.adventure')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Tour Montaña de 7 Colores - Vinicunca | ' . config('seo.brand'),
        'description' => 'La montaña Vinicunca se ha convertido en uno de los destinos turísticos naturales más visitados en Perú, con una nueva ruta de acceso por Cusipata.',
        'canonical' => route('vinicunca-tour', [], true),
        'og_image' => 'img/tours/rainbow-mountain-vinicunca.jpg',
        'og_type' => 'article',
    ])
@endsection

@section('overview')
    <p>
        La montaña Vinicunca se ha convertido en uno de los destinos turísticos naturales más
        visitados en Perú, motivo por el cual la municipalidad de Cusipata abrió una nueva ruta de
        acceso a la Montaña de Colores: una excelente alternativa donde se aprecia una gama de
        paisajes naturales, entre ellos el Valle Rojo, lagos, camélidos sudamericanos y la
        hospitalidad de los lugareños, los más beneficiados con el turismo.
    </p>
    <p>
        ¡Ya no hay excusas para no visitar esta maravilla natural, ahora por una ruta más corta y
        segura!
    </p>

    <figure class="article-figure">
        <img src="{{ asset('img/galeria/MONTAÑA-7-COLORES.webp') }}" alt="Vinicunca" loading="lazy">
    </figure>
@endsection

@section('itinerary')
    <p>
        La montaña de 7 Colores, también conocida como Vinicunca o Winicunca, es uno de los
        lugares naturales más visitados en Perú.
    </p>
    <p>
        Nuestro tour por Cusipata comienza con el recojo entre las 04:20 am y 5:00 am,
        dependiendo de dónde se ubique su hotel en Cusco. Nos dirigiremos hacia el sur de la
        ciudad, un viaje de aproximadamente 2 horas hasta Cusipata, lugar de nuestro desayuno.
        Luego continuaremos una hora más en minivan, observando llamas, alpacas y la gente local
        en el trayecto.
    </p>
    <p>
        En <strong>Phulawasipata</strong> comenzará la caminata, de aproximadamente 1 hora o más
        según el ritmo, con la opción de alquilar un caballo si lo necesita (recuerde que
        Vinicunca se encuentra a 5000 metros de altura).
    </p>

    <figure class="article-figure">
        <img src="{{ asset('img/galeria/Vinicunca-portada.webp') }}" alt="Montaña 7 colores" loading="lazy">
    </figure>

    <p>
        Desde este punto tendremos la mejor vista de las montañas, incluyendo el nevado Ausangate
        a 6400 msnm, la montaña más alta del Cusco. Después descenderemos por una hora para tomar
        el transporte hacia el punto de almuerzo, nuevamente en Cusipata, y finalmente regresamos
        a la ciudad del Cusco, llegando aproximadamente entre las 5:00 y 6:00 pm.
    </p>

    <figure class="article-figure">
        <img src="{{ asset('img/galeria/paisaje-vinicunca.webp') }}" alt="Vinicunca tour" loading="lazy">
    </figure>
@endsection
