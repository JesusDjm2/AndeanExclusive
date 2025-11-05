@extends('layouts.general-en')
@section('metas')
    <title>{{ $tag->nombre }}</title>
    <meta name="keywords" content="{{ $tag->keywords }}" />
    <meta name="description" content="" />
    <meta property="og:url" content="https://www.andeanexclusive.com/entag/{{ $tag->slug }}">
    <meta property="og:title" content="{{ $tag->nombre }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ asset($tag->imgThumb) }}" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/entag/{{ $tag->slug }}" />
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
                                    @include('layouts.menu')
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
            <img src="{{ asset('img/tours/Hiram-bingham.webp') }}" class="fullscreen-img">
            <div class="content-overlay">
                <h1>Blog tagged: {{ $tag->nombre }}</h1>
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
                    <a href="{{ route('index') }}">Home</a> <span>⮞</span>
                    <a href="{{ route('blog-en') }}">Blog</a> <span>⮞</span>
                    <a>{{ $tag->nombre }}</a>
                </div>
                <div class="col-lg-3 col-sm-2" id="display2">
                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 2em">
            <div class="col-lg-9">
                <div class="row">
                    @foreach ($blogs as $tour)
                        <div class="col-md-6 relaciones" style="margin-bottom: 2em">
                            <div class="card">
                                <a href="{{ route('enblog.show', $tour->slug) }}">
                                    <img class="card-img-top" src="{{ asset('img/thumb/' . $tour->imgThumb) }}"
                                        alt="{{ $tour->nombre }}" loading="lazy" style="width: 100%; height: 220px; object-fit:cover ">
                                </a>
                                <div class="card-body" style="height: 220px; max-height:240px">
                                    <h5 class="text-center"
                                        style="font-family: 'FontAwesome';font-weight: bold; color: #0c8178;">
                                        {{ $tour->nombre }}</h5>
                                    <div class="linea"></div>
                                    <div class="categorias">
                                        <p>
                                            {{ $tour->descripcionCorta }}
                                        </p>
                                    </div>
                                    <a href="{{ route('enblog.show', $tour->slug) }}" class="boton2023">
                                        Read more
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-3" style="position: sticky;top:5em">
                <div class="tags">
                    <h3>Tags</h3>
                    <div class="div">
                        @foreach ($tags as $tag)
                            <a href="{{ route('entag.show', $tag->slug) }}" class="enlaceTags">#{{ $tag->nombre }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
