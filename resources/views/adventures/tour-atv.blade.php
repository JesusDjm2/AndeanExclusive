<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>TOUR DE MOTOCROS EN CUSCO </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="TOUR DE MOTOCROS  EN CUSCO " />
    <meta name="description"
        content="La aventura empezara desde nuestra base en Chinchero, aproximadamente 40 minutos de la ciudad del Cusco." />
    <meta property="og:url" content="https://www.andeanexclusive.com/adventures/tour-atv">
    <meta property="og:title" content="TOUR DE MOTOCROS  EN CUSCO ">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:image" content="https://andeanexclusive.com/img/tours/atv-cusco-tours-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/adventures/tour-atv">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel='stylesheet' href="{{ asset('styles/js_composer.min5243.css') }}" type='text/css' media='all' />
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/estilo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body class="home page-template-default page page-id-44 wpb-js-composer js-comp-ver-5.4.5 vc_responsive"
    style="font-size: 14px; font-weight: 400; color:#131313">
    @include('layouts.wasa-blog')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="/" rel="home" class="logo-text-link"><img
                                        src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-blog-castellano')
                                    <li id="display" class="menu-has-children">
                                        <a href="{{route('atv-tour')}}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="{{route('atv-tour')}}">
                                            <button type="button" class="botondjm">English
                                            </button>
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
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <article class="blog-item blog-single">
                        <div class="entry-excerpt">
                            <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                data-onepage-slug="home"
                                class="vc_row wpb_row adventures vc_row-has-fill vc_row-o-full-height vc_row-o-content-middle vc_row-flex">
                                <div class="wpb_column vc_column_container vc_col-sm-12">
                                    <div class="vc_column-inner vc_custom_1461317698190">
                                        <div class="wpb_wrapper">
                                            <div class='carousel-headings '>
                                                <div class='swiper-container'>
                                                    <div class='swiper-wrapper'>
                                                        <div class='swiper-slide'>
                                                            <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                @if (session()->has('flash'))
                                                                    <center>
                                                                        <div class="alert alert-success" id="alerta"
                                                                            style="width: 60%;">
                                                                            {{ session()->get('flash') }}<button
                                                                                type="button" onclick="bye()"
                                                                                style="float: right;">X</button>
                                                                        </div>
                                                                    </center>
                                                                @endif
                                                                <h1><?php $tour = 'TOUR EN CUATRIMOTO A MARAS Y MORAY';
                                                                echo $tour; ?></h1>
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
                        </div>
                    </article>
                </div>
            </div>
        </div>

    </div>
    <div id="separador"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3" id="display2">
                <div class="sticky-top" style="padding-top: 75px; z-index: 40">
                    <div>
                        <div data-onepage-title="Services" data-onepage-slug="services"
                            class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
                            <h1 class="text-center" style="font-size:22px; font-weight:500; margin-top:30px">
                                Tours Similares
                            </h1>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../ciclismo" class="button">Ciclismo en las momntañas</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="tour-rafting" class="button">Rafting</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="tour-laguna-humantay" class="button">Lago Humantay</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="vinicunca-tour" class="button">Montaña de colores</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../peru-12-dias" class="button">Tour en Perú por 12 días</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../peru-20-dias-tour" class="button">Tour en Perú por 20 days</a>
                            </div>
                            <div id="separador"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div data-onepage-title="Services" style="text-align:justify" data-onepage-slug="services"
                    class="vc_row-fluid vc_custom_1461248392126">
                    <center>
                        <h1 id="titulo1">TOUR EN CUATRIMOTO A MARAS Y MORAY </h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center"><i class="fa fa-clock-o"></i> Full Day</p>
                        <p class="text-center"><i class="fa fa-map-marker"> Cusco - Maras - Moray</i>
                        </p>
                        <p class="text-center"><i class="fa fa-envelope"></i> Reservar con 10 días de anticiáción</p>
                    </center>
                    <div id="separador"></div>
                    <h5 style="padding: 0px 1px 0px 20px;">Itinerario:</h5>
                    <p style="padding: 0px 1px 0px 20px;">
                        La aventura empezara desde nuestra base en<strong> Chinchero,</strong> aproximadamente 40
                        minutos de la ciudad
                        del Cusco. Recorreremos lugares turísticos del Valle Sagrado. Haciendo nuestra primera parada es
                        la iglesia de Tiobamba, seguidamente visitaremos las<strong> Minas de sal de Maras,</strong> por
                        ultimo
                        visitaremos Moray - terrazas agrícolas de los Incas, que concluye nuestro tour en cuatrimoto a
                        Maras, Moray y el Valle sagrado.
                    </p>
                    <div id="separador"></div>
                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist"
                        style="text-transform: uppercase; font-size: 12px; font-weight: 700">
                        <li class="nav-item">
                            <a class="nav-link active" id="drop" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">Include</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">No Include</a>
                        </li>

                    </ul>
                    <div class="tab-content pb-5" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <ul>
                                <li>Guía de aventura.</li>
                                <li>Transporte desde Cusco(ida y retorno).</li>
                                <li>Base, gasolina, lecciones de manejo, equipo
                                    (casco, guantes, lentes).</li>
                                <li>Almuerzo en Salineras</li>
                                <li>Dulce/snack en ruta.</li>
                                <li>Fotos.</li>
                                <li>Entradas a los sitios de Maras y Moray</li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <ul>
                                <li>Propinas</li>
                            </ul>
                        </div>
                    </div>
                    <iframe
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FAndeanExclusiveTours%2Fposts%2Fpfbid02bvkNAu3Z4A1h5vCLvk1F4ZCMRfssVUwb7kaAEfZEb81cpeJAmuBKEUmudvVtyaLul&show_text=true&width=500"
                        width="100%" height="879" style="border:none;overflow:hidden" scrolling="no"
                        frameborder="0" allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe><br><br>
                    @include('layouts.payments')
                    <!-----Booking---------->
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/adventures/tour-atv"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div><br><br>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="sticky-top" style="padding-top: 75px; z-index: 40">
                    <div>
                        @include('layouts.booking-castellano')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-onepage-title="Services" data-onepage-slug="services" id="display"
        class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="vc_column-inner vc_custom_1461227943574">
                <div class="wpb_wrapper">
                    <div class='heading  text-center'>
                        <div id="separador"></div>
                        <h3>Tours Similares</h3>
                        <div id="separadordjm"></div>
                    </div>
                </div>
                <div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1461228476759">
                    <div class="wpb_column vc_column_container vc_col-sm-3">
                        <div class="vc_column-inner vc_custom_1461228417147">
                            <div class="wpb_wrapper">
                                <div class='travel-item'>
                                    <div class='entry-img'>
                                        <a class='entry-link text-center' href="../cusco-5-dias-4-noches">
                                            <img src='../img/thumbnail/llama-peru.JPG' alt=" cusco en los andes" />
                                        </a>
                                    </div>
                                    <div class='entry-info'>
                                        <div class='readmore'>
                                            <a href='../cusco-5-days-4-nights' class='button'>5 días Cusco</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpb_column vc_column_container vc_col-sm-3">
                        <div class="vc_column-inner vc_custom_1461228442150">
                            <div class="wpb_wrapper">
                                <div class='travel-item'>
                                    <div class='entry-img'>
                                        <a class='entry-link text-center' href="../cusco-6-dias-5-noches">
                                            <img width="400" height="400" src='../img/thumbnail/cusco-01.JPG'
                                                class="attachment-post-grid-s size-post-grid-s"
                                                alt="Cusco tours de 6 días" />
                                        </a>
                                    </div>
                                    <div class='entry-info'>
                                        <div class='readmore'>
                                            <a href="../cusco-6-dias-5-noches" class='button'>6 dias Cusco</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpb_column vc_column_container vc_col-sm-3">
                        <div class="vc_column-inner vc_custom_1461228449756">
                            <div class="wpb_wrapper">
                                <div class='travel-item'>
                                    <div class='entry-img'>
                                        <a class='entry-link text-center' href="../peru-9-dias-8-noches">
                                            <img width="400" height="400" src="../img/thumbnail/chincheros.jpg"
                                                class="attachment-post-grid-s size-post-grid-s"
                                                alt="Tour en el Valle sagrado de los incas"
                                                srcset="../img/thumbnail/chincheros.jpg 400w"
                                                sizes="(max-width: 400px) 100vw, 400px" />
                                        </a>
                                    </div>
                                    <div class='entry-info'>
                                        <div class='readmore'>
                                            <a class='button' href="../peru-9-dias-8-noches">Perú por 9 días</a>
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
    @include('layouts.foot-blog-castellano')

</body>

</html>
