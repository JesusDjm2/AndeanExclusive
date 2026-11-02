{{--
    Incluye / No incluye en dos columnas con iconos ✓ / ✕ (los iconos los pone el CSS sobre las <li>).
    @param string      $included HTML (normalmente una <ul>).
    @param string|null $excluded HTML o null si el tour no lo especifica.
    @param string      $locale
--}}
<div class="tour-includes {{ $excluded ? 'tour-includes--split' : '' }}">
    <div class="tour-includes__col tour-includes__col--in">
        <h3 class="tour-includes__title"><i class="fa fa-check-circle" aria-hidden="true"></i> {{ __('tour.included', [], $locale) }}</h3>
        <div class="tour-includes__list">{!! $included !!}</div>
    </div>
    @if ($excluded)
        <div class="tour-includes__col tour-includes__col--out">
            <h3 class="tour-includes__title"><i class="fa fa-times-circle" aria-hidden="true"></i> {{ __('tour.not_included', [], $locale) }}</h3>
            <div class="tour-includes__list">{!! $excluded !!}</div>
        </div>
    @endif
</div>
