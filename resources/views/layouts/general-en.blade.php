<!DOCTYPE html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    @yield('metas')
    <meta name="theme-color" content="#0c8178">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    @include('layouts.partials.seo-jsonld')
    @stack('seo-structured-data')
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="{{ asset('styles/bootstrap.minbb49.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' href='{{ asset('styles/js_composer.min5243.css') }}' type='text/css' media='all' />
    <link rel="stylesheet" href="{{ asset('css/wasa.css') }}" type="text/css">
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/estilo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nuevos.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TCY2R1G6QT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-TCY2R1G6QT');
    </script>
</head>

<body class="home page-template-default page page-id-44 wpb-js-composer js-comp-ver-5.4.5 vc_responsive">
    <a href="https://bit.ly/3kYXpXr" class="float" target="_blank" rel="nofollow">
        <i class="fa fa-whatsapp my-float"></i>
    </a>
    @yield('contenido')
    <footer id="footer" class="site-footer-public">
        <div class="container footer-container">
            <div class="row text-center footer-widgets-row">
                <div class="col-sm-6 col-md-4 pb-3 pb-md-0">
                    <div class="footer_widget widget widget_recent_entries">
                        <h5 class="widget-title">Query</h5>
                        <div id="separadordjm2"></div>
                        <ul class="list-unstyled mb-0 footer-link-list">
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('testimonials') }}">Testimonials</a></li>
                            <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                            <li><a href="{{ route('faqs') }}">FAQs</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 footer-column-4 pb-3 pb-md-0">
                    <div class="footer_widget widget widget_text">
                        <h5 class="widget-title">Contact</h5>
                        <div id="separadordjm2"></div>
                        <div class="textwidget footer-contact-block">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <a class="footer-link-muted" href="mailto:operaciones@andeanexclusive.com">operaciones@andeanexclusive.com</a><br>
                            <i class="fa fa-mobile" aria-hidden="true"></i>
                            <a class="footer-link-muted" href="https://bit.ly/3kYXpXr" target="_blank" rel="noopener">+51 979 721 194</a><br>
                            <i class="fa fa-phone" aria-hidden="true"></i> +51 084 242791<br>
                            <i class="fa fa-map-marker" aria-hidden="true"></i> Balconcillo Alto c-6 Cusco - Perú
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 footer-column-4 pb-3 pb-md-0">
                    <div class="footer_widget widget widget_search">
                        <h5 class="widget-title">Get in Touch</h5>
                        <div id="separadordjm2"></div>
                    </div>
                    <div class="footer_widget widget widget_social">
                        <div class="social-links">
                            <a href="https://www.facebook.com/AndeanExclusiveTours" target="_blank" rel="nofollow noopener"><i class="fa fa-facebook fa-2x" aria-hidden="true"></i></a>
                            <a href="https://twitter.com/AndeanExclusive" target="_blank" rel="nofollow noopener"><i class="fa fa-twitter fa-2x" aria-hidden="true"></i></a>
                            <a href="https://n9.cl/cjx4" target="_blank" rel="nofollow noopener"><i class="fa fa-tripadvisor fa-2x" aria-hidden="true"></i></a>
                            <a href="https://www.instagram.com/andean.exclusive/" target="_blank" rel="nofollow noopener"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i></a>
                            <a href="https://www.pinterest.com/andeanexclusive/" target="_blank" rel="nofollow noopener"><i class="fa fa-pinterest fa-2x" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sub-footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="widget mb-0">
                            <p class="footer-copy">Copyright &copy; {{ date('Y') }} Andean Exclusive Tours | All Rights Reserved | Designed by
                                <a href="https://www.facebook.com/DjmWebMaster" id="afoot" target="_blank" rel="nofollow noopener">DJM2</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script type='text/javascript' src='{{ asset('js/jquery4a5f.js') }}'></script>
    <script type='text/javascript' src='{{ asset('js/mediaelement-and-player.min45a0.js') }}'></script>
    <script type='text/javascript' src='{{ asset('js/swiper.minbb49.js?ver=5.2.2') }}'></script>
    <script type='text/javascript' src='{{ asset('js/isotope.pkgd.min5243.js?ver=5.4.5') }}'></script>
    <script type='text/javascript' src='{{ asset('js/scripts.js') }}'></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>
