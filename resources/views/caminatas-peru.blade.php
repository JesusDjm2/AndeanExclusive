@extends('layouts.general-es')
@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'caminatas', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection
@section('contenido')
    <div class="wrapper">
        @include('layouts.partials.header-public-es', ['enRoute' => 'adventures'])
        <section class="section-content no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="blog-item blog-single">
                            <div class="entry-excerpt">
                                <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                    data-onepage-slug="home"
                                    class="vc_row wpb_row vc_row-fluid adventure vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    <h1>Tours de aventura en Perú</h1>
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
                        </article>
                    </div>
                </div>
            </div>
            <div class="container relacionados">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Adventure Tours in demand in Perú</h2>
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
                                            <a
                                                href="{{ route('category.show', $categoria->slug) }}">{{ $categoria->nombre }}</a>
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
