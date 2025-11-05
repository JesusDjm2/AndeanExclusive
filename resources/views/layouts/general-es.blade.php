<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="{{asset('styles/bootstrap.minbb49.css')}}" type='text/css' media='all' />
    <link rel='stylesheet' href='{{asset('styles/js_composer.min5243.css')}}' type='text/css' media='all' />
    <link rel="stylesheet" href="{{asset('css/wasa.css')}}" type="text/css">
    <link rel="icon" type="image/png" href="{{asset('img/logoico.png')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/estilo.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('font-awesome/css/font-awesome.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/nuevos.css') }}">
    @yield('metas')
</head>

<body class="home page-template-default page page-id-44 wpb-js-composer js-comp-ver-5.4.5 vc_responsive">
    <link rel="stylesheet" href="{{ asset('css/wasa.css') }}">
    <!--Boton chat de wasa-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a href="https://bit.ly/3kYXpXr" class="float" target="_blank" rel="nofollow">
        <i class="fa fa-whatsapp my-float"></i>
    </a>
    @yield('contenido')
    <div class="col-12 text-center">
        <a href="javascript:toUp()" id="arriba"><i class="fa fa-caret-up"></i></a>
        <script type='text/javascript'>
            mybutton = document.getElementById("arriba");
            window.onscroll = function() {
                scrollFunction()
            };

            function scrollFunction() {
                if (document.body.scrollTop > 1200 || document.documentElement.scrollTop > 1200) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
            }

            function toUp() {
                document.documentElement.scrollTop = 0;
            }
        </script>
    </div>
    <footer id="footer">
        <div class="container footer-container">
            <div class="row text-center">
                <div class='col-sm-6 col-md-4'>
                    <div id="recent-posts-3" class="footer_widget widget widget_recent_entries">
                        <h5 class="widget-title">Consulta</h5>
                        <div id="separadordjm2"></div>
                        <ul>
                            <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                            <li><a href="{{ route('testimonios') }}">Testimonios</a></li>
                            <li><a href="{{ route('terms') }}">Términos & Condiciones</a></li>
                            <li><a href="{{ route('preguntas-frecuentes') }}">Preguntas Frecuentes</a></li>
                        </ul>
                    </div>
                </div>
                <div class='col-sm-6 col-md-4 footer-column-4'>
                    <div id="text-3" class="footer_widget widget widget_text">
                        <h5 class="widget-title">Contacto</h5>
                        <div id="separadordjm2"></div>
                        <div class="textwidget">
                            <i class="fa fa-envelope"></i><a style="color: rgba(248, 248, 248, 0.5)"
                                href="mailto:operaciones@andeanexclusive.com"> operaciones@andeanexclusive.com</a>
                            <br>
                            <i class="fa fa-mobile"></i> <a href='https://bit.ly/3kYXpXr' target="_blank"
                                style="color: rgba(248, 248, 248, 0.5)"> +51 979 721 194</a>
                            <br>
                            <i class="fa fa-phone"></i> +51 084 242791
                            <br>
                            <i class="fa fa-map-marker"></i> Balconcillo Alto c-6 Cusco - Perú
                        </div>
                    </div>
                </div>
                <div class='col-sm-6 col-md-4 footer-column-4'>
                    <div id="search-4" class="footer_widget widget widget_search">
                        <h5 class="widget-title">Sociales</h5>
                        <div id="separadordjm2"></div>
                    </div>
                    <div id="tt_sociallinkswidget-4" class="footer_widget widget widget_social">
                        <div class="social-links">
                            <a href="https://www.facebook.com/AndeanExclusiveTours" rel="nofollow" target="_blank"><i
                                    class="fa fa-facebook fa-2x"></i></a>
                            <a href="https://twitter.com/AndeanExclusive" rel="nofollow" target="_blank"><i
                                    class="fa fa-twitter fa-2x"></i></a>
                            <a href="https://n9.cl/cjx4" target="_blank" rel="nofollow"><i
                                    class="fa fa-tripadvisor fa-2x"></i></a>
                            <a href="https://www.instagram.com/andean.exclusive/" rel="nofollow" target="_blank"><i
                                    class="fa fa-instagram fa-2x"></i></a>
                            <a href="https://www.pinterest.com/andeanexclusive/" rel="nofollow" target="_blank"><i
                                    class="fa fa-pinterest fa-2x"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sub-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="widget">
                                <p class="text-center mt-5">Copyright 2019 &copy; Andean Exclusive Tours | Todos los
                                    derechos reservados | <a href="https://www.facebook.com/DjmWebMaster"
                                        rel="nofollow" id="afoot" target="_blank">DJM2</a>
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>
