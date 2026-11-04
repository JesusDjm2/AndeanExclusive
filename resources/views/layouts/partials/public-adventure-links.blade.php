{{--
    Grid "También te puede interesar" para las 6 páginas de aventuras de
    salida fija (adventures/tour-*). No están respaldadas por un modelo en
    base de datos, así que se listan a mano en vez de vía Eloquent.

    @param string $currentRoute Nombre de la ruta actual, para excluirla del listado.
--}}
@php
    $adventures = [
        ['route' => 'tour-atv', 'title' => 'ATV Tour · Maras & Moray', 'img' => 'img/tours/atv-cusco-tours-exclusive.jpg'],
        ['route' => 'tour-rafting', 'title' => 'Rafting en el Río Vilcanota', 'img' => 'img/tours/canotaje-tour-cusco.jpg'],
        ['route' => 'tour-choquequirao', 'title' => 'Trek a Choquequirao (4 días)', 'img' => 'img/tours/cusco-maravilloso.jpg'],
        ['route' => 'tour-islas-palomino', 'title' => 'Islas Palomino', 'img' => 'img/tours/palomino-islands-swim.jpg'],
        ['route' => 'tour-laguna-humantay', 'title' => 'Laguna Humantay', 'img' => 'img/tours/laguna-lake-humantya.jpg'],
        ['route' => 'vinicunca-tour', 'title' => 'Montaña de 7 Colores (Vinicunca)', 'img' => 'img/tours/rainbow-mountain-vinicunca.jpg'],
    ];
@endphp
<section class="section section--sand">
    <div class="site-container">
        <div class="section-header">
            <span class="section-eyebrow font-display">Más aventuras</span>
            <h2 class="section-title">También te puede interesar</h2>
        </div>
        <div class="grid-auto" data-reveal-stagger>
            @foreach ($adventures as $adventure)
                @continue($adventure['route'] === $currentRoute)
                <a href="{{ route($adventure['route']) }}" class="card-blog">
                    <img src="{{ asset($adventure['img']) }}" alt="{{ $adventure['title'] }}" loading="lazy">
                    <div class="card-blog__body">
                        <h3 class="card-blog__title">{{ $adventure['title'] }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
