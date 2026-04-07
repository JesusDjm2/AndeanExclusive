@extends('layouts.general-en')
@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'page' => null,
        'title' => $blog->nombre . ' | ' . config('seo.brand'),
        'description' => Str::limit(strip_tags($blog->descripcionCorta ?: $blog->descripcion), 165),
        'canonical' => route('enblog.show', $blog->slug, true),
        'og_image' => asset($blog->imgThumb),
        'og_type' => 'article',
        'keywords' => $blog->keywords,
    ])
@endsection
@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $blog->nombre,
    'description' => Str::limit(strip_tags($blog->descripcionCorta ?: $blog->descripcion), 300),
    'url' => route('enblog.show', $blog->slug, true),
    'image' => [asset($blog->imgThumb)],
    'datePublished' => $blog->created_at?->toIso8601String(),
    'dateModified' => $blog->updated_at?->toIso8601String(),
    'author' => ['@type' => 'Organization', 'name' => config('seo.brand')],
    'publisher' => ['@type' => 'Organization', 'name' => config('seo.brand'), 'logo' => ['@type' => 'ImageObject', 'url' => rtrim(config('seo.site_url'), '/') . '/img/andean-exclusive-logo.png']],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
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
                                        @if ($esblog && $esblog->blog)
                                            <a href="{{ route('esblog.show', $esblog->slug) }}">
                                                <button type="button" class="botondjm">
                                                    <i class="fa fa-language"></i> Español
                                                </button>
                                            </a>
                                        @else
                                            <a href="{{ route('inicio') }}">
                                                <button type="button" class="botondjm">
                                                    <i class="fa fa-language"></i> Español
                                                </button>
                                            </a>
                                        @endif
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2">
                                        @if ($esblog && $esblog->blog)
                                            <a href="{{ route('esblog.show', $esblog->slug) }}">
                                                <button type="button" class="botondjm">Español</button>
                                            </a>
                                        @else
                                            <a href="{{ route('inicio') }}">
                                                <button type="button" class="botondjm">Español</button>
                                            </a>
                                        @endif
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
            <img src="{{ asset('img/full/' . $blog->imgFull) }}" alt="{{ $blog->nombre }}"
                class="fullscreen-img zoom-infinite">
            <div class="content-overlay">
                <h1>{{ $blog->nombre }}</h1>
                <p>{{ $blog->descripcionCorta }}</p>
                @foreach ($blog->tags as $tag) 
                    <a href="{{ route('entag.show', $tag->slug) }}" class="tags">#{{ $tag->nombre }}</a>
                @endforeach
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
                    <a>{{ $blog->nombre }}</a>
                </div>
                <div class="col-lg-3 col-sm-2" id="display2">
                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <div data-onepage-title="Services" style="text-align:justify" data-onepage-slug="services"
                    class="vc_row-fluid vc_custom_1461248392126">
                    <div class="">
                        <h2 class="tituloBlog text-center">{{ $blog->nombre }}</h2>
                        <img src="{{ asset('img/full/' . $blog->imgFull) }}" alt="{{ $blog->nombre }}" loading="lazy"
                            width="100%">
                        <div class="contenidoBLogs rich-html-content">
                            {!! $blog->descripcion !!}
                        </div>
                    </div>
                    <div class="socialesShare">
                        <h3>Share</h3>
                        <div id="separadordjm2"></div>
                        <div class="redes-sociales">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/' . $blog->slug)) }}"
                                target="_blank" title="Compartir en Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="mailto:?subject=Recomendación de tour&body={{ urlencode('Te recomiendo este increíble tour: ' . url('/' . $blog->slug)) }}"
                                title="Compartir por correo electrónico">
                                <i class="fa fa-envelope"></i>
                            </a>
                            <a href="https://www.pinterest.com/pin/create/button/?url={{ urlencode(url('/' . $blog->slug)) }}"
                                target="_blank" title="Compartir en Pinterest">
                                <i class="fa fa-pinterest"></i>
                            </a>
                            <a href="whatsapp://send?text={{ urlencode(url('/' . $blog->slug)) }}"
                                data-action="share/whatsapp/share" target="_blank" title="Compartir en WhatsApp">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/' . $blog->slug)) }}"
                                target="_blank" title="Compartir en Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 blog-sidebar-related">
                <h4 class="text-center" style="font-family: 'Dancing Script', cursive;color:#000; font-size:25px">Similar
                    Blogs
                </h4>
                <div id="separadordjm2"></div>
                @foreach ($blogs as $item)
                    <div class="blogsRelacionados">
                        <div class="div">
                            <a href="{{ route('enblog.show', $item->slug) }}">
                                <img src="{{ asset('img/thumb/' . $item->imgThumb) }}" alt="{{ $item->nombre }}">
                            </a>
                            <a href="{{ route('enblog.show', $item->slug) }}">
                                <h3>{{ $item->nombre }}</h3>
                            </a>
                            <p><i class="fa fa-calendar"></i> {{ $item->created_at->format('d M, Y') }}</p>
                        </div>
                    </div>
                @endforeach
                <div class="tags">
                    <h3>Tags</h3>
                    <div class="div">
                        @foreach ($blog->tags as $tag)
                            <a href="{{ route('entag.show', $tag->slug) }}" class="enlaceTags">#{{ $tag->nombre }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row relacionados">
            <div class="col-12 mb-3">
                <h2>Exclusive Tours for Perú</h2>
            </div>
            @foreach ($tours->take(3) as $relatedTour)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <a href="{{ route('tour.show', $relatedTour->slug) }}">
                            <img class="card-img-top" src="{{ asset($relatedTour->imgThumb) }}" alt="{{ $relatedTour->nombre }}"
                                loading="lazy" decoding="async">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5>{{ $relatedTour->nombre }}</h5>
                            <div class="linea"></div>
                            <div class="recorrido">
                                <i class="fa fa-map-marker"></i>&nbsp;
                                <span>{{ $relatedTour->recorrido }}</span>
                            </div>
                            <p class="card-text flex-grow-1">{{ $relatedTour->descripcionCorta }}</p>
                            <div class="cuerpo row no-gutters">
                                <div class="col-sm-6">
                                    <p><i class="fa fa-usd"></i> {{ $relatedTour->precio }}.00</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="right"><i class="fa fa-clock-o"></i> {{ $relatedTour->dias }} days</p>
                                </div>
                            </div>
                            <div class="categorias">
                                @foreach ($relatedTour->categorias as $categoria)
                                    <a href="{{ route('category.show', $categoria->slug) }}">{{ $categoria->nombre }}</a>
                                    @if (!$loop->last)
                                        -&nbsp;
                                    @endif
                                @endforeach
                            </div>
                            <a href="{{ route('tour.show', $relatedTour->slug) }}" class="boton2023 mt-2">
                                Read more
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
