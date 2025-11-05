<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Enjoy a fantastic picnic Lunch in the Andes! Andean Exclusive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="Picnic Lunch, Lunch in the andes, Picnic, Picnic Tour, luxury Picnic, luxury picnic Perú, Amazing views, amazing lunch, lunch with beautiful view, Andean Picnic Lunch, Andean Picnic, Picnic Lunch Peru, Picnic Lunch Cusco" />
    <meta name="description"
        content="Today I bring you a post that is not about clothing or clothing trends, but about a fashion that is entering forcefully into Peru." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/picnic">
    <meta property="og:title" content="Enjoy a fantastic pincin Lunch in the Andes!">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/andean-exclusive-cusco-picnic.JPG" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/picnic">
    @include('layouts.links')
    @include('layouts.analitycs')
</head>

<body class="home page-template-default page page-id-44 wpb-js-composer js-comp-ver-5.4.5 vc_responsive"
    style="font-size: 14px; font-weight: 400; color:#131313">

    @include('layouts.wasa')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="{{ route('index') }}" rel="home" class="logo-text-link">
                                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo">
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu')
                                    <li id="display">
                                        <a href="{{ route('picnic-castellano') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('picnic-castellano') }}">
                                            <button type="button" class="botondjm">Español </button>
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
        <div class="container-fluid" style="height: 130px; background:#000">
        </div>
    </div>
    <section>
        <div class="container">
            <div class="row ubicacion">
                <div class="col-lg-3 col-sm-2">
                    <div class="line"></div>
                </div>
                <div class="col-lg-6 col-sm-10 text-center"><a href="{{ route('index') }}">Index</a> /
                    <a href="{{ route('blog-en') }}">Blog</a> /
                    <a>Picnic in the Andes</a>
                </div>
                <div class="col-lg-3 col-sm-2">
                    <div class="line"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="sticky-top" style="top:100px; z-index: 40">
                        <h4 class="text-center">Table of Contents</h4>
                        <div id="separadordjm2"></div>
                        <ul class="tabla-de-contenido" style="list-style:none;">
                            <li><i class="fa fa-caret-right"></i> <a href="#description">Description</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#realizacion">How is the Picnic Lunch made?</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#foods">Picnic Lunch Dishes</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#places">Places for Picnic Lunch</a>
                            </li>
                        </ul>
                        <div class="text-center">
                            <button class="boton-info"><a href="#openModal">Request Info</a></button>
                        </div>
                        <!------ventana emergente 01------------------>
                        <div id="openModal" class="modalDialog">
                            <div>
                                <a href="#close" title="Close" class="close"><i class="fa fa-close"></i></a>
                                <form action="{{ route('mensaje02') }}" method="post" role="form"
                                    class="book1 text-center">
                                    @csrf
                                    <div class="form-row">
                                        <br>
                                        <div class="form-group col-lg-6">
                                            <span class="form-label">Nombre:</span>
                                            <input type="text" name="name" required class="form-control"
                                                placeholder="Requerido*" data-rule="minlen:4"
                                                data-msg="Please enter at least 4 chars" />
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <span class="form-label">E-Mail:</span>
                                            <input type="email" class="form-control" required name="email"
                                                id="email" placeholder="Requerido*" data-rule="email"
                                                data-msg="Please enter a valid email" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <span class="form-label">Adultos:</span>
                                            <input class="form-control" name="adultos" id="adultos" type="number"
                                                placeholder="0" data-error="Valid email is required.">
                                            <div class="validation"></div>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <span class="form-label">Niños:</span>
                                            <input class="form-control" name="child" id="child" type="number"
                                                placeholder="0" data-error="Valid email is required.">
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-lg-12">
                                            <span class="form-label">Fecha Arrivo:</span>
                                            <input class="form-control" name="da" id="da"
                                                type="date">
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-lg-12">
                                            <span class="form-label">Mensaje:</span>
                                            <textarea class="form-control" name="mensaje" id="mensaje" rows="5" data-rule="required"
                                                data-msg="Please write something for us" placeholder="Escriba acá su mensaje:"></textarea>
                                        </div>
                                    </div>
                                    <center>
                                        <input id="botondjm2" type="submit" name="submit" value="Enviar" />
                                    </center>
                                    <br><br>
                                </form>
                            </div>
                        </div>
                        <!------Fin ventana emergente 01------------------>

                    </div>
                </div>
                <div class="col-lg-8">
                    <center>
                        <h1 id="titulo1">ANDEAN PICNIC LUNCH</h1>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-pencil-square"></i> Writted by: David Miranda<br>
                        <i class="fa fa-calendar"></i> 11/06/2021
                    </center><br>
                    <img src='{{ asset('img/blog/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.webp') }}'
                        width="100%" alt="Picnic en los Andes" loading="lazy">
                    <div id="description" style="padding-top:70px">
                        <h2>Description of the Picnic Lunch:</h2>
                    </div>
                    <p>
                        Andean Exclusive Tours has the characteristic of always innovating with the new trends that
                        exist and this time, we will talk about a trend that became very fashionable in Peru: We are
                        talking about picnic lunches in the Andes of Peru. We take advantage of the wonderful landscapes
                        that have a geography full of mountains and snow-capped mountains that surround the Sacred
                        Valley of the Incas in Cusco, to enjoy these incredible picnic lunches.<br><br>
                        This type of experience goes hand in hand with the different tours we offer, some of them are:
                        cycling, motocross, hiking, horseback riding or visits in private mobility. Since normally
                        during these tours it is possible to take a break in the middle of the tour to relax, clean up
                        and enjoy this experience in the middle of the mountains.
                    </p>
                    <div id="realizacion" style="padding-top:70px">
                        <h2>How is the Andean Picnic Lunch made?</h2>
                    </div>
                    <p>
                        As part of the tour that is being carried out, we will wait for lunch or rest time and arriving
                        at the area, we will set up our picnic in a totally private area, located in a strategic place
                        and with impressive privileged panoramic views in the middle of nature, where you can relax and
                        enjoy the comfort and tranquility of the place. It should be noted that you will be able to
                        observe the exclusivity of our tools, utensils and clothing used to provide you with the best
                        experience, as well as the excellent presentation of our service staff to the exquisite
                        presentation of our food dishes. Then you can enjoy snacks and drinks to liven up and wait to
                        taste the rest of the dishes we have to offer, we also have hot natural herbal teas, or cold
                        drinks to acclimatize the body as the weather requires.
                    </p>
                    <div id="foods" style="padding-top:70px">
                        <h2>Meals that you can enjoy in our Andean Picnic Lunch</h2>
                    </div>
                    <p>
                        The Sacred Valley of the Incas is known for being a propitious place to be able to grow
                        different products, which are consumed by the people of the region from Cusco on a daily basis.
                        Thus, most of the dishes offered at the country lunch are prepared with products raised in this
                        area and in this way it can be said that the Cusqueño peasant is helped. You can enjoy delicious
                        appetizers, main dishes and hot and cold drinks, all with 100% natural and native ingredients,
                        creating a pleasant and unique experience for your palate, with the "Andean Exclusive" seal.
                        Some of our dishes at the Andean Picnic are:
                    </p>
                    <ul>
                        <li>Quinoto </li>
                        <li>Ensalada Mesclum</li>
                        <li>Pollo enrollado</li>
                        <li>Brocheta de verduras</li>
                        <li>Causa rellena</li>
                        <li>Palta Ccapi</li>
                        <li>Pico de Gallo</li>
                        <li>Pisco Sour</li>
                        <li>Postres </li>
                        <li>Mate de Coca e infusiones</li>
                        <li>Bebidas frías como gaseosa, agua</li>
                        <li>Té helado</li>
                    </ul>
                    <div class="row">
                        <div class="col-lg-6">
                            <img src="../img/blog/Andean-Picnic.JPG" loading="lazy"
                                alt="Luxury Andean Picnic in Cusco Peru">
                        </div>
                        <div class="col-lg-6">
                            <img src="../img/blog/Picnic-lunch-peru.jpg" width="100%" loading="lazy"
                                alt="Picnic - Country lunch Peru"><br><br>
                        </div>
                    </div>

                    <h2>Our Picnic Lunch Team</h2>
                    <ul>
                        <li> Dining Tent</li>
                        <li> Buffet Tent</li>
                        <li> Bathroom tent</li>
                        <li> Tables, Chairs</li>
                        <li> Full Porcelain Tableware</li>
                        <li> Cups, Glasses</li>
                        <li> Cutlery</li>
                        <li> Portable faucet to wash hands, with cloth towels</li>
                    </ul>
                    <img src="{{ asset('img/blog/Picnic-lunch-almuerzo-campestre-Cusco.webp') }}" loading="lazy"
                        alt="Presentation of Picnic Lunch dishes" width="100%">
                    <i>Picnic Lunch with Chinchero community</i><br><br>
                    <div id="places" style="padding-top:70px">
                        <h2>Places where the Picnic Lunch takes place in Cusco</h2>
                    </div>
                    <ul>
                        <li> Huacarpay Lagoon</li>
                        <li> South valley: farm Chuquijahuana</li>
                        <li> Chinchero Community</li>
                        <li> Close to the archaeological center of Moray</li>
                    </ul>
                    <img src="{{ asset('img/blog/picnic-equipo-atencion.webp') }}" loading="lazy"
                        alt="Luxury Andean Picnic in Cusco Peru" width="100%">
                    <i>'Andean Exclusive Tours' Team</i>
                    <div id="separador"></div>
                    <h2>Video of Picnic Lunch </h2>

                    <iframe width="100%" height="400" src="https://www.youtube.com/embed/B0MvUIYTvmQ"
                        title="Andean Exclusive - Andean Food Service | Spot Picnic" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>

                    <div id="separador"></div>

                    <!---------redes sociales------------------------>
                    <div class="form text-center">
                        <!---------redes sociales------------------------>
                        <script type="text/javascript"
                            src="https://platform-api.sharethis.com/js/sharethis.js#property=6232ae6fcdbe0200193e185e&product=inline-share-buttons"
                            async="async"></script>
                        <div class="sharethis-inline-share-buttons"></div>
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v13.0&appId=588015035734857&autoLogAppEvents=1"
                            nonce="RA0e6tQN"></script>
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/picnic"
                            data-width="100%" data-numposts="5"></div>
                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
    </section>
    @include('layouts.foot')

</body>

</html>
