<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>El misterio de las Lineas de Nazca - Nazca - Perú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/lineas-de-nasca-colibri.jpg">
    <meta name="keywords"
        content="Lineas de Nazca, Ica - Perú, tour en Nazca, tour de lujo en Perú, tour nazca, nazca tour, nazca exclusive tour" />
    <meta name="description" content="Descubre las líneas de Nazca, reconocidas por el misterio detrás de ellas." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/lineas-de-nazca">
    <meta property="og:title" content="El misterio de las Lineas de Nazca - Nazca - Perú">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/lineas-de-nasca-colibri.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/lineas-de-nazca">
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
                                <a href="{{ route('index') }}" rel="home" class="logo-text-link">
                                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo">
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{ route('nazca-lines-peru') }}">
                                            <button type="button" class="botondjm">English </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('nazca-lines-peru') }}">
                                            <button type="button" class="botondjm">English </button>
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
                    <a>Las lineas de nazca</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#comollegar">¿Cómo llegar?</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#datosUtiles">Datos útiles</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#colibri">Colibri</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#mono">Mono</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#frigaAves">Ave Gigante</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#arania">Araña</a></li>
                        </ul>
                        <div class="text-center">
                            <button class="boton-info"><a href="#openModal">Solicitar Información</a></button>
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
                    <h1 class="text-center">El misterio de las lineas de Nazca</h1>
                    <div id="separadordjm2"></div>
                    <p class="text-center">Sugerido por: Andean Exclusive Tours</p>
                    <p class="text-center"><i class="fa fa-calendar"></i> 10/03/2022</p>
                    <img src="{{ asset('img/blog/lineas-de-nazca.jpg') }}" width="100%"
                        alt="Misterio de lineas de Nazca" loading="lazy"><br><br>
                    <h2 id="comollegar" style="padding-top:70px">¿
                        DÓNDE ESTÁ UBICADO Y CÓMO LLEGAR A LAS LINEAS DE
                        NAZCA?</h2>
                    <p>
                        Las líneas y geoglifos de Nasca se encuentran en la región de Ica, específicamente en los valles
                        de Palpa y Nasca, a 450 kilómetros al sur de Lima y cerca del Océano Pacífico. Ubicados en el
                        borde de los Andes occidentales, se encuentran a 600 metros sobre el nivel del mar. Para llegar
                        por tierra, puede viajar en autobuses turísticos que salen de las ciudades de Lima, Arequipa y
                        Cusco. <br><br>
                        No hay vuelos comerciales programados a Ica. Sin duda, para disfrutar y observar las
                        Líneas de Nasca, es preferible verlas desde el aire, un espacio desde donde la perspectiva de
                        las figuras es única e irrepetible. Para hacer esto, el visitante puede hacer un viaje en avión.
                    </p>
                    <div id="datosUtiles" style="padding-top:70px">
                        <h2>DATOS ÚTILES PARA VISITAR LAS LINEAS DE NAZCA:</h2>
                    </div>
                    <ul>
                        <li>Es necesario llevar documentos de identidad originales (DNI o pasaporte). Los ciudadanos
                            extranjeros que no porten su pasaporte original pueden presentar una copia de su pasaporte y
                            el original de un documento emitido por su país de origen.</li>
                        <li>Se puede encontrar alojamiento de $ 10 a $ 50 por noche en los albergues más cercanos a la
                            ciudad de Nasca.</li>
                        <li>La entrada es con presentación previa del boleto o boleto. Para el sobrevuelo, se recomienda
                            hacer una reserva con anticipación, principalmente durante las temporadas altas
                            (junio-agosto)</li>
                        <li>Las aeronaves que sobrevuelan esta atracción cuentan con un guía calificado que comentará
                            las cifras a observar con la información más importante de cada una de ellas.</li>
                    </ul>
                    <div id="colibri" style="padding-top:70px">
                        <h2>Colibri</h2>
                    </div>
                    <h3>¿Qué es?</h3>
                    <p>
                        Es la más recordada de las figuras por sus dimensiones armónicas, la distancia entre sus dos
                        alas es de aproximadamente 66 metros.
                    </p>
                    <h3>Historia y descubrimiento</h3>
                    <p>
                        Considerado a través de los años como una de las figuras más representativas de esta atracción
                        turística, su descubrimiento y posterior estudio supondría actividades religiosas y cultos que
                        tuvieron lugar entre los pobladores de la antigua Nasca.
                    </p>
                    <h3>Teorías y misterios</h3>
                    <p>
                        Como la historiadora Maria Rostworowski menciona, las figuras fueron hechas para ser vistas
                        desde el cielo. Sin embargo, la función de esta figura no era comprender las estrellas, sino
                        rendir homenaje a un Dios volador respetado y temido por la gente. De esta manera, serviría como
                        una ofrenda para que esta deidad no los castigue con ausencias de lluvia, lo que en una tierra
                        seca como Nasca produciría consecuencias devastadoras.
                    </p>
                    <img src="{{ asset('img/blog/lineas-de-nasca-colibri.jpg') }}" width="100%" alt="Colibri"
                        loading="lazy">
                    <i>Foto: figura 'Colibri'</i><br> <br>

                    <div id="mono" style="padding-top:70px">
                        <h2>Mono:</h2>
                    </div>
                    <h3>¿Qué es?</h3>
                    <p>
                        La curiosidad de esta figura es que muestra al mono con nueve dedos y una cola en forma de
                        espiral. Se cree que él representa al Big Dipper. También se considera uno de los más
                        trascendentales, con un tamaño aproximado de 135 metros.
                    </p>
                    <h3>Historia y descubrimiento</h3>
                    <p>
                        El descubrimiento de estos trazos por la investigadora alemana Maria Reiche fue el punto de
                        partida necesario para alimentar las teorías que se habían desarrollado sobre un posible origen
                        astronómico, donde los antiguos pobladores de Nasca utilizaron este gigantesco geoglifo como
                        punto de partida para estudiar el cielo y prepárate para los eventos que podrían llegar.
                    </p>
                    <h3>Teorías y misterios</h3>
                    <p>
                        Como mencionó el investigador, el grupo de constelaciones espaciales tuvo una relación directa
                        con la formación de la cola y las manos del geoglifo mono. La aparición en el cielo de estas
                        estrellas estaría directamente relacionada con el acercamiento de las estaciones lluviosas,
                        extremadamente importante para una tierra como la de Nasca.
                    </p>
                    <img src="{{ asset('img/blog/linea-nazca-mono.jpg') }}" width="100%" alt="Colibri"
                        loading="lazy">
                    <i>Foto: figura 'Mono'</i><br> <br>

                    <div id="frigaAves" style="padding-top:70px">
                        <h2>Friga de aves:</h2>
                    </div>
                    <h3>¿Qué es?</h3>
                    <p>
                        Con una longitud aproximada de 160 metros, lo más característico de esta figura está en su
                        propia cabeza, que está formada por segmentos de círculos que tienen un radio que varía entre 10
                        y 20 centímetros. En el perfil, dos círculos de igual tamaño podrían representar tus ojos.
                    </p>
                    <h3>Historia y descubrimiento</h3>
                    <p>
                        Como mencionó María Reiche al momento del descubrimiento, la inmensidad de esta figura es que si
                        te paras al final de un ala no puedes ver el otro lado. Aproximadamente, es el equivalente a la
                        medida de 6 campos de fútbol.
                    </p>
                    <h3>Teorías y misterios</h3>
                    <p>
                        La realización de este geoglifo estaría dentro de la astronomía, porque su alineación
                        respondería a una configuración del cielo nocturno. Se puede ver que no está alineado con las
                        puestas de sol o la luna. Sin embargo, si está alineado con Fomalhaut, una estrella muy
                        brillante que se encuentra en la constelación de Piscis.
                    </p>
                    <img src="{{ asset('img/blog/nazca-lines-giant-bird.jpg') }}" width="100%"
                        alt="Ave gigante Friga" loading="lazy">
                    <i> Foto: Ave gigante </i> <br> <br>

                    <div id="arania" style="padding-top:70px">
                        <h2>Araña:</h2>
                    </div>
                    <h3>¿Qué es?</h3>

                    <p>
                        Al ser parte de una enorme red de líneas ubicadas en el borde de un trapezoide en la superficie
                        de la tierra, es otra de las figuras más destacadas gracias a la habilidad de hacer tu trazo.
                        Mide aproximadamente 46 metros de largo.
                    </p>
                    <h3>Historia y descubrimiento</h3>
                    <p>
                        La habilidad con la que se construyó esta figura no solo representaría un acto de arte
                        impresionante, sino que desde su descubrimiento se relacionó con la constelación de Orión, que
                        es conocida mundialmente por ser una de las más brillantes en el espacio exterior.
                    </p>
                    <h3>Teorías y misterios</h3>
                    <p>
                        Este geoglifo tiene aspectos astronómicos y religiosos. Ambos están relacionados con la
                        realización de presagios sobre la temporada de lluvias. De esta manera, cuando se encontraban en
                        un lugar muy árido, los colonos y los estudiantes utilizaron esta cifra para evitar tiempos de
                        sequía.
                    </p>


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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/lineas-de-nazca"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3>Temas Similares</h3>
                    <div id="separadordjm2"></div>
                </div><br><br>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('inti-raymi-castellano') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/inti-raymi-andean-exclusive.webp') }}"
                                alt="La fiesta del Sol Inti Raymi" loading="lazy">
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
                        <a href="{{route('picnic-castellano')}}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Picnic-lunch-peru.jpg') }}" alt="Picnic lunch andino" loading="lazy">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Picnic Lunch Andino</h5>
                            <p class="card-text">Disfruta de un almuerzo campestre, con una increible vista y comida
                                tipica de Cusco</p>
                            <a href="{{route('picnic-castellano')}}" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('dia-de-la-pachamama') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}"
                                alt="Día de la Pacha Mama" loading="lazy">
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
