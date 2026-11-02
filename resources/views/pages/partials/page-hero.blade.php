{{--
    Hero compacto para páginas informativas (Nosotros, Testimonios, FAQ, Términos).
    @param string      $image   Ruta relativa a public/.
    @param string      $alt
    @param string      $title   H1.
    @param string|null $eyebrow
    @param string|null $lead
    @param array       $crumbs  Breadcrumb (ver layouts/partials/breadcrumb).
--}}
<section class="page-hero">
    <img class="page-hero__img" src="{{ asset($image) }}" alt="{{ $alt }}" data-parallax="8" loading="eager" fetchpriority="high" decoding="async">
    <div class="page-hero__inner site-container" data-reveal>
        @include('layouts.partials.breadcrumb', ['crumbs' => $crumbs])
        @if (! empty($eyebrow))
            <span class="page-hero__eyebrow">{{ $eyebrow }}</span>
        @endif
        <h1 class="page-hero__title">{{ $title }}</h1>
        @if (! empty($lead))
            <p class="page-hero__lead">{{ $lead }}</p>
        @endif
    </div>
</section>
