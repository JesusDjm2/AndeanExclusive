{{--
    Menú de secciones fijo bajo el header; resalta la sección visible (resources/js/tour-detail.js).
    @param array      $sections [['id' => 'overview', 'label' => 'Resumen'], ...]
    @param float|null $price    Precio "desde"; null o 0 oculta el precio.
    @param string     $locale
--}}
<nav class="tour-nav" aria-label="{{ __('tour.nav_label', [], $locale) }}" data-tour-nav>
    <div class="tour-nav__inner site-container">
        <ul class="tour-nav__links">
            @foreach ($sections as $section)
                <li><a href="#{{ $section['id'] }}" class="tour-nav__link" data-tour-nav-link>{{ $section['label'] }}</a></li>
            @endforeach
        </ul>
        <div class="tour-nav__cta">
            @if (! empty($price))
                <span class="tour-nav__price">
                    <small>{{ __('tour.from', [], $locale) }}</small> US${{ number_format($price, 0) }}
                </span>
            @endif
            <a href="#book" class="btn-brand btn-brand--primary btn-brand--sm">{{ __('tour.sections.book', [], $locale) }}</a>
        </div>
    </div>
</nav>
