@extends('layouts.general-es')
@section('metas')
    <title>{{ $tour->nombre }}</title>
    <meta name="keywords" content="{{ $tour->keywords }}" />
    <meta name="description" content="{{ $tour->descripcionCorta }}" />
    <meta property="og:url" content="https://www.andeanexclusive.com/{{ $tour->slug }}">
    <meta property="og:title" content="{{ $tour->nombre }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ asset($tour->imgThumb) }}" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/{{ $tour->slug }}" />
@endsection
@section('contenido')
@auth
    <a href="{{ route('estours.edit', $tour->id) }}" class="boton-editar" target="_blank">Editar Tour</a>
@endauth
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="{{ route('inicio') }}" rel="home" class="logo-text-link"><img
                                        src="img/andean-exclusive-logo.png" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{ route('tour.show', $entour->slug) }}">
                                            <button type="button" class="botondjm">
                                                <i class="fa fa-language"></i> English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2">
                                        <a href="{{ route('tour.show', $entour->slug) }}">
                                            <button type="button" class="botondjm">English</button>
                                        </a>
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
        <div class="fullscreen-section">
            <img src="{{ $tour->imgFull }}" alt="Imagen Full" class="fullscreen-img">
            <div class="content-overlay">
                <h1>{{ $tour->nombre }}</h1>
                <p><i class="fa fa-map-marker"></i> {{ $tour->recorrido }}</p>
            </div>
        </div>
    </div>
    <div id="separador"></div>
    <div class="container">
        <div class="location">
            <div class="row text-center">
                <div class="col-lg-3 col-sm-2" id="display2">
                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 text-center">
                    <a href="{{ route('inicio') }}">Inicio</a> ⮞
                    <a href="{{ route('experiencias') }}">Exclusivos</a> ⮞
                    <a>{{ $tour->nombre }}</a>
                </div>
                <div class="col-lg-3 col-sm-2" id="display2">
                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-8">
                <div data-onepage-title="Services" style="text-align:justify" data-onepage-slug="services"
                    class="vc_row-fluid vc_custom_1461248392126">
                    <div class="text-center">
                        <h2 id="titulo1">{{ $tour->nombre }}</h2>
                        <div id="separadordjm2"></div>
                        <p class="text-center">
                            <i class="fa fa-clock-o"></i> {{ $tour->dias }} días <br>
                            <i class="fa fa-map-marker"></i> {{ $tour->recorrido }}
                        </p>
                    </div>
                    <div class="contenidoInicial" style="margin-top: 2em">
                        {!! $tour->presentacion !!}
                    </div>
                    <div id="separador"></div>
                    <div class="tabs">
                        <button class="tab-button active" data-tab="tab1"><i class="fa fa-list"></i>Itinerario detallado</button>
                        <button class="tab-button" data-tab="tab2"><i class="fa fa-plus"></i> Incluye</button>
                        <button class="tab-button" data-tab="tab3"><i class="fa fa-map-marker"></i> Mapa</button>
                        <button class="tab-button" data-tab="tab4"><i class="fa fa-exclamation"></i> Importante</button>
                    </div>

                    <div class="tab-content">
                        <div id="tab1" class="tab-pane active">
                            {!! $tour->itinerario !!}
                        </div>
                        <div id="tab2" class="tab-pane">
                            {!! $tour->incluye !!}
                        </div>
                        <div id="tab3" class="tab-pane">
                            @if ($tour->mapa)
                                {!! $tour->mapa !!}
                            @endif
                        </div>
                        <div id="tab4" class="tab-pane">
                            {!! $tour->importante !!}
                        </div>
                    </div>
                    <script>
                        const tabButtons = document.querySelectorAll('.tab-button');
                        const tabContent = document.querySelectorAll('.tab-pane');
                        tabButtons.forEach(button => {
                            button.addEventListener('click', () => {
                                tabButtons.forEach(btn => btn.classList.remove('active'));
                                tabContent.forEach(content => content.classList.remove('active'));
                                const tabId = button.getAttribute('data-tab');
                                button.classList.add('active');
                                document.getElementById(tabId).classList.add('active');
                            });
                        });
                    </script>


                    <div class="socialesShare">
                        <h3>Compartir</h3>
                        <div id="separadordjm2"></div>
                        <div class="redes-sociales">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/' . $tour->slug)) }}"
                                target="_blank" title="Compartir en Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="mailto:?subject=Recomendación de tour&body={{ urlencode('Te recomiendo este increíble tour: ' . url('/' . $tour->slug)) }}"
                                title="Compartir por correo electrónico">
                                <i class="fa fa-envelope"></i>
                            </a>
                            <a href="https://www.pinterest.com/pin/create/button/?url={{ urlencode(url('/' . $tour->slug)) }}"
                                target="_blank" title="Compartir en Pinterest">
                                <i class="fa fa-pinterest"></i>
                            </a>
                            <a href="whatsapp://send?text=https://www.andeanexclusive.com/{{ $tour->slug }}"
                                data-action="share/whatsapp/share" title="Compartir en WhatsApp">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/' . $tour->slug)) }}"
                                target="_blank" title="Compartir en Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" style="padding: 2em;position: sticky;top:3em">
                <div>
                    <div class="card">
                        <div class="text-center">
                            <h4 style="color: #3e3e3e; font-weight: 600; font-size:30px; font-family: FontAwesome">
                                <small style="font-size:12px; color:#000; text-transform:none; letter-spacing:1pt">Desde: </small> ${{ $tour->precio }}.00
                            </h4>
                        </div>
                        @include('layouts.booking-castellano')
                    </div>
                    <div id="similares">
                        <h4 class="text-center" style="font-family: 'Dancing Script', cursive">Similar Tours</h4>
                        <div id="separadordjm2"></div>
                        @foreach ($tours as $t)
                            <a href="{{ route('tour.show', $tour->slug) }}"><span>⮞</span> {{ $t->nombre }}</a>
                        @endforeach
                    </div>
                    <div id="similares">
                        <h3 style="font-family: 'Dancing Script', cursive; text-align: center; font-size: 1.8em; font-weight: 500">Categories of tours</h3>
                        <div id="separadordjm2"></div>
                        @foreach ($categorias as $categoria)
                            <a class="categorias" href="{{ route('categoria.show', $categoria->slug) }}"><span>·</span>
                                {{ $categoria->nombre }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
