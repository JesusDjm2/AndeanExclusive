@php
    $locale = 'en';
    $altRoute = 'inicio';
    $hasPreloader = true;
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'en', 'page' => 'home', 'og_image' => '/img/machupicchu.png'])

    {{-- Preconexión ultra-rápida a los servidores y CDN de YouTube y Google Video --}}
    <link rel="preconnect" href="https://www.youtube.com" crossorigin>
    <link rel="preconnect" href="https://s.ytimg.com" crossorigin>
    <link rel="preconnect" href="https://i.ytimg.com" crossorigin>
    <link rel="preconnect" href="https://googlevideo.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.youtube.com">
    <link rel="dns-prefetch" href="https://s.ytimg.com">
    <link rel="dns-prefetch" href="https://i.ytimg.com">
    <link rel="dns-prefetch" href="https://googlevideo.com">

    {{-- Bootstrap anticipado de la API de YouTube para carga inmediata idéntico al panel de login --}}
    <script>
        window.YT_API_READY = false;
        window.onYouTubeIframeAPIReady = function() {
            window.YT_API_READY = true;
            if (typeof window.aeInitHeroVideo === 'function') {
                window.aeInitHeroVideo();
            }
        };
    </script>
    <script src="https://www.youtube.com/iframe_api"></script>
@endsection

