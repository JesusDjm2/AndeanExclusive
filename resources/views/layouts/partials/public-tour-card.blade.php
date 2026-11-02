{{--
    Tarjeta de tour para listados por categoria (adventures/experiences/around
    y sus equivalentes en espanol). Reemplaza el bloque de card Bootstrap 4
    duplicado 6 veces en esas paginas.

    Usa el patron "stretched link": la tarjeta completa es clicable a traves
    del titulo, y los enlaces de categoria quedan por encima sin anidar un
    <a> dentro de otro <a> (HTML invalido que antes rompia el click).

    @param \App\Models\Tour|\App\Models\Estour $tour
    @param string $tourRoute Nombre de ruta para el detalle del tour.
    @param string $categoryRoute Nombre de ruta para el listado por categoria.
    @param string $daysLabel Texto de unidad de dias ya localizado (p.ej. "days" o "dias").
    @param string $moreLabel Texto del enlace "leer mas" ya localizado.
--}}
<div class="card-tour">
    <div class="card-tour__media">
        <img src="{{ asset($tour->imgThumb) }}" alt="{{ $tour->nombre }}" loading="lazy">
    </div>
    <div class="card-tour__body">
        <h3 class="card-tour__title">
            <a href="{{ route($tourRoute, $tour->slug) }}" class="card-tour__title-link">{{ $tour->nombre }}</a>
        </h3>
        @if ($tour->recorrido ?? null)
            <span class="card-tour__location"><i class="fa fa-map-marker"></i> {{ $tour->recorrido }}</span>
        @endif
        <p class="card-tour__excerpt">{{ $tour->descripcionCorta }}</p>
        <div class="card-tour__footer">
            <div class="flex-between mb-sm">
                <span class="card-tour__meta"><i class="fa fa-clock-o"></i> {{ $tour->dias }} {{ $daysLabel }}</span>
                <span class="card-tour__meta"><i class="fa fa-usd"></i> {{ $tour->precio }}.00</span>
            </div>
            <span class="btn-brand btn-brand--ghost btn-brand--sm btn-brand--block">{{ $moreLabel }}</span>
        </div>
    </div>
</div>
