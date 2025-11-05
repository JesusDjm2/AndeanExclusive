@extends('layouts.general-en')
@section('metas')
    <title>Find your exclusive experience in Perú! travel this 2022</title>
    <meta name="keywords"
        content="Experiences in Perú, luxury tours, Lima, Cusco, Exclusive Cusco, Peru 2022, exclusive tours" />
    <meta name="description" content="We present you a list of luxury experiences for Peru." />
    <meta property="og:url" content="https://www.andeanexclusive.com/experiences">
    <meta property="og:title" content="Tours en Perú">
    <meta property="og:type" content="webside">
    <meta property="og:image" content="https://www.andeanexclusive.com/img/Machu-Picchu-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/experiences" />
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="/" rel="home" class="logo-text-link">
                                    <div class="logo2"></div>
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu')
                                    <li id="display" class="menu-has-children">
                                        <a href="{{ route('experiencias') }}">
                                            <button type="button" class="btn botondjm">
                                                <i class="fa fa-language"></i> Español
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="experiencias">ES</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a>ES</a>
                                    </li>
                                </ul>
                                <a href="javascript:;" id="mobile-menu"><span></span></a>
                                <a href="javascript:;" id="close-menu"></a>
                            </nav>

                        </div>

                    </div>
                </div>
            </div>
        </header>
        <section class="section-content no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="blog-item blog-single">
                            <div class="entry-excerpt">
                                <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                    data-onepage-slug="home"
                                    class="vc_row wpb_row vc_row-fluid experiencias vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    <h1>Exclusive - Private tours in Perú</h1>
                                                                    <div class="text-center">
                                                                        <div
                                                                            style="margin: auto;width:90px;height:4px;background-color:#14a89d">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>
                                <div data-onepage-title="Services" data-onepage-slug="services"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461227943574">
                                            <div class="wpb_wrapper text-center">
                                                <div style="height: 20px"></div>
                                                <h2>Exclusive tours in Perú</h2>
                                                <p style="text-align: justify">
                                                    If we are looking for a destination with the widest range of experiences
                                                    possible, without a doubt more than one traveler would say: visit Peru!
                                                    The
                                                    wealth that this South American country possesses is difficult to
                                                    surpass,
                                                    giving us the opportunity to visit places as dry as deserts
                                                    coasts of the south of the country, walk through glaciers through the
                                                    mountain range
                                                    Andean, navigate through the deep jungle that surrounds the Amazon
                                                    River,
                                                    visit ancient cities where lost civilizations resided or
                                                    simply taste one of the best valued cuisines on the planet.
                                                    <br>
                                                    Travelers look for destinations according to their needs. some seek
                                                    cultural experiences, others gastronomic and the most daring seek
                                                    a contact with nature, without discounting being able to do some
                                                    than any other sports activity. Peru offers this and much more, never
                                                    disappoints and always forces to repeat.<br><br>

                                                    Peru has the experience you were looking for so much. Every
                                                    experience has a hidden wealth. Find it!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            <div class="container relacionados">
                <div class="row">                    
                    @foreach ($tours as $tour)
                        <div class="col-lg-4">
                            <div class="card" style="margin-bottom: 2em">
                                <a href="{{ route('tour.show', $tour->slug) }}">
                                    <img class="card-img-top" src="{{ $tour->imgThumb }}" alt="{{ $tour->nombre }}"
                                        loading="lazy">
                                </a>
                                <div class="card-body">
                                    <h5>{{ $tour->nombre }}</h5>
                                    <div class="linea"></div>
                                    <div class="recorrido">
                                        <i class="fa fa-map-marker"></i>&nbsp;
                                        <span>{{ $tour->recorrido }}</span>
                                    </div>
                                    <p class="card-text">{{ $tour->descripcionCorta }}</p>
                                    <div class="cuerpo">
                                        <div class="col-sm-6">
                                            <p><i class="fa fa-usd"></i> {{ $tour->precio }}.00</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="right"><i class="fa fa-clock-o"></i> {{ $tour->dias }} days</p>
                                        </div>
                                    </div>
                                    <div class="categorias">
                                        @foreach ($tour->categorias as $categoria)
                                            <a href="{{ route('category.show', $categoria->slug) }}">{{ $categoria->nombre }}</a>
                                            @if (!$loop->last)
                                                -&nbsp;
                                            @endif
                                        @endforeach
                                    </div>
                                    <a href="{{ route('tour.show', $tour->slug) }}" class="boton2023">
                                        Read more
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