@section('contenido')
    <section class="hero" data-hero-video-id="B_8bbKn3amE">
        <div class="hero__media" data-parallax-container>
            <picture class="hero__poster">
                <source media="(max-width: 768px)" srcset="{{ asset('img/responsive/machupicchu-mobile.webp') }}" type="image/webp">
                <img src="{{ asset('img/machupicchu.webp') }}" alt="Machu Picchu, Cusco - Peru" loading="eager" fetchpriority="high" decoding="async" data-parallax="12">
            </picture>
            <div class="hero__video-wrap" id="hero-video-wrap" aria-hidden="true">
                <iframe
                    id="hero-yt-player"
                    src="https://www.youtube.com/embed/B_8bbKn3amE?enablejsapi=1&autoplay=1&mute=1&controls=0&loop=1&playlist=B_8bbKn3amE&playsinline=1&rel=0&iv_load_policy=3&modestbranding=1&disablekb=1&fs=0&origin={{ url('/') }}"
                    title="Peru Travel Hero Video"
                    frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    tabindex="-1"
                    aria-hidden="true">
                </iframe>
            </div>
        </div>
        <div class="hero__content">
            <h1 class="hero__title">Luxury Peru tours, made exclusively for you</h1>
            <div class="hero__divider"></div>
            <p class="hero__subtitle">
                Enjoy the best tours, trips and vacation packages in Peru — the most luxury &amp; exclusive
                experiences, tailored to you.
            </p>

            @include('layouts.partials.form-consulta-rapida', ['locale' => 'en'])
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Handpicked for you</span>
                <h2 class="section-title">Best Sellers</h2>
                <p class="section-lead">The most luxury &amp; exclusive tours for you.</p>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $tour)
                    <a href="{{ route('tour.show', $tour->slug) }}" class="card-tour">
                        <div class="card-tour__media">
                            <img src="{{ asset($tour->imgThumb) }}" alt="{{ $tour->nombre }}" loading="lazy">
                        </div>
                        <div class="card-tour__body">
                            <h3 class="card-tour__title">{{ $tour->nombre }}</h3>
                            <p class="card-tour__excerpt">{{ $tour->descripcionCorta }}</p>
                            <div class="flex-between mt-xs">
                                <span class="card-tour__meta"><i class="fa fa-clock-o"></i> {{ $tour->dias }} days</span>
                                <span class="card-tour__meta"><i class="fa fa-usd"></i> {{ $tour->precio }}.00</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--sand section--hotels">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Where you'll stay</span>
                <h2 class="section-title">Peru's Most Celebrated Hotels</h2>
                <p class="section-lead">These aren't necessarily the properties we book most often — they're the ones that set the standard. From Belmond's colonial landmarks in Cusco to secluded Amazon eco-lodges, each name on this list is synonymous with exceptional Peruvian hospitality, and the benchmark we design every itinerary against.</p>
            </div>
        </div>
        @php
            $hotels = [
                ['img' => 'img/hotels/belmond-nazarenas-2.jpg', 'place' => 'Cusco', 'name' => 'Belmond Hotel Nazarenas', 'desc' => 'A restored 16th-century convent turned all-suite sanctuary, steps from Cusco\'s Plaza de Armas.',
                    'gallery' => ['img/hotels/belmond-nazarenas-2.jpg', 'img/hotels/belmond-nazarenas-3.jpg', 'img/hotels/belmond-nazarenas-1.jpg', 'img/hotels/belmond-nazarenas-4.jpg', 'img/hotels/belmond-nazarenas-5.jpg']],
                ['img' => 'img/hotels/sol-y-luna-1.jpg', 'place' => 'Sacred Valley', 'name' => 'Sol & Luna Hotel', 'desc' => 'Thatched-roof casitas set among 25 acres of gardens in the heart of the Sacred Valley.',
                    'gallery' => ['img/hotels/sol-y-luna-1.jpg', 'img/hotels/sol-y-luna-2.jpg', 'img/hotels/sol-y-luna-4.jpg', 'img/hotels/sol-y-luna-5.jpg', 'img/hotels/sol-y-luna-3.jpg']],
                ['img' => 'img/hotels/sanctuary-lodge-1.jpg', 'place' => 'Machu Picchu', 'name' => 'Sanctuary Lodge Hotel', 'desc' => 'The only hotel at the entrance of Machu Picchu, with front-row access to the citadel at sunrise.',
                    'gallery' => ['img/hotels/sanctuary-lodge-1.jpg', 'img/hotels/sanctuary-lodge-2.jpg', 'img/hotels/sanctuary-lodge-3.jpg', 'img/hotels/sanctuary-lodge-4.jpg', 'img/hotels/sanctuary-lodge-5.jpg']],
                ['img' => 'img/hotels/miraflores-park-5.jpg', 'place' => 'Lima', 'name' => 'Miraflores Park Hotel', 'desc' => 'An oceanfront tower overlooking Lima\'s Malecón, with panoramic city and Pacific views.',
                    'gallery' => ['img/hotels/miraflores-park-5.jpg', 'img/hotels/miraflores-park-1.jpg', 'img/hotels/miraflores-park-2.jpg', 'img/hotels/miraflores-park-3.jpg', 'img/hotels/miraflores-park-4.jpg']],
                ['img' => 'img/hotels/titilaka-1.jpg', 'place' => 'Puno', 'name' => 'Titilaka Hotel', 'desc' => 'An all-suite lodge on its own private peninsula on Lake Titicaca, the world\'s highest navigable lake.',
                    'gallery' => ['img/hotels/titilaka-1.jpg', 'img/hotels/titilaka-2.jpg', 'img/hotels/titilaka-3.jpg', 'img/hotels/titilaka-4.jpg']],
                ['img' => 'img/hotels/reserva-amazonica-1.jpg', 'place' => 'Puerto Maldonado', 'name' => 'Reserva Amazonas Hotel', 'desc' => 'A carbon-neutral eco-lodge deep inside a private 17,000-hectare Amazon reserve.',
                    'gallery' => ['img/hotels/reserva-amazonica-1.jpg', 'img/hotels/reserva-amazonica-2.jpg', 'img/hotels/reserva-amazonica-3.jpg', 'img/hotels/reserva-amazonica-4.jpg']],
                ['img' => 'img/hotels/hotel-b-1.jpg', 'place' => 'Lima', 'name' => 'B Hotel', 'desc' => 'Lima\'s only Relais & Châteaux address, a Belle Époque mansion turned boutique art hotel in Barranco.',
                    'gallery' => ['img/hotels/hotel-b-1.jpg', 'img/hotels/hotel-b-2.jpg', 'img/hotels/hotel-b-3.jpg', 'img/hotels/hotel-b-4.jpg', 'img/hotels/hotel-b-5.jpg']],
                ['img' => 'img/hotels/casa-cartagena-1.jpg', 'place' => 'Cusco', 'name' => 'Casa Cartagena', 'desc' => 'A 16th-century mansion in Cusco\'s historic center, reimagined as a five-star boutique hotel and spa.',
                    'gallery' => ['img/hotels/casa-cartagena-1.jpg', 'img/hotels/casa-cartagena-2.jpg', 'img/hotels/casa-cartagena-3.jpg', 'img/hotels/casa-cartagena-4.jpg', 'img/hotels/casa-cartagena-5.jpg']],
                ['img' => 'img/hotels/casona-inkaterra-1.jpg', 'place' => 'Sacred Valley', 'name' => 'Casona Inkaterra', 'desc' => 'A contemporary hacienda on 100 acres of the Sacred Valley, framed by the Andes.',
                    'gallery' => ['img/hotels/casona-inkaterra-1.jpg', 'img/hotels/casona-inkaterra-2.jpg', 'img/hotels/casona-inkaterra-3.jpg']],
            ];
        @endphp
        @include('layouts.partials.hotel-wall', [
            'hotels' => $hotels,
            'labels' => [
                'cta' => 'View photos', 'open' => 'View photo gallery', 'close' => 'Close gallery',
                'prev' => 'Previous photo', 'next' => 'Next photo', 'dialog' => 'Hotel photo gallery',
            ],
        ])
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Explore Peru</span>
                <h2 class="section-title">Popular Destinations</h2>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours2 as $tour)
                    <a href="{{ route('tour.show', $tour->slug) }}" class="card-tour">
                        <div class="card-tour__media">
                            <img src="{{ asset($tour->imgThumb) }}" alt="{{ $tour->nombre }}" loading="lazy">
                        </div>
                        <div class="card-tour__body">
                            <h3 class="card-tour__title">{{ $tour->nombre }}</h3>
                            <p class="card-tour__excerpt">{{ $tour->descripcionCorta }}</p>
                            <div class="flex-between mt-xs">
                                <span class="card-tour__meta"><i class="fa fa-clock-o"></i> {{ $tour->dias }} days</span>
                                <span class="card-tour__meta"><i class="fa fa-usd"></i> {{ $tour->precio }}.00</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section--tight section--sand">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Trusted by</span>
                <h2 class="section-title section-title--sm">Our Partners</h2>
            </div>
            <div class="partner-logos" data-reveal>
                <img src="{{ asset('img/logos/aatc.webp') }}" loading="lazy" alt="AATC Cusco">
                <img src="{{ asset('img/logos/marca_peru.webp') }}" loading="lazy" alt="Marca Perú">
                <img src="{{ asset('img/logos/regenera2.webp') }}" loading="lazy" alt="Regenera Cusco Perú">
                <img src="{{ asset('img/logos/tripadvisor.webp') }}" loading="lazy" alt="Trip Advisor">
                <img src="{{ asset('img/logos/PeruRail.webp') }}" loading="lazy" alt="Peru Rail">
            </div>
        </div>
    </section>

    @if ($blogs->count())
        <section class="section section--blog">
            <div class="site-container">
                <div class="section-header">
                    <span class="section-eyebrow font-display">From the journal</span>
                    <h2 class="section-title">Latest from the Blog</h2>
                    <p class="section-lead">Stories, tips and inspiration for your journey through Peru.</p>
                </div>
            </div>
            <div class="blog-rail" data-reveal data-blog-rail>
                <div class="blog-rail__track" tabindex="0" aria-label="Latest articles">
                    @foreach ($blogs as $blog)
                        <a href="{{ route('enblog.show', $blog->slug) }}" class="blog-card">
                            <div class="blog-card__media">
                                <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy">
                            </div>
                            <div class="blog-card__body">
                                @if ($blog->created_at)
                                    <span class="blog-card__date">{{ $blog->created_at->format('M d, Y') }}</span>
                                @endif
                                <h3 class="blog-card__title">{{ $blog->nombre }}</h3>
                                <span class="blog-card__more">Read article <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button type="button" class="blog-rail__nav blog-rail__nav--prev" data-rail-prev aria-label="Previous articles"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
                <button type="button" class="blog-rail__nav blog-rail__nav--next" data-rail-next aria-label="Next articles"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
            </div>
        </section>
    @endif

    <section class="section section--reviews">
        <span class="reviews-mark" data-float aria-hidden="true">&rdquo;</span>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">What travelers say</span>
                <h2 class="section-title">Guest Reviews</h2>
                <p class="section-lead">Real words from the travelers who trusted us with their journey through Peru.</p>
            </div>
            <div class="reviews-grid" data-reveal-stagger>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Rated 5 out of 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"On behalf of all Blue Parallel team, I would like to thank you a million for all the hard and excellent work over the past 12 years. It has been a real pleasure to work with you and we always felt that we could count on you regardless of the day/time and regardless of how challenging the request was."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">EB</span>
                        <span class="card-review__who">
                            <strong>Emmanuel Burgio</strong>
                            <small>President, Blue Parallel Inc.</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Rated 5 out of 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Thank you for our delicious lunch with you. We enjoyed it very much and also our weaving visit and guinea pig! You and your mother are twins! Thank you and until next time."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">AF</span>
                        <span class="card-review__who">
                            <strong>The Anderson Family</strong>
                            <small>Family trip</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Rated 5 out of 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"It has been a pleasure working together with Andean Exclusive team and I thank you very much for your professionalism, patience, support and dedication."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">AR</span>
                        <span class="card-review__who">
                            <strong>Travel Planner, Aracari</strong>
                            <small>Partner agency</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Rated 5 out of 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"We were extremely pleased with our tour. Carmen and her team are creative and attentive, the guides were top-notch and the attention was personalized. We were particularly pleased with those special touches and surprises, such as the picnic lunch in the beautiful countryside. We were treated like royalty at all times."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">GS</span>
                        <span class="card-review__who">
                            <strong>Grace, Susan &amp; Nancy</strong>
                            <small>Guests</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Rated 5 out of 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Our time in Peru was amazing and in some way we will be forever touched by our time spent there. Veronica, Freddie, Lucio and Mario were so outstanding that it is difficult for others to be compared!"</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">ER</span>
                        <span class="card-review__who">
                            <strong>Erna</strong>
                            <small>Traveler</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Rated 5 out of 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Our trip was a real success and the travellers left Peru with unforgettable memories. You were the architect of the trip, and I was happy to realize that your standards were as high as ever."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">JD</span>
                        <span class="card-review__who">
                            <strong>Jean-Louis Doss</strong>
                            <small>Longtime client</small>
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
            <div class="reviews__cta" data-reveal>
                <span class="section-eyebrow font-display">Ready for your own story?</span>
                <h3 class="reviews__cta-title">Start planning your trip to Peru</h3>
                <p class="reviews__cta-note">Tell us your dates and ideas &mdash; we reply within one business day.</p>
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>Plan my trip</button>
            </div>
        </div>
    </section>
@endsection
