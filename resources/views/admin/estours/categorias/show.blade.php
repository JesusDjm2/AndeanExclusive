@extends('layouts.general-es')
@section('metas')
    <title>{{ $categoria->nombre }}</title>
    <meta name="keywords" content="Listado de tour con la categoria: {{ $categoria->nombre }}" />
    <meta name="description" content="Listado de tour con la categoria: {{ $categoria->nombre }}" />
    <meta property="og:url" content="https://www.andeanexclusive.com">
    <meta property="og:title" content="{{ $categoria->nombre }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com" />
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="{{ route('index') }}" rel="home" class="logo-text-link">
                                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo"
                                        alt="Logo Andean Exclusive Tours">
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{ route('inicio') }}">
                                            <button type="button" class="botondjm">
                                                <i class="fa fa-language"></i> Español
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2">
                                        <a href="{{ route('inicio') }}">
                                            <button type="button" class="botondjm">Español</button>
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
        <div class="blogContenedor">
            <img src="{{ asset('img/full/Huayna-Picchu-Machu-Picchu.webp') }}" class="fullscreen-img">
            <div class="content-overlay">
                <h1>Categoria: {{ $categoria->nombre }}</h1>
            </div>
        </div>
    </div>
    <div class="container relacionados" style="margin-top:2em; margin-bottom:2em">
        <div class="row">
            @foreach ($tours as $tour)
                <div class="col-lg-4">
                    <div class="card">
                        <a href="{{ route('tour.show', $tour->slug) }}">
                            <img class="card-img-top" src="{{asset( $tour->imgThumb )}}" alt="{{ $tour->nombre }}"
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
                            <a href="{{ route('tour.show', $tour->slug) }}" class="boton2023">
                                Read more
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
