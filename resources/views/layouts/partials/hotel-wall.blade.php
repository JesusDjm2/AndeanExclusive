{{--
    Muro de hoteles estilo mosaico (photo-tile) + galería lightbox por hotel.
    El tile completo abre el popup; no enlaza a la web del hotel.

    @param array $hotels Cada hotel: img (portada), place, name, desc, gallery[] (rutas relativas bajo /public).
    @param array $labels cta, open, close, prev, next, dialog.
--}}
<div class="photo-wall" data-reveal-stagger>
    @foreach ($hotels as $i => $hotel)
        <article class="photo-tile photo-tile--gallery">
            <img src="{{ asset($hotel['img']) }}" alt="{{ $hotel['name'] }} — {{ $hotel['place'] }}" loading="lazy" class="photo-tile__img">
            <div class="photo-tile__body">
                <span class="photo-tile__meta"><i class="fa fa-map-marker"></i> {{ $hotel['place'] }}</span>
                <h3 class="photo-tile__title">{{ $hotel['name'] }}</h3>
                <span class="photo-tile__stars">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                </span>
                <div class="photo-tile__reveal">
                    <p class="photo-tile__excerpt">{{ $hotel['desc'] }}</p>
                    <span class="photo-tile__cta">{{ $labels['cta'] }} <i class="fa fa-camera" aria-hidden="true"></i></span>
                </div>
            </div>
            <button type="button" class="photo-tile__open" data-gallery-open="{{ $i }}" aria-label="{{ $labels['open'] }} — {{ $hotel['name'] }}"></button>
        </article>
    @endforeach
</div>

@php
    $galleryData = collect($hotels)
        ->mapWithKeys(fn ($h, $i) => [$i => [
            'name' => $h['name'],
            'place' => $h['place'],
            'images' => collect($h['gallery'])->map(fn ($g) => asset($g))->all(),
        ]])
        ->all();
@endphp
<div class="gallery-modal" data-gallery-modal hidden role="dialog" aria-modal="true" aria-label="{{ $labels['dialog'] }}">
    <div class="gallery-modal__backdrop" data-gallery-close></div>
    <figure class="gallery-modal__stage">
        <img class="gallery-modal__img" data-gallery-img src="" alt="">
        <figcaption class="gallery-modal__caption">
            <span class="gallery-modal__name font-display" data-gallery-name></span>
            <span class="gallery-modal__place" data-gallery-place></span>
            <span class="gallery-modal__counter" data-gallery-counter></span>
        </figcaption>
        <button type="button" class="gallery-modal__nav gallery-modal__nav--prev" data-gallery-prev aria-label="{{ $labels['prev'] }}"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
        <button type="button" class="gallery-modal__nav gallery-modal__nav--next" data-gallery-next aria-label="{{ $labels['next'] }}"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
        <button type="button" class="gallery-modal__close" data-gallery-close aria-label="{{ $labels['close'] }}"><i class="fa fa-times" aria-hidden="true"></i></button>
    </figure>
</div>
<script type="application/json" data-gallery-data>@json($galleryData)</script>
