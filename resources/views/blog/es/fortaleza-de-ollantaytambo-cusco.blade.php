<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>El pueblo y la Fortaleza de Ollantaytambo </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords"
        content="Ollantaytambo, fortaleza de ollantaytambo, viaja a ollantaytambo, drama ollantay, inca pachacute, pachacutec, cusco ollantaytambo" />
    <meta name="description"
        content="Es un poblado que se encuentra al noreste del colorido Valle de los Incas. Ollantaytambo fue antes de los incas un pequeño pueblo en crecimiento que entraron en el dominio de los Incas en la época del Inca Pachacútec" />
    <meta property="og:url" content="https://andeanexclusive.com/blog/es/fortaleza-de-ollantaytambo-cusco">
    <meta property="og:title" content="El pueblo y la Fortaleza de Ollantaytambo ">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/templo-del-sol-ollantaytambo.webp" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/es/fortaleza-de-ollantaytambo-cusco">
    @include('layouts.links')
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
                                        <a href="{{ route('ollantaytambo-fortress') }}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('how-to-travel-to-peru') }}">
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
                    <a>Gastronomía Peruana</a>
                </div>
                <div class="col-lg-3 col-sm-2">
                    <div class="line"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="sticky-top" style="top:100px; z-index: 40">
                        <h4 class="text-center">Tabla de contenido</h4>
                        <div id="separadordjm2"></div>
                        <ul class="tabla-de-contenido" style="list-style:none;">
                            <li><i class="fa fa-caret-right"></i> <a href="#construccion">Construccion de Ollantaytambo</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#temploDelSol">Templo del Sol</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#curiosidades">Curiosidades</a></li>

                            <li><i class="fa fa-caret-right"></i> <a href="#lugares">¿Qué lugares visitar?</a>
                            </li>
							<li><i class="fa fa-caret-right"></i> <a href="#tours">Tours ollantaytambo</a>
                            </li>
                        </ul>
                        <div class="text-center">
                            <button class="boton-info"><a href="#openModal">Solicitar Reserva</a></button>
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
                    <h1 class="text-center">Fortaleza de Ollantaytambo</h1>
                    <div id="separadordjm2"></div>
                    <p class="text-center">Por: David Miranda</p>
                    <p class="text-center"><i class="fa fa-calendar"></i> 02/12/2022</p>
                    <p>
                        Es un poblado que se encuentra al noreste del colorido Valle de los Incas. Ollantaytambo fue
                        antes de los incas un pequeño pueblo en crecimiento que entraron en el dominio de los Incas en
                        la época del <strong>Inca Pachacútec</strong> (1418 - 1471). Este pueblo se fijo como un muy importante puesto de
                        control, además de ser el lugar donde se pudo domesticar al maíz para su producción en
                        abundancia, aprovechando que el rio Urubamba se encontraba justo alado del pueblo. <br><br>

                        Este pueblo es conocido por ser uno de los asentamientos humanos más antiguos en América que ha
                        sido habitado. Solo con deambular entre las calles de este pueblo, puede un viajero retroceder
                        en el tiempo de una manera especial.
                    </p>
                    <img src="{{ asset('img/blog/OLLANTAYTAMBO.webp') }}" width="100%"
                        alt="Recomendaciones para viajar a Perú" loading="lazy">
                        <i>Andenes de Ollantaytambo</i>
                    <br><br>
                    <div id="construccion" style="padding-top:70px">
                        <h2>Construcción de Ollantaytambo</h2>
                    </div>
                    <p>
                        Se sabe que Pachacútec fue quien construyó las principales edificaciones del actual sitio
                        arqueológico de Ollantaytambo, pero hay evidencias de que los orígenes de Ollantaytambo
                        provienen desde aproximadamente 400 años atrás. <br><br>
                        Hay evidencia de la cultura Huari en los yacimientos del pueblo e indicios de asentamientos
                        mucho mas antiguos, entonces lo mas probable es que los Incas construyeron sobre una ciudad que
                        ya existía.
                    </p>

                    <div id="temploDelSol" style="padding-top:70px">
                        <h2>El templo del Sol</h2>
                    </div>
                    <p>
                        En la cima de Ollantaytambo construida sobre una torre de terrazas en el lado oeste, se
                        encuentra el ‘Aracama’, que es donde se encuentra el Templo del Sol, esta es una edificación
                        única en el Cusco.<br><br>
                        Cuenta con 150 escalones con un perfecto tallado de piedras parecidas a el Valle de Tipón al sur
                        de Cusco, entre la arquitectura, destacan los 6 monolitos erguidos y unidos por enormes laminas
                        de piedra, se crearon con la finalidad de amortiguar los movimientos sísmicos.<br><br>
                        Es por las terrazas de Ollantaytambo que este pueblo es visto como una de las mas impresionantes
                        muestras de arquitectura, ingeniería y agricultura Inca, poseen 700 alto, 58 de ancho y 15
                        metros de profundidad, construidos en relación al sol, estos crear microclimas lo que permitían
                        cultivar productos de diferentes zonas altitudinales.
                    </p>
                    <img src="{{ asset('img/blog/templo-del-sol-ollantaytambo.webp') }}" width="100%"
                        alt="Lenguas más habladas en Perú" loading="lazy">
                    <i>Templo del Sol</i> <br> <br>

                    <div id="curiosidades" style="padding-top:70px">
                        <h2>Curiosidades de Ollantaytambo </h2>
                    </div>
                    <ul>
                        <li>Tiene el Templo del Sol, que es considerado uno de los mejores trabajos de construcción
                            realizado en la época de los Incas.</li>
                        <li>Tanto sus atributos culturales, paisajísticos, naturales, históricos y arquitectónicos que
                            posee Ollantaytambo, le han otorgado el merecimiento como <strong>‘Mejores pueblos
                                turísticos del
                                mundo’</strong>, en la veinticuatroava asamblea General de la Organización Mundial del
                            Turismo (OMT).
                            <i>España – Madrid 2021</i>
                        </li>
                        <li>Ollantaytambo también ha sido declarado como <strong>‘Capital mundial de la
                                Indianidad’</strong> y su danza
                            Wallata de la cuenca del río Patacancha fue declarada Patrimonio Cultural de la Nación por
                            el Ministerio de Cultura, el 30 de octubre del 2008. </li>
                        <li>Se encuentra a una altura de 2792 m.s.n.m. En la provincia de Urubamba 60 kilómetros noreste
                            de Cusco, se divide en 2 sectores: el pueblo y el sitio arqueológico.</li>
                        <li>La leyenda <strong>‘El Drama Ollantay’</strong> sostiene que en la época de Pachacútec hubo
                            un almirante que se sublevo ante el Inca por el amor de su hija. La palabra ‘tambo’,
                            significa en quechua, lugar de descanso o albergue de viajeros. Lo que supone que Ollantay
                            uso el ‘tambo’ como su base. </li>
                        <li>Además de haber sido una fortaleza, las construcciones indican que también fue un lugar de
                            descanso tanto para el inca como para la Colla (Esposa del Inca).</li>
                        <li>Para visitar el sitio arqueológico se debe comprar el Boleto Turístico que te permite
                            visitar otros destinos arqueológicos. Se puede también hacer una visita a las antiguas
                            canteras abandonadas desde donde se extrajo el material de construcción de la ciudad.</li>
                        <li>Este pueblo en la actualidad aun conserva su un trazado urbano inca, tiene un diseño
                            poligonal y con la plaza principal en medio y calles organizadas. Tiene muchos servicios
                            turísticos como hoteles, restaurantes, cafés y es donde se toma el tren para ir a la
                            ciudadela de Machu Picchu.</li>
                        <li>Tiene varias ramificaciones del camino inca ‘Qapac Ñan’ en buen estado de conservación. </li>
                    </ul>
                    <img src="{{ asset('img/blog/el-drama-ollantay.webp') }}"
                        alt="Billetes de cien soles moneda peruana" loading="lazy">
                    <i>El drama Ollantay</i><br><br>
                    <div id="lugares" style="padding-top:70px">
                        <h2>¿Qué lugares visitar en Ollantaytambo</h2>
                    </div>                    
                    <ul>
                        <li>Los andenes</li>
                        <li>Paredes megalíticas</li>
                        <li>Caminata al Inti Watana</li>
                        <li>Vista de Tunupa</li>
                        <li>Perfil del Inca</li>
                        <li>Reloj Solar</li>
                        <li>Templo del Cóndor</li>
                        <li>Baños reales de la ñusta</li>
                        <li>El pueblo y sus calles</li>
                    </ul>
					<div id="tours" style="padding-top:70px">
                    <h2>Tours que incluyen Ollantaytambo</h2>
					</div>
                    <ul>
                        <li><a href="{{route('cusco-4-dias')}}"><strong>Cusco 4 días</strong></a> </li>
                        <li><a href="{{route('cusco-5-dias')}}"><strong>Cusco 5 días</strong></a> </li>
                        <li><a href="{{route('cusco-6-dias')}}"><strong>Cusco 6 días</strong></a> </li>
                        <li><a href="{{route('peru-6-dias')}}"><strong>Perú 6 días</strong></a> </li>
                        <li><a href="{{route('peru-8-dias')}}"><strong>Perú 8 días</strong></a> </li>
                    </ul>               

                    <!-------------Boton Top---------->

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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/es/fortaleza-de-ollantaytambo-cusco"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            </div><br><br>
            <div class="row" style="text-align: -webkit-center">
                <div class="col-lg-12 text-center">
                    <h3>Temas Similares</h3>
                    <div id="separadordjm2"></div>
                </div><br><br>
                <div class="col-lg-4 pb-3">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('gastronomia-peruana') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/peruvian-gastronomy-Peru.webp') }}"
                                alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Gastronomía peruana</h5>
                            <p class="card-text">Conoce en este blog, por qué la gastronomía peruana es reconocida a
                                nivel mundial...
                            </p>
                            <a href="{{ route('gastronomia-peruana') }}" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 pb-4">
                    <div class="card" style="width: 18rem;">
                        <a href="">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/lineas-de-nasca-colibri.webp') }}"
                                alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Lineas de Nazca</h5>
                            <p class="card-text">Las mas misteriosas y enormes figuras talladas en el desierto de Ica,
                                es una constante pregunta de ¡Para qu'e estan hechos?</p>
                            <a href="#" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 pb-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('dia-de-la-pachamama') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}"
                                alt="Card image cap">
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
