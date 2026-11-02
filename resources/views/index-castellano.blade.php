@php
    $locale = 'es';
    $altRoute = 'index';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'home', 'og_image' => '/img/machupicchu.png'])
@endsection

@section('contenido')
    <section class="hero">
        <div class="hero__media" data-parallax-container>
            <img src="{{ asset('img/machupicchu.webp') }}" alt="Machu Picchu, Cusco - Perú" loading="eager" fetchpriority="high" data-parallax="12" data-breathe>
        </div>
        <div class="hero__content">
            <h1 class="hero__title">Tours de lujo en Perú, hechos exclusivamente para ti</h1>
            <div class="hero__divider"></div>
            <p class="hero__subtitle">
                Disfruta de los mejores tours, viajes y paquetes vacacionales en Perú — las experiencias más
                exclusivas y de lujo, hechas a tu medida.
            </p>
            @include('layouts.partials.form-consulta-rapida', ['locale' => 'es'])
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Seleccionados para ti</span>
                <h2 class="section-title">Más Solicitados</h2>
                <p class="section-lead">Los mejores tours y paquetes de viaje en el Perú. Experiencias de lujo y exclusivas para usted.</p>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $tour)
                    <a href="{{ route('estour.show', $tour->slug) }}" class="card-tour">
                        <div class="card-tour__media">
                            <img src="{{ asset($tour->imgThumb) }}" alt="{{ $tour->nombre }}" loading="lazy">
                        </div>
                        <div class="card-tour__body">
                            <h3 class="card-tour__title">{{ $tour->nombre }}</h3>
                            <p class="card-tour__excerpt">{{ $tour->descripcionCorta }}</p>
                            <div class="flex-between mt-xs">
                                <span class="card-tour__meta"><i class="fa fa-clock-o"></i> {{ $tour->dias }} {{ $tour->dias == 1 ? 'día' : 'días' }}</span>
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
                <span class="section-eyebrow font-display">Dónde te hospedarás</span>
                <h2 class="section-title">Los Hoteles Más Prestigiosos del Perú</h2>
                <p class="section-lead">No siempre son los hoteles que más reservamos, sino los que marcan el estándar de la hospitalidad de lujo en el país. De íconos coloniales de Belmond en Cusco a eco-lodges escondidos en la Amazonía, cada nombre de esta lista es sinónimo de excelencia peruana, y el referente contra el que diseñamos cada itinerario.</p>
            </div>
        </div>
        @php
            $hotels = [
                ['img' => 'img/hotels/belmond-nazarenas-2.jpg', 'place' => 'Cusco', 'name' => 'Belmond Hotel Nazarenas', 'desc' => 'Un convento colonial del siglo XVI convertido en un santuario de suites, a pasos de la Plaza de Armas de Cusco.',
                    'gallery' => ['img/hotels/belmond-nazarenas-2.jpg', 'img/hotels/belmond-nazarenas-3.jpg', 'img/hotels/belmond-nazarenas-1.jpg', 'img/hotels/belmond-nazarenas-4.jpg', 'img/hotels/belmond-nazarenas-5.jpg']],
                ['img' => 'img/hotels/sol-y-luna-1.jpg', 'place' => 'Valle Sagrado', 'name' => 'Sol & Luna Hotel', 'desc' => 'Casitas de techo de paja rodeadas de jardines, en pleno corazón del Valle Sagrado.',
                    'gallery' => ['img/hotels/sol-y-luna-1.jpg', 'img/hotels/sol-y-luna-2.jpg', 'img/hotels/sol-y-luna-4.jpg', 'img/hotels/sol-y-luna-5.jpg', 'img/hotels/sol-y-luna-3.jpg']],
                ['img' => 'img/hotels/sanctuary-lodge-1.jpg', 'place' => 'Machu Picchu', 'name' => 'Sanctuary Lodge Hotel', 'desc' => 'El único hotel a la entrada de Machu Picchu, con acceso privilegiado a la ciudadela al amanecer.',
                    'gallery' => ['img/hotels/sanctuary-lodge-1.jpg', 'img/hotels/sanctuary-lodge-2.jpg', 'img/hotels/sanctuary-lodge-3.jpg', 'img/hotels/sanctuary-lodge-4.jpg', 'img/hotels/sanctuary-lodge-5.jpg']],
                ['img' => 'img/hotels/miraflores-park-5.jpg', 'place' => 'Lima', 'name' => 'Miraflores Park Hotel', 'desc' => 'Una torre frente al mar sobre el Malecón de Lima, con vistas panorámicas a la ciudad y al Pacífico.',
                    'gallery' => ['img/hotels/miraflores-park-5.jpg', 'img/hotels/miraflores-park-1.jpg', 'img/hotels/miraflores-park-2.jpg', 'img/hotels/miraflores-park-3.jpg', 'img/hotels/miraflores-park-4.jpg']],
                ['img' => 'img/hotels/titilaka-1.jpg', 'place' => 'Puno', 'name' => 'Titilaka Hotel', 'desc' => 'Un lodge de suites en su propia península privada sobre el lago Titicaca, el lago navegable más alto del mundo.',
                    'gallery' => ['img/hotels/titilaka-1.jpg', 'img/hotels/titilaka-2.jpg', 'img/hotels/titilaka-3.jpg', 'img/hotels/titilaka-4.jpg']],
                ['img' => 'img/hotels/reserva-amazonica-1.jpg', 'place' => 'Puerto Maldonado', 'name' => 'Reserva Amazonas Hotel', 'desc' => 'Un eco-lodge carbono neutral, en el corazón de una reserva privada de 17.000 hectáreas en la Amazonía.',
                    'gallery' => ['img/hotels/reserva-amazonica-1.jpg', 'img/hotels/reserva-amazonica-2.jpg', 'img/hotels/reserva-amazonica-3.jpg', 'img/hotels/reserva-amazonica-4.jpg']],
                ['img' => 'img/hotels/hotel-b-1.jpg', 'place' => 'Lima', 'name' => 'B Hotel', 'desc' => 'La única dirección Relais & Châteaux de Lima, una mansión Belle Époque convertida en hotel boutique de arte en Barranco.',
                    'gallery' => ['img/hotels/hotel-b-1.jpg', 'img/hotels/hotel-b-2.jpg', 'img/hotels/hotel-b-3.jpg', 'img/hotels/hotel-b-4.jpg', 'img/hotels/hotel-b-5.jpg']],
                ['img' => 'img/hotels/casa-cartagena-1.jpg', 'place' => 'Cusco', 'name' => 'Casa Cartagena', 'desc' => 'Una mansión colonial del siglo XVI en el centro histórico de Cusco, reinventada como hotel boutique y spa cinco estrellas.',
                    'gallery' => ['img/hotels/casa-cartagena-1.jpg', 'img/hotels/casa-cartagena-2.jpg', 'img/hotels/casa-cartagena-3.jpg', 'img/hotels/casa-cartagena-4.jpg', 'img/hotels/casa-cartagena-5.jpg']],
                ['img' => 'img/hotels/casona-inkaterra-1.jpg', 'place' => 'Valle Sagrado', 'name' => 'Casona Inkaterra', 'desc' => 'Una hacienda contemporánea sobre 100 acres del Valle Sagrado, enmarcada por los Andes.',
                    'gallery' => ['img/hotels/casona-inkaterra-1.jpg', 'img/hotels/casona-inkaterra-2.jpg', 'img/hotels/casona-inkaterra-3.jpg']],
            ];
        @endphp
        @include('layouts.partials.hotel-wall', [
            'hotels' => $hotels,
            'labels' => [
                'cta' => 'Ver fotos', 'open' => 'Ver galería de fotos', 'close' => 'Cerrar galería',
                'prev' => 'Foto anterior', 'next' => 'Foto siguiente', 'dialog' => 'Galería de fotos del hotel',
            ],
        ])
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Explora Perú</span>
                <h2 class="section-title">Destinos Populares</h2>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours2 as $tour)
                    <a href="{{ route('estour.show', $tour->slug) }}" class="card-tour">
                        <div class="card-tour__media">
                            <img src="{{ asset($tour->imgThumb) }}" alt="{{ $tour->nombre }}" loading="lazy">
                        </div>
                        <div class="card-tour__body">
                            <h3 class="card-tour__title">{{ $tour->nombre }}</h3>
                            <p class="card-tour__excerpt">{{ $tour->descripcionCorta }}</p>
                            <div class="flex-between mt-xs">
                                <span class="card-tour__meta"><i class="fa fa-clock-o"></i> {{ $tour->dias }} {{ $tour->dias == 1 ? 'día' : 'días' }}</span>
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
                <span class="section-eyebrow font-display">Confían en nosotros</span>
                <h2 class="section-title section-title--sm">Nuestros Aliados</h2>
            </div>
            <div class="partner-logos" data-reveal>
                <img src="{{ asset('img/logos/aatc.webp') }}" loading="lazy" alt="AATC Cusco">
                <img src="{{ asset('img/logos/marca_peru.webp') }}" loading="lazy" alt="Marca Perú">
                <img src="{{ asset('img/logos/regenera2.webp') }}" loading="lazy" alt="Regenera Cusco Perú">
                <img src="{{ asset('img/logos/tripadvisor.webp') }}" loading="lazy" alt="TripAdvisor">
                <img src="{{ asset('img/logos/PeruRail.webp') }}" loading="lazy" alt="PeruRail">
            </div>
        </div>
    </section>

    @if ($blogs->count())
        <section class="section section--blog">
            <div class="site-container">
                <div class="section-header">
                    <span class="section-eyebrow font-display">Desde el blog</span>
                    <h2 class="section-title">Últimos Artículos</h2>
                    <p class="section-lead">Historias, consejos e inspiración para tu viaje por el Perú.</p>
                </div>
            </div>
            <div class="blog-rail" data-reveal data-blog-rail>
                <div class="blog-rail__track" tabindex="0" aria-label="Últimos artículos">
                    @foreach ($blogs as $blog)
                        <a href="{{ route('esblog.show', $blog->slug) }}" class="blog-card">
                            <div class="blog-card__media">
                                <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy">
                            </div>
                            <div class="blog-card__body">
                                @if ($blog->created_at)
                                    <span class="blog-card__date">{{ $blog->created_at->format('d/m/Y') }}</span>
                                @endif
                                <h3 class="blog-card__title">{{ $blog->nombre }}</h3>
                                <span class="blog-card__more">Leer artículo <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button type="button" class="blog-rail__nav blog-rail__nav--prev" data-rail-prev aria-label="Artículos anteriores"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
                <button type="button" class="blog-rail__nav blog-rail__nav--next" data-rail-next aria-label="Artículos siguientes"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
            </div>
        </section>
    @endif

    <section class="section section--reviews">
        <span class="reviews-mark" data-float aria-hidden="true">&rdquo;</span>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Lo que dicen los viajeros</span>
                <h2 class="section-title">Opiniones de Viajeros</h2>
                <p class="section-lead">Nos importa su experiencia. Trabajamos cada día para ofrecer un servicio impecable, cercano y a la altura de un viaje de lujo por el Perú.</p>
            </div>
            <div class="reviews-grid" data-reveal-stagger>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Calificado con 5 de 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"En nombre de todo el equipo de Blue Parallel, quiero agradecerles muchísimo por el excelente trabajo durante los últimos 12 años. Ha sido un verdadero placer trabajar con ustedes."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">EB</span>
                        <span class="card-review__who">
                            <strong>Emmanuel Burgio</strong>
                            <small>Presidente, Blue Parallel Inc.</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Calificado con 5 de 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Gracias por el delicioso almuerzo. Disfrutamos mucho la visita a tejidos y a los cuyes. ¡Tú y tu madre son gemelas! Gracias y hasta la próxima."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">FA</span>
                        <span class="card-review__who">
                            <strong>Familia Anderson</strong>
                            <small>Viaje familiar</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Calificado con 5 de 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Ha sido un placer trabajar junto al equipo de Andean Exclusive, les agradezco mucho su profesionalismo, paciencia, apoyo y dedicación."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">AR</span>
                        <span class="card-review__who">
                            <strong>Travel Planner, Aracari</strong>
                            <small>Agencia aliada</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Calificado con 5 de 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Estuvimos muy satisfechas con nuestro tour. Carmen y su equipo son creativos y atentos, los guías fueron de primer nivel y la atención fue personalizada. Disfrutamos mucho los detalles y sorpresas especiales, como el almuerzo campestre en medio de la campiña. Nos trataron como realeza en todo momento."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">GS</span>
                        <span class="card-review__who">
                            <strong>Grace, Susan &amp; Nancy</strong>
                            <small>Clientas</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Calificado con 5 de 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Nuestro tiempo en Perú fue increíble y de alguna manera nos marcará para siempre. Veronica, Freddie, Lucio y Mario fueron tan sobresalientes que es difícil compararlos con otros."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">ER</span>
                        <span class="card-review__who">
                            <strong>Erna</strong>
                            <small>Viajera</small>
                        </span>
                    </figcaption>
                </figure>
                <figure class="card-review">
                    <span class="card-review__mark" aria-hidden="true">&ldquo;</span>
                    <div class="card-review__stars" aria-label="Calificado con 5 de 5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <blockquote class="card-review__quote"><p>"Nuestro viaje fue un verdadero éxito y los viajeros se llevaron recuerdos inolvidables del Perú. Usted fue la arquitecta del viaje y me alegré de comprobar que sus estándares siguen tan altos como siempre."</p></blockquote>
                    <figcaption class="card-review__byline">
                        <span class="card-review__avatar" aria-hidden="true">JD</span>
                        <span class="card-review__who">
                            <strong>Jean-Louis Doss</strong>
                            <small>Cliente de siempre</small>
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
            <div class="reviews__cta" data-reveal>
                <span class="section-eyebrow font-display">&iquest;Lista para tu propia historia?</span>
                <h3 class="reviews__cta-title">Comienza a planificar tu viaje al Per&uacute;</h3>
                <p class="reviews__cta-note">Cu&eacute;ntanos tus fechas e ideas &mdash; respondemos en menos de un d&iacute;a h&aacute;bil.</p>
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>Planificar mi viaje</button>
            </div>
        </div>
    </section>
@endsection
