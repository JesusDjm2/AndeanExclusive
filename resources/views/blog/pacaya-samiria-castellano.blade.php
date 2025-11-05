<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Selva del Perú - Pacaya Samiria - La reserva natural más grande del Perú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="Pacaya Samiria, jungla de Perú, tour en amazonas, río amazonas" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://www.andeanexclusivetours.com/Blog/imgs/otorongo-jaguars.jpg">
    <meta property="og:title" content="Selva del Perú - Pacaya Samiria - La reserva natural más grande del Perú">
    <meta name="description"
        content="La Reserva Nacional Pacaya Samiria (PSNR) es un área protegida ubicada en la región de Loreto Perú, Amazonía peruana." />
    <meta property="og:url" content="https:/www./andeanexclusive.com/blog/pacaya-samiria-castellano">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://www.andeanexclusivetours.com/Blog/imgs/otorongo-jaguars.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/pacaya-samiria-castellano">
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
                                <a href="{{ route('inicio') }}" rel="home" class="logo-text-link">
                                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo">
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{route('pacaya-samiria')}}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{route('pacaya-samiria')}}">
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
        <div class="container-fluid" style="height: 130px; background:#000">
        </div>
    </div>
    <section>
        <div class="container">
            <div class="row ubicacion">
                <div class="col-lg-3 col-sm-2">
                    <div class="line"></div>
                </div>
                <div class="col-lg-6 col-sm-10 text-center"><a href="{{ route('inicio') }}">Inicio</a> /
                    <a href="{{ route('blog-es') }}">Blog</a> /
                    <a>Reserva nacional de Pacaya Samiria</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#queEs">¿Qué es Pacaya Samiria?</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#objetivos">Objetivos de Pacaya Samiria</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#weather">Clima e hidrografía</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#turismo">Turismo</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#comoLlegar">¿Cómo llegar?</a> </li>
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
                        <h1 id="titulo1">Reserva Nacional de Pacaya Samiria</h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center">Escrito por: David Miranda</p>                        
                        <i class="fa fa-calendar"></i> 20/09/2021
                    </center>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/barco-en-Iquitos.jpg" alt="Paseo en crucero en Pacaya Samiria"
                            loading="lazy  ">
                    </div>
                    <div id="queEs" style="padding-top:70px">
                        <h2>¿Qué es Pacaya Samiria?</h2>
                    </div>
                    <p>
                        La Reserva Nacional Pacaya Samiria (RNPS) es un área protegida ubicada en la región de
                        Loreto Perú, Amazonía peruana. Con un área de 2.08 millones de ha (20,800 kilómetros
                        cuadrados) es la Reserva Nacional más grande del Perú. La extensión de la reserva representa
                        el 1.5% del territorio peruano y el 6% de la región de Loreto también es algo más grande que
                        la superficie de Eslovenia y comparable al 50% del territorio de Dinamarca, Suiza, los
                        Países Bajos y Costa Rica, lo que garantiza La representación ecológica y genética de la
                        región. Comprende aproximadamente 208 centros de población con aproximadamente 40 mil
                        personas viviendo en el área y 50 mil en la zona de amortiguamiento. Hay 56 comunidades
                        nativas afiliadas a organizaciones indígenas.
                    </p>
                    <div id="objetivos" style="padding-top:70px">
                        <h2>Objetivos de la reserva nacional de Pacaya Samiria</h2>
                    </div>
                    <ul>
                        <li>Promover estudios de flora y fauna en el área.</li>
                        <li>Mejorar y ampliar la educación en el área.</li>
                        <li>Interesar a la población local sobre los beneficios de la conservación y el buen manejo de
                            la vida silvestre.</li>
                        <li>Promover y mejorar el uso de los recursos naturales de acuerdo con el desarrollo ecológico
                            apropiado.</li>
                    </ul>
                    <div id="weather" style="padding-top:70px">
                        <h2>Clima en Pacaya Samiria</h2>
                    </div>
                    <p>
                        Cálido y húmedo, con temperaturas que a menudo superan los 34 ° C. Como en todo el Amazonas,
                        tiene dos estaciones distintas: la estación seca, la estación seca o de verano (mayo a
                        octubre) se conoce como el verano amazónico para la formación de playas fluviales de color
                        blanco. la arena y la otra es la temporada de lluvias o de crecimiento (de noviembre a
                        abril), conocida como el invierno amazónico.</p>

                    <h2>Hidrografía</h2>
                    <P>
                        Hay dos ríos principales en la reserva: el río Pacaya, afluente del Ucayali, desemboca en la
                        margen izquierda del canal Puinahua. El río Samiria, afluente del Marañón, fluye por el lado
                        derecho del río. Hay unos 80 lagos. En la confluencia de los ríos Marañón y Ucayali, ante la
                        comunidad Mariscal Castilla, nació nominalmente Elrio Amazonas.
                    </P>
                    <div id="turismo" style="padding-top:70px">
                        <h2>Turismo en La reserva Nacional de Pacaya Samiria</h2>
                    </div>
                    <p>
                        En Pacaya Samiria puedes encontrar muchas cosas qué hacer, ya sea desde pescar y conseguir
                        tu propia comida asi como pasear en excursiones, ver distintos tipos de animales, darte un
                        enigmatico baño en las lagunas, o recostarte en una hamaca disfrutando de un manto de
                        estrellas.<br><br>
                        <img src="{{ asset('img/blog/pacaya-samiria.jpg') }}" width="100%"
                            alt="Turismo en la reserva nacional de Pacaya Samiria" loading="lazy"><br><br>
                        Es normal querer quedarse un buen tiempo para explorar todas las actividades que nos ofrece
                        esta reserva nacional, tambien sabemos que muchos turistas tienen un tiempo limitado para su
                        estadia en este lugar, asi que les mostramos los principales lugares turisticos de Pacaya
                        Samiria:
                    </p>
                    <ul>
                        <li><strong>Rio Marañón:</strong>Este río es un gran afluente del rio Amazonas, que en
                            realidad será tu principal carretera para los distintos lugares que visitarás en Pacaya
                            Samiria, es posible ver animales como caimanes, torugas, pirañas.</li>
                        <li><strong>Laguna el Dorado:</strong>Muy a pesar de que podrás encontrar muchas lagunas por
                            esta zona, la laguna El Dorado, destaca por entre las demás por que desde acá podrás ver
                            uno de los atardeceres más hermosos de tu vida.</li>
                        <li><strong>Lago Yanta:</strong> Este lugar trae consigo una hermosa caminata por medio de
                            la selva de la amazonía, donde podrás tener clases de reconocimiento de plantas,
                            disfrutar de paseo en canoa o lanzarte a las aguas de la laguna, donde a la vez
                            disfrutaras del hermoso paisaje alrededor. ¡Escoger que hacer será una dificil
                            decisión!.</li>
                        <li><strong>Comunidades Nativas:</strong>En Pacaya Samiria existen alrededor de 200 centros
                            poblados
                            en los que habitan un promedio de 120 000 personas, las cuales tienen una forma de vida
                            muy distinta a la vida cotidiana de las ciudades. Por lo que tener la experiencia de ver
                            su estilo de vida, resulta ser una experiencia enriquecedora para la vida misma.</li>
                        <li><strong>Crucero en medio de la selva:</strong> Podriamos juntar el lujo y el turismo en
                            estos cruceros de la selva, donde se podrá conocer la inmensidad de la selva desde la
                            comodidad de un crucero.</li>
                    </ul>
                    <div id="comoLlegar" style="padding-top:70px">
                        <h2>¿Cómo llegar a Pacaya Samiria?</h2>
                    </div>
                    <p>
                        La reserva nacional de Pacaya Samiria esta bastante alejada de Iquitos, y la mejor manera de
                        llegar a ella es a travez de la inmensa selva que la rodea. Para ello lo primero es llegar a
                        Iquitos, ya sea en barco o en avión, que son los 2 únicos medios de transporte que existen
                        para llegar a Pacaya Samiria.<br><br>
                        Una vez se haya llegado a Iquito, se debe viajar hacia Nauta, un pueblo portuario, donde te
                        subirás a un bote recorrerás durante aproximadamente 35 minutos las aguas del Rio Marañón,
                        es una experiencia que te perdurará toda la vida.<br><br>
                        Es importante y practicamente obligatorio, contar con el servicio de una agencia de viajes
                        que te brinde un guía de turismo, para que te ayude a moverte dentro de la selva. Ya que la
                        selva es un lugar bastante difícil de recorrer, con caminos similares que hace complicado su
                        recorrido.
                    </p>
                    <img src="{{ asset('img/blog/turismo-selva-peru.jpg') }}" width="100%"
                        alt="Gallito de las rocas Amazonia" loading="lazy">
                    <div id="separador"></div>

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
                        <div class="fb-comments"
                            data-href="https://andeanexclusive.com/blog/pacaya-samiria-castellano" data-width="100%"
                            data-numposts="5"></div>
                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            </div>
            <div id="separador"></div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3>Temas Similares</h3>
                    <div id="separadordjm2"></div>
                </div><br><br>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('inti-raymi-castellano') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/inti-raymi-andean-exclusive.webp') }}" alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">La fiesta del sol</h5>
                            <p class="card-text">El Inti Raymi es una de las fechas más importantes para la cultura
                                Inca, y en cusco se celebra a lo grande</p>
                            <a href="{{ route('inti-raymi-castellano') }}" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/lineas-de-nasca-colibri.webp') }}" alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Lineas de Nazca</h5>
                            <p class="card-text">Las mas misteriosas y enormes figuras talladas en el desierto de Ica,
                                es una constante pregunta de ¡Para qu'e estan hechos?</p>
                            <a href="#" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('dia-de-la-pachamama') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}" alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Día de la Madre Tierra</h5>
                            <p class="card-text">Al ser pateísta la religion Inca, esta tenia un día especial para la
                                madre tierra</p>
                            <a href="{{ route('dia-de-la-pachamama') }}" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
            </div><br><br>
        </div>
    </section>
    @include('layouts.foot-castellano')

</body>

</html>
