{{--
    Itinerario día a día como línea de tiempo (datos de App\Support\TourContent::itinerary).
    Cada día es un <details>: el contenido está en el HTML (indexable) aunque esté plegado.
    Si el itinerario no sigue el patrón "Day/Día N", se muestra tal cual.
    @param array  $itinerary ['intro' => html, 'days' => [['number', 'title', 'html'], ...]]
    @param string $locale
--}}
<div class="tour-section__header">
    <h2 class="tour-section__title">{{ __('tour.sections.itinerary', [], $locale) }}</h2>
    @if (count($itinerary['days']) > 1)
        <button type="button" class="tour-itinerary__toggle" data-itinerary-toggle
            data-label-expand="{{ __('tour.expand_all', [], $locale) }}"
            data-label-collapse="{{ __('tour.collapse_all', [], $locale) }}">{{ __('tour.expand_all', [], $locale) }}</button>
    @endif
</div>

@if ($itinerary['intro'] !== '')
    <div class="legal-prose legal-prose--fluid">{!! $itinerary['intro'] !!}</div>
@endif

@if ($itinerary['days'])
    <ol class="tour-itinerary" data-itinerary>
        @foreach ($itinerary['days'] as $day)
            <li class="tour-itinerary__day">
                <details @if ($loop->first) open @endif>
                    <summary class="tour-itinerary__summary">
                        <span class="tour-itinerary__badge">
                            <small>{{ __('tour.day', [], $locale) }}</small>{{ $day['number'] }}
                        </span>
                        <span class="tour-itinerary__title">{{ $day['title'] }}</span>
                        <i class="fa fa-angle-down tour-itinerary__chevron" aria-hidden="true"></i>
                    </summary>
                    <div class="tour-itinerary__body legal-prose legal-prose--fluid">
                        {!! $day['html'] !!}
                    </div>
                </details>
            </li>
        @endforeach
    </ol>
@endif
