@extends('layouts.general-en')
@section('metas')
    <title>Blog - Popular themes for Perú 2023 - Andean Exclusive Tours. </title>
    <meta name="keywords"
        content="Cusco tours, Luxury peru, Travel agency Peru, Peru travels, Exclusive Travel agency, Blog for peru, Travel agency Cusco." />
    <meta name="description"
        content="Peru blog, find the most popular tourism topics. ✅ Enjoy the magic of this amazing land.">
    <meta property="og:url" content="https://www.andeanexclusive.com/blog" />
    <meta property="og:title" content="Blog - Popular themes 2022 - Andean Exclusive Tours.">
    <meta property="og:type" content="webside">
    <meta property="og:description"
        content="A luxury travel agency in Cusco - Perú. ✅ Enjoy the magic of this amazing land." />
    <meta property="og:image" content="https://www.andeanexclusive.com/img/blog-hiking-peru.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog" />
@endsection
@section('contenido')
    <!--End Boton wasa-->
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="/" rel="home" class="logo-text-link"><img
                                        src="img/andean-exclusive-logo.png" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu')
                                    <li id="display">
                                        <a href="{{ route('blog-es') }}">
                                            <button type="button" class="btn botondjm">
                                                <i class="fa fa-language"></i> Español
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2">
                                        <a href="{{ route('blog-es') }}">
                                            <button type="button" class="btn botondjm">Español</button>
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
        <section class="section-content no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="blog-item blog-single">
                            <div class="entry-excerpt">
                                <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                    data-onepage-slug="home"
                                    class="vc_row wpb_row vc_row-fluid vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex"
                                    style="background: url('{{ asset('img/full/' . $lastBlog->imgFull) }}') !important; margin-bottom: 0px !important;                                    
                                    background-position: center !important; background-repeat: no-repeat !important; background-size: cover !important;">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pvb0'>
                                                                    <h1>Blog Perú </h1>
                                                                    <p>Image: <a
                                                                            href="{{ route('enblog.show', $lastBlog->slug) }}"
                                                                            target="_blank"> {{ $lastBlog->nombre }}</a>
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
                                <div class="vc_row-full-width vc_clearfix"></div>
                                <div data-vc-full-width="true" data-vc-full-width-init="false"
                                    data-vc-stretch-content="true" data-onepage-title="Gallery"
                                    data-onepage-slug="our-gallery"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248454146 vc_row-no-padding">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner" style="padding-top: 0px">
                                            <div class="wpb_wrapper">
                                                <div class='travel-grid' data-columns='3' data-col-class='.col-sm-4'>
                                                    <div class='grid-container'>
                                                        @foreach ($blogs as $blog)
                                                            <div class='masonry-item col-xs-12 col-sm-4 ftr-europe ftr-our-gallery'
                                                                style='padding: 0px;'>
                                                                <div class='travel-grid-item'>
                                                                    <img src='{{ asset('img/thumb/' . $blog->imgThumb) }}'
                                                                        class="attachment-post-grid-m size-post-grid-m"
                                                                        alt="{{ $blog->nombre }}" loading="lazy" />
                                                                    <div class='entry-hover'>
                                                                        <div class='info'>
                                                                            <a
                                                                                href="{{ route('enblog.show', $blog->slug) }}">
                                                                                <h2>{{ $blog->nombre }}</h2>
                                                                            </a>
                                                                            <div class="entry-cat">
                                                                                <a>{{ $blog->descripcionCorta }}
                                                                                </a>
                                                                            </div> <br>
                                                                            <div class="readmore text-center">
                                                                                <a id="botonblog"
                                                                                    href="{{ route('enblog.show', $blog->slug) }}">Read
                                                                                    article</a>
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
