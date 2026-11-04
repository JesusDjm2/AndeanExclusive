@extends('layouts.general-es')
@section('metas')
    <title>Ica - Huacachina: Un oasis en el desierto de Perú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords"
        content="tour de 2 dias, Ica Huacachina, Ica tours, Ica, Perú, Oasis, huacachina tour, tour en huacachina, hucachina ica tour, viaja a huacachina, viaja a ica" />
    <meta name="description" content="Conoce y disfruta de este tour en Huacachina, donde encontraremos un gran Oasis." />
    <meta property="og:url" content="https://www.andeanexclusive.com/huacachina-ica-peru-castellano">
    <meta property="og:title" content="Ica - Huacachina: Un oasis en el desierto">
    <meta property="og:type" content="article">
    <meta property="og:image" content="http://andeanexclusive.com/img/tours/huacachina-tour-exclusive-peru.jpg" />
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="index-castellano" rel="home" class="logo-text-link">
                                    <div class="logo2"></div>
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{ route('huacachina-ica') }}">
                                            <button type="button" class="btn botondjm">English</button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2">
                                        <a href="{{ route('huacachina-ica') }}">
                                            <button type="button" class="btn botondjm">English</button>
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
                                    class="vc_row wpb_row huacachina vc_row-has-fill vc_row-o-full-height vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings'>
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
                                                                    <h1><?php $tour = 'Huacachina - Ica - Perú';
                                                                    echo $tour; ?></h1>
                                                                    <p><i class="fa fa-map-marker"></i> Ica - Perú
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
                                <div data-onepage-title="Services" data-onepage-slug="services"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
                                    <div id="separador"></div>
                                    <div class="row" id="rowdjm">
                                        <div class="location">
                                            <div class="row text-center">
                                                <div class="col-lg-3 col-sm-2" id="display2">
                                                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-12"><a href="{{ route('inicio') }}">Inicio</a> /
                                                    <a href="experiencias">Populares</a> /
                                                    <a>Huacachina - Perú</a>
                                                </div>
                                                <div class="col-lg-3 col-sm-2" id="display2">
                                                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3" id="display2">
                                            <h4 class="text-center">Tours Similares</h4>
                                            <div id="similares">
                                                <div id="separador"></div>
                                                <a href="ciclismo" class="button">Ciclismo en los Andes</a>
                                            </div>
                                            <div id="similares">
                                                <div id="separador"></div>
                                                <a href="adventures/tour-rafting" class="button">Rafting</a>
                                            </div>
                                            <div id="similares">
                                                <div id="separador"></div>
                                                <a href="adventures/tour-atv" class="button">Motocros en los
                                                    cielos</a>
                                            </div>
                                            <div id="similares">
                                                <div id="separador"></div>
                                                <a href="valle-sagrado" class="button">Valle Sagrado de los
                                                    Incas</a>
                                            </div>
                                            <div id="similares">
                                                <div id="separador"></div>
                                                <a href="puerto-maldonado-castellano" class="button">Tour en
                                                    Puerto Maldonado</a>
                                            </div>
                                            <div id="similares">
                                                <div id="separador"></div>
                                                <a href="peru-8-dias-7-noches" class="button">Perú por 8
                                                    días</a>
                                            </div>
                                            <div id="separador"></div>
                                        </div>
                                        <div class="col-lg-6">
                                            <center>
                                                <h2 id="titulo1">Huacachina</h2>
                                                <div id="separadordjm2"></div>
                                                <p class="text-center"><i class="fa fa-clock-o"></i> 2 días</p>
                                                <p class="text-center"><i class="fa fa-map-marker"></i> Huacachina -
                                                    Ica</p>
                                                <p class="text-center" style="color: #0c8178">Reservar con 30 días de
                                                    anticipación</p>
                                            </center>
                                            <h5>Visión general</h5>
                                            <p>
                                                Debido a su proximidad con Lima, la capital del Perú, Ica es uno de los
                                                destinos ideales para escaparse: esta región sureña está ubicada a
                                                (aproximadamente) 4 horas de la urbe limeña y tiene un sinnúmero de
                                                actividades, tan distintas como divertidas, para los viajeros que se
                                                aventuren a recorrer su territorio.<br><br>

                                                Porque en Ica, las dunas de sus enormes desiertos resguardan el legado
                                                cultural e histórico de antiguas civilizaciones, como también son la
                                                locación de un oasis que parece sacado de una película o un cuento de
                                                hadas. En Ica abundan las hermosas playas con aguas refrescantes. Ese
                                                mismo mar que sirve para practicar tus chapuzones con tu familia y
                                                amigos, también es el hábitat de hermosas especies de fauna silvestre,
                                                que están ansiosas porque las visites.<br><br>

                                                No es una exageración: en Ica hay una actividad distinta para cada tipo
                                                de turista. Es un lugar donde el tiempo pasa volando, porque siempre hay
                                                algo qué hacer. ¿No nos crees? No te preocupes, que aquí mencionaremos
                                                experiencias de sobra para que puedas elegir las que mejor se adecúan a
                                                tu itinerario de viaje.
                                            </p>
                                            <img src="{{ asset('img/blog/Huacachina-Buggies-Andean-Exclusive-Tours.webp') }}"
                                                alt="Huacachina Sandboarding Ica Peru Andean Exclusive"
                                                loading="lazy"><br><br>
                                            <h5>Dia 01: PASEO EN TUBULARES</h5>
                                            <p>
                                                &minus; Check in en el hotel<br>
                                                &minus; 03:30 PM Inicio del circuito de aventuras en el desierto, donde
                                                realizarán un paseo de pura adrenalina en carros areneros (buggies) y
                                                además podrán realizar el sandboarding (deslizamiento en tablas),
                                                circuito dura una hora aprox.<br>
                                                &minus; Noche de alojamiento en hotel seleccionado.
                                            </p>
                                            <h5>DIA 2: CITY TOUR ICA</h5>
                                            <p>
                                                - Desayunos<br>
                                                - Recojo de su hotel. Como primer punto visitaremos la bodega
                                                vitivinícola
                                                artesanal, en donde aprenderemos sobre la elaboración del vino y Pisco,
                                                además tendrán la oportunidad de degustar los diversos piscos y vinos de
                                                las bodegas. Continuamos con la visita a la Plaza de armas de la ciudad,
                                                donde observaremos las principales calles de Ica, también visitaremos
                                                Chocolatería Helena, la Iglesia Señor de Luren. Luego nos dirigiremos
                                                hasta el pueblo místico de Cachiche, pueblo muy conocido en Ica, ya que
                                                fue un lugar en donde se encontraron las antiguas brujas, allí podrán
                                                conocer la Palmera de 7 cabezas. Finalmente visitaremos la majestuosa
                                                laguna de Huacachina, conocida como “El Oasis de América”, desde este
                                                punto podremos apreciar el impresionante paisaje desértico de las dunas,
                                                tomarse increíbles fotos con la puesta de sol. Al final del tour
                                                retornamos al hotel.<br>
                                                - Check out
                                            </p>
                                            <h5>INCLUYE:</h5>
                                            <p>
                                                &#10003; 01 noche de alojamiento en hotel con piscina<br>
                                                &#10003; Desayunos<br>
                                                &#10003; Paseo en Tubulares + Sandboarding.<br>
                                                &#10003; City Tour<br>
                                                &#10003; Asistencia personalizada.
                                            </p>
                                            <h5>NO INCLUYE:</h5>
                                            <p>
                                                &#10007; Ticket de ingreso a las dunas.<br>
                                                &#10007; Traslados desde Lima a Ica o viceversa (puede solicitar este
                                                servicio
                                                adicional).<br>
                                                &#10007; Recojo en la estación de bus y traslado al hotel (puede
                                                solicitar este
                                                servicio adicional).<br>
                                                &#10007; IGV, si desea factura añade el 18%.
                                            </p>
                                            <div id="separador"></div>
                                            <div class="form text-center">
                                                @include('layouts.payments')
                                                <h5>Compartir</h5>
                                                <!---------redes sociales------------------------>
                                                <script type="text/javascript"
                                                    src="https://platform-api.sharethis.com/js/sharethis.js#property=6232ae6fcdbe0200193e185e&product=inline-share-buttons"
                                                    async="async"></script>
                                                <div class="sharethis-inline-share-buttons"></div>
                                                <div id="fb-root"></div>
                                                <script async defer crossorigin="anonymous"
                                                    src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v13.0&appId=588015035734857&autoLogAppEvents=1"
                                                    nonce="RA0e6tQN"></script>
                                                <div class="fb-comments"
                                                    data-href="https://andeanexclusive.com/huacachina-ica-peru-castellano"
                                                    data-width="100%" data-numposts="5"></div>
                                                <!---------Fin redes sociales------------------------>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            @include('layouts.booking-castellano')
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-3" id="display">
                                    <h4 class="text-center">Tours Similares</h4>
                                    <div id="similares">
                                        <div id="separador"></div>
                                        <a href="ciclismo" class="button">Ciclismo en los Andes</a>
                                    </div>
                                    <div id="similares">
                                        <div id="separador"></div>
                                        <a href="adventures/tour-rafting" class="button">Rafting</a>
                                    </div>
                                    <div id="similares">
                                        <div id="separador"></div>
                                        <a href="adventures/tour-atv" class="button">Motocros en los
                                            cielos</a>
                                    </div>
                                    <div id="similares">
                                        <div id="separador"></div>
                                        <a href="valle-sagrado" class="button">Valle Sagrado de los
                                            Incas</a>
                                    </div>
                                    <div id="similares">
                                        <div id="separador"></div>
                                        <a href="puerto-maldonado-castellano" class="button">Tour en
                                            Puerto Maldonado</a>
                                    </div>
                                    <div id="similares">
                                        <div id="separador"></div>
                                        <a href="peru-8-dias-7-noches" class="button">Perú por 8
                                            días</a>
                                    </div>
                                    <div id="separador"></div>
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
