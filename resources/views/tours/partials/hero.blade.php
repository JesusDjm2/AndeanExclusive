{{--
    Hero de tour/aventura: imagen a sangre, título, bajada, datos clave y CTAs.
    @param string      $image    URL de la imagen (LCP: carga prioritaria).
    @param string|null $alt      Texto alternativo (por defecto, el título).
    @param string      $title
    @param string|null $eyebrow  Categoría o etiqueta corta.
    @param string|null $lead     Descripción corta.
    @param array       $facts    [['icon' => 'fa-clock-o', 'label' => 'Duración', 'value' => '4 días'], ...]
    @param string      $locale   'es'|'en'
    @param string      $tourName Nombre usado en el mensaje prellenado de WhatsApp.
--}}
@php
    $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', config('seo.phone'))
        . '?text=' . rawurlencode(__('tour.whatsapp_text', ['tour' => $tourName], $locale));
@endphp
<section class="tour-hero">
    <img class="tour-hero__img" src="{{ $image }}" alt="{{ $alt ?? $title }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">

    <div class="tour-hero__inner site-container">
        <div class="tour-hero__content" data-reveal>
            @if (! empty($eyebrow))
                <span class="tour-hero__eyebrow">{{ $eyebrow }}</span>
            @endif
            <h1 class="tour-hero__title">{{ $title }}</h1>
            @if (! empty($lead))
                <p class="tour-hero__lead">{{ \Illuminate\Support\Str::limit(strip_tags($lead), 220) }}</p>
            @endif

            <div class="tour-hero__actions">
                <a href="#book" class="btn-brand btn-brand--primary">{{ __('tour.check_availability', [], $locale) }}</a>
                <a href="{{ $whatsappUrl }}" class="btn-brand btn-brand--glass" target="_blank" rel="noopener nofollow">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i> {{ __('tour.whatsapp', [], $locale) }}
                </a>
            </div>
        </div>

        @if (! empty($facts))
            <dl class="tour-facts" data-reveal>
                @foreach ($facts as $fact)
                    <div class="tour-facts__item">
                        <i class="fa {{ $fact['icon'] }}" aria-hidden="true"></i>
                        <div>
                            <dt>{{ $fact['label'] }}</dt>
                            <dd>{{ $fact['value'] }}</dd>
                        </div>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>
</section>
