@extends('layouts.general-es')
@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'page' => 'blog',
        'canonical' => route('blog-es', [], true),
        'og_image' => '/img/blog-hiking-peru.jpg',
    ])
@endsection
@section('contenido')
    <div class="wrapper">
        @include('layouts.partials.header-public-es', ['enRoute' => 'blog-en'])
        <section class="section-content no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="blog-item blog-single">
                            <div class="entry-excerpt">
                                @if ($lastBlog)
                                <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                    data-onepage-slug="home"
                                    class="vc_row wpb_row vc_row-fluid vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex"
                                    style="background: url('{{ asset($lastBlog->imgFull) }}') !important; margin-bottom: 0px; background-position: center !important; background-repeat: no-repeat !important; background-size: cover !important;">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pvb0'>
                                                                    <h1>Blog de turismo en el Perú</h1>
                                                                    <p>Destacado: <a
                                                                            href="{{ route('esblog.show', $lastBlog->slug) }}"
                                                                            target="_blank" rel="noopener">{{ $lastBlog->nombre }}</a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="vc_row wpb_row vc_row-fluid vc_row-has-fill py-5"
                                    style="background: linear-gradient(135deg,#1e3a5f 0%,#0c8178 100%); margin-bottom: 0;">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner text-center text-white py-4">
                                            <h1 class="text-white">Blog de turismo en el Perú</h1>
                                            <p class="mb-0">Próximamente publicaremos artículos. Vuelva pronto o visite la versión en <a href="{{ route('blog-en') }}" class="text-white" style="text-decoration: underline;">inglés</a>.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="vc_row-full-width vc_clearfix"></div>
                                <div data-vc-full-width="true" data-vc-full-width-init="false"
                                    data-vc-stretch-content="true" data-onepage-title="Gallery"
                                    data-onepage-slug="our-gallery"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248454146 vc_row-no-padding">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner" style="padding-top: 0px">
                                            <div class="wpb_wrapper">
                                                <div class='travel-grid ' data-columns='3' data-col-class='.col-sm-4'>
                                                    <div class='grid-container'>
                                                        @foreach ($blogs as $blog)
                                                            <div class='masonry-item col-xs-12 col-sm-4 ftr-europe ftr-our-gallery'
                                                                style='padding: 0px;'>
                                                                <div class='travel-grid-item'>
                                                                    <img src='{{ asset($blog->imgThumb) }}'
                                                                        class="attachment-post-grid-m size-post-grid-m"
                                                                        alt="{{ $blog->nombre }}" loading="lazy" />
                                                                    <div class='entry-hover'>
                                                                        <div class='info'>
                                                                            <a
                                                                                href="{{ route('esblog.show', $blog->slug) }}">
                                                                                <h2>{{ $blog->nombre }}</h2>
                                                                            </a>
                                                                            <div class="entry-cat">
                                                                                <a>{{ $blog->descripcionCorta }}
                                                                                </a>
                                                                            </div> <br>
                                                                            <div class="readmore text-center">
                                                                                <a id="botonblog"
                                                                                    href="{{ route('esblog.show', $blog->slug) }}">Leer artículo</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
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
        </section>
    </div>
@endsection
