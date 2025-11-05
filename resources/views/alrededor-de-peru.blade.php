@extends('layouts.general-es')
@section('metas')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <title>Tours alrededor de Perú, encuentre tours por Perú!</title>
    <meta name="keywords" content="Experiences in Perú, luxury tours, Lima, Cusco, Exclusive Cusco" />
    <meta name="description" content="We present you a list of luxury experiences for Peru." />
    <meta property="og:url" content="https://www.andeanexclusive.com/alrededor-de-peru">
    <meta property="og:title" content="Tours en Perú">
    <meta property="og:type" content="webside">
    <meta property="og:image" content="https://www.andeanexclusive.com/img/Machu-Picchu-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/alrededor-de-peru" />
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="{{route('inicio')}}" rel="home" class="logo-text-link">
                                    <div class="logo2"></div>
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display" class="menu-has-children">
                                        <a href="{{ route('around') }}">
                                            <button type="button" class="btn botondjm">
                                                <i class="fa fa-language"></i> English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="#">ES</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="around">EN</a>
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
                                    class="vc_row wpb_row vc_row-fluid around vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv5 pvb0'>
                                                                    <h1>Tours alrededor de Perú</h1>
                                                                    <div class="text-center">
                                                                        <div
                                                                            style="margin: auto;width:90px;height:4px;background-color:#0c8178">
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
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126 text-center">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461227943574">
                                            <div class="wpb_wrapper">
                                                <div class='heading  text-center '>
                                                    <div id="separador"></div>
                                                    <h3>Más solicitados</h3>
                                                    <div id="separadordjm"></div>
                                                </div>
                                            </div>
                                            <div id="separador"></div>
                                            <div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1461228476759">
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228417147">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href='cusco-4-dias-3-noches' class='entry-link'><img
                                                                            srcset="img/thumbnail/vacaciones-familiares-peru.jpg 1000w, img/thumbnail/vacaciones-familiares-peru.jpg 500w"
                                                                            alt="Cusco tour" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href='cusco-4-dias-3-noches' class='button'>Perú
                                                                            por 4 days</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-5-dias-4-noches" class='entry-link'><img
                                                                            src="img/thumbnail/llama-peru.JPG"
                                                                            alt="llamas Peru" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a class='button' href="peru-5-dias-4-noches">Perú
                                                                            por 5 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228417147">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-6-dias-5-noches" class='entry-link'><img
                                                                            alt="Machu Picchu - Cusco - Perú"
                                                                            src="img/thumbnail/Machupicchu_AET.jpg" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href="peru-6-dias-5-noches" class='button'>Perú
                                                                            por 6 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-7-dias-6-noches" class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/cusco-01.JPG"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="Tour in Cusco"
                                                                            srcset="img/thumbnail/cusco-01.JPG 400w"
                                                                            sizes="(max-width: 400px) 100vw, 400px" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href="peru-7-dias-6-noches" class='button'>perú
                                                                            por 7 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="separador"></div>
                                            <div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1461228476759">
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228442150">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-8-dias-7-noches" class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/Machupicchu.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="Tren de ida a Machu Picchu"
                                                                            srcset="img/thumbnail/Machupicchu.jpg 400w"
                                                                            sizes="(max-width: 400px) 100vw, 400px" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href="peru-8-dias-7-noches" class='button'>Perú
                                                                            por 8 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228442150">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-9-dias-8-noches" class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/urubamba-market.png"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="urubamba market - cusco"
                                                                            sizes="(max-width: 400px) 100vw, 400px" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href="peru-9-dias-8-noches" class='button'>Perú
                                                                            por 9 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-10-dias-9-noches"
                                                                        class='entry-link'><img width="400"
                                                                            height="400"
                                                                            src="img/thumbnail/biking-cusco.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="biking cusco sacred valley" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a class='button'
                                                                            href="peru-10-dias-9-noches">Perú por 10
                                                                            días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-12-dias" class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/chincheros.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="Sacred Valley Cusco"
                                                                            srcset="img/thumbnail/chincheros.jpg 400w"
                                                                            sizes="(max-width: 400px) 100vw, 400px" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a class='button' href="peru-12-dias">Perú por 12
                                                                            días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="separador"></div>
                                            <div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1461228476759">
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228417147">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href="peru-13-dias" class='entry-link'><img
                                                                            src="img/thumbnail/puno-peru.JPG"
                                                                            alt="tours in Puno" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href='peru-13-dias' class='button'>Perú por 13
                                                                            días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228442150">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href='peru-15-dias' class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/arequipa-peru.JPG"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="Arequipa perú" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href="peru-15-dias" class='button'>Perú por 15
                                                                            días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href='peru-18-dias-tour' class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/ceviche-gambas.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href='peru-18-dias-tour' class='button'>Perú
                                                                            por 18 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a href='peru-20-dias-tour' class='entry-link'><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/mono-amazonas.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="mono amazonas" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <div class='readmore'>
                                                                        <a href='peru-20-dias-tour' class='button'>Perú
                                                                            por 20 días</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="separador"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="vc_row-full-width vc_clearfix"></div>
                    </div>
                    </article>
                </div>
            </div>
            <div class="container relacionados">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Tours in demand around Perú</h2>
                    </div>
                    @foreach ($tours as $tour)
                        <div class="col-lg-4">
                            <div class="card" style="margin-bottom: 2em">
                                <a href="{{ route('estour.show', $tour->slug) }}">
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
                                            <a href="{{ route('categoria.show', $categoria->slug) }}">{{ $categoria->nombre }}</a>
                                            @if (!$loop->last)
                                                -&nbsp;
                                            @endif
                                        @endforeach
                                    </div>
                                    <a href="{{ route('estour.show', $tour->slug) }}" class="boton2023">
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
