@if ($tours->count())
    <section class="section section--sand">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">{{ $eyebrow }}</span>
                <h2 class="section-title">{{ $title }}</h2>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $relatedTour)
                    @include('layouts.partials.public-tour-card', [
                        'tour' => $relatedTour,
                        'tourRoute' => $tourRoute,
                        'categoryRoute' => $categoryRoute,
                        'daysLabel' => $locale === 'es' ? ($relatedTour->dias == 1 ? 'día' : 'días') : 'days',
                        'moreLabel' => $moreLabel,
                    ])
                @endforeach
            </div>
        </div>
    </section>
@endif
