@php
    $locale = 'es';
    $adventure = [
        'route' => 'tour-choquequirao',
        'title' => 'Choquequirao Trek',
        'crumb' => 'Choquequirao',
        'eyebrow' => '4 días / 3 noches',
        'lead' => 'Este tour a Choquequirao de 4 días es uno de los más hermosos: paisajes únicos y energía ancestral, el único atractivo en Cusco a la altura de competir con Machu Picchu.',
        'image' => 'img/tours/cusco-maravilloso.jpg',
        'image_alt' => 'Choquequirao Trek',
        'days' => 4,
        'facts' => [
            ['icon' => 'fa-map-marker', 'label' => 'Recorrido', 'value' => 'Cusco - Choquequirao'],
            ['icon' => 'fa-clock-o', 'label' => 'Duración', 'value' => '4 días / 3 noches'],
        ],
        'includes' => '<ul><li>Sesión informativa previa a la partida.</li><li>Recojo del hotel por la mañana y traslado en transporte privado a Cachora, punto de partida de la caminata.</li><li>Boleto de ingreso a Choquequirao, para los visitantes y el personal de la agencia.</li><li>Carpas.</li><li>Guía profesional (español e inglés).</li><li>Cocinero y equipo de cocina.</li><li>Paquete de animales (para llevar tiendas de campaña, comida y equipo de cocina).</li><li>1 caballo de emergencia.</li><li>Comidas (03 desayunos, 04 almuerzos, 03 cenas). Menús vegetarianos o especiales disponibles sin costo adicional.</li><li>Botiquín de primeros auxilios.</li><li>Transporte privado de Cusco a Cachora.</li></ul>',
        'excludes' => '<ul><li>Cena en el último día.</li><li>Impuesto IGV.</li><li>Otros servicios no mencionados en el programa.</li></ul>',
        'bring' => null,
    ];
@endphp
@extends('tours.adventure')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Tour Choquequirao (4 Días / 3 Noches) | ' . config('seo.brand'),
        'description' => 'Este tour a Choquequirao de 4 días es uno de los más hermosos: paisajes únicos y energía ancestral, el único atractivo en Cusco a la altura de competir con Machu Picchu.',
        'canonical' => route('tour-choquequirao', [], true),
        'og_image' => 'img/tours/cusco-maravilloso.jpg',
        'og_type' => 'article',
    ])
@endsection

@section('overview')
    <p>
        Este tour a Choquequirao de 4 días es uno de los más hermosos. La mayor parte de las
        personas que ya lo hicieron dicen que es cansado, pero el esfuerzo siempre vale la pena.
        Los paisajes que se aprecian son únicos, la energía ancestral se siente desde el primer
        paso. No en vano se dice que Choquequirao es el único atractivo en Cusco a la altura de
        competir con Machu Picchu.
    </p>

    <table>
        <tbody>
            <tr><th>Día 01</th><td>Cusco - Chiquisca</td></tr>
            <tr><th>Día 02</th><td>Chiquisca - Choquequirao</td></tr>
            <tr><th>Día 03</th><td>Choquequirao a Playa Rosalina</td></tr>
            <tr><th>Día 04</th><td>Playa Rosalina a Cachora</td></tr>
        </tbody>
    </table>
@endsection

@section('itinerary')
    <p><strong>Día 1: Cusco - Chiquisca</strong></p>
    <p>
        Salimos de Cusco a las 6:00 a.m. en transporte privado hacia Cachora, una comunidad andina
        ubicada a 4-5h de Cusco a 2800 m/9184 ft, el punto de partida de la caminata donde nos
        encontramos con nuestros arrieros y caballos. Empezamos la caminata subiendo por dos horas
        y media hacia el abra Capuliyoc a 2750 m/9020 ft, con hermosas vistas de las montañas
        circundantes y el Cañón del Río Apurímac. Continuamos descendiendo durante dos horas más
        hacia Chiquisca, donde armaremos el campamento y pasaremos la noche.
    </p>
    <p><strong>Día 2: Chiquisca - Choquequirao</strong></p>
    <p>
        Dejamos Chiquisca a las 6:00 a.m. con dirección a Playa Rosalina, aproximadamente una
        hora. Cruzaremos el río Apurímac y comenzaremos la subida hacia Santa Rosa, zigzagueando
        por una pendiente empinada (2 horas aprox.). En Santa Rosa tendremos media hora de
        descanso, con la opción de probar el cambray, un extracto de caña de azúcar local.
        Continuamos subiendo lentamente durante dos horas y media hasta llegar a Marampata, lugar
        de almuerzo. Desde ahí caminamos dos horas más hasta el complejo arqueológico de
        Choquequirao, a 3103 m/10178 ft, donde acamparemos y tendremos tiempo libre para explorar
        las terrazas y edificios.
    </p>
    <p><strong>Día 3: Choquequirao a Playa Rosalina</strong></p>
    <p>
        Disfrutaremos de una visita guiada completa al complejo de Choquequirao: la parte alta
        (Hanan) y las partes inferiores (Urin), incluyendo algunas áreas aún cubiertas de
        vegetación silvestre pendientes de restauración. Luego iniciamos la caminata de regreso
        hacia Santa Rosa, donde almorzaremos, y continuamos descendiendo dos horas más hasta Playa
        Rosalina, a 1450 m/4756 ft, donde acamparemos y cenaremos.
    </p>
    <p><strong>Día 4: Playa Rosalina a Cachora</strong></p>
    <p>
        Dejamos el campamento después del desayuno para iniciar el ascenso de dos a tres horas
        hacia Chiquisca, y luego el camino de vuelta hacia el abra Capuliyoc, donde tendremos el
        almuerzo. Por la tarde continuamos el camino de dos horas de regreso a Cachora, donde nos
        estará esperando un transporte privado de vuelta a Cusco (llegada aproximada 9:00 p.m.).
    </p>
    <p>
        <em>
            * Los horarios de salida y llegada son aproximados. Los campamentos están sujetos a
            cambios según criterio del guía y el progreso del grupo.
        </em>
    </p>
@endsection
