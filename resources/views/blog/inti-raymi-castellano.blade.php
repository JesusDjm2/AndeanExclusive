<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>La fiesta del Sol "El Inti Raymi"</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/Inti-Raymi-vista-aerea.jpg">
    <meta name="keywords"
        content="Fiesta del sol, Inti Raymi, Costumbres Cusco, Costumbres Perú, intiraymi intiraymi cusco, fiesta de intiraymi" />
    <meta name="description"
        content="Inti Raymi es una palabra quechua que significa ‘Fiesta del Sol’. Todos los solsticios decinvierno (entre el 20 y el 23 de junio) el inca rendía un ritual en honor al sol, máxima divinidad en el Tahuantinsuyo." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/inti-raymi-castellano">
    <meta property="og:title" content="La fiesta del Sol 'El Inti Raymi'">
    <meta property="og:type" content="article">
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/inti-raymi-castellano">
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
                                        <a href="{{ route('inti-raymi') }}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('inti-raymi') }}">
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
                    <a>Inti Raymi</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#solfestival">El Festival del Sol</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#cuando">¿Cuándo y donde se celebra?</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#datos">Datos Curiosos</a>
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
                        <h1 id="titulo1">Inti Raymi</h1>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-pencil-square"></i> Escrito por: David Miranda<br>
                        <i class="fa fa-calendar"></i> 13/04/2021
                    </center>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/inti-raymi-andean-exclusive.jpg" alt="Picnic en los Andes">
                    </div>
                    <div id="separador"></div>
                    <div id="solfestival" style="padding-top:70px">
                        <h2>La fiesta del Sol</h2>
                    </div>
                    <p>Inti Raymi es una palabra quechua que significa ‘Fiesta del Sol’. Todos
                        los solsticios de invierno (entre el 20 y el 23 de junio) el inca rendía
                        un ritual en honor al sol, máxima divinidad en el Tahuantinsuyo.
                        Pachacutec comenzó esta tradición religioso-cultural en el siglo XV.
                        Según la cosmovisión andina, en esa época el sol volvía a comenzar su
                        ciclo. <br>
                        Antiguamente el Inti Raymi duraba unos 15 días, se hacían sacrificios y
                        se presentaban bailes o danzas para adorar al "Dios Sol". El último Inti
                        Raymi que se realizó con la presencia del Emperador Inca fue en el año
                        de 1535; un año antes de ¨La Conquista Española¨ el año de 1536. <br>
                        Actualmente el Inti Raymi es una representación teatral, miles de
                        cusqueños y personas de todas partes del mundo se dan cita a este
                        acontecimiento que es una de las manifestaciones culturales y
                        tradicionales más importantes en esta parte del mundo. Esta celebración
                        Inca hace que cada uno de nosotros sienta correr por sus venas la sangre
                        Inca y revalore nuestro pasado.</p>

                    <div id="cuando" style="padding-top:70px">
                        <h2>¿Cuando se celebra?</h2>
                    </div>
                    <p>Los 24 de junio de cada año, el Cusco celebra el Inti Raymi. Esta fecha
                        se debe a que coincide con el Día del Campesino. Es la prenda más
                        vistosa que tienen las fiestas jubilares del Cusco para mostrarle al
                        mundo. </p>
                    <h3>¿Dónde se celebra?</h3>
                    <p>La puesta en escena tiene lugar en 3 sitios específicos de Cusco con gran
                        significado histórico:</p>
                    <br>
                    <h3>1° Qoricancha</h3>
                    <p>El Templo del Sol o Qoricancha, desde las primeras horas del día, inicia
                        la Fiesta del Sol. Esta representación se realiza en el denominado
                        jardín de oro. Empieza con la aparición del inca. Los primeros rituales
                        ofrendan hojas de coca, llamas, alpacas y más.</p>
                    <img src="../img/blog/intiraymi-cusco-festivity.jpg" alt="Festividad del Inti Raymi"
                        loading="lazy">
                    <br><br>
                    <h3>2° Plaza de armas</h3>
                    <p>El centro histórico del Cusco es punto neurálgico de las celebraciones de
                        la Fiesta del Sol. La Plaza de Armas es cordonada. El inca es cargado en
                        andas por ocho sirvientes y realiza la denominada Ceremonia de la Coca.
                    </p>
                    <img src="../img/blog/intiraymi-24-de-junio-tours-fiesta-del-sol.jpg" alt=" Fiestas del Cusco"
                        loading="lazy"> <br><br>
                    <h3>3° Sacsayhuaman</h3>
                    <p>La explanada Chuquipampa en la fortaleza de Sacsayhuaman es escenario
                        principal de la representación del Inti Raymi. Este lugar gozó de mucha
                        importancia durante el incanato. </p>
                    <img src="../img/blog/Inti-Raymi-vista-aerea.jpg" alt="Vista arerea del Inti Raymi"
                        loading="lazy">
                    <br><br>

                    <div id="datos" style="padding-top:70px">
                        <h2>Datos Curiosos</h2>
                    </div>
                    <h3>1.- Inti Raymi es una de las fiestas más grandes de Perú</h3>
                    <p>Los números son grandilocuentes. Se estima que la efeméride fue creada en
                        1430 por el inca Pachacútec. En aquellos tiempos, se prolongaba por dos
                        semanas donde había danzas y ofrendas al dios-sol Inti (de allí el
                        nombre Inti Raymi, que en quechua significa “Fiesta del Sol”). En 2019,
                        cerca de 200 mil personas fueron a Cusco para el festival, que contó con
                        700 actores en escena.</p>
                    <h3>2.- La Fiesta del Sol marcaba el inicio de un nuevo año en el calendario
                        inca</h3>
                    <p>Para los pueblos antiguos, el Inti Raymi representaba un nuevo comienzo
                        al cerrar un ciclo de cosechas y abrir otro. Hoy, el festival es una
                        forma de mantener viva la cultura ancestral y reverenciar la grandeza de
                        las tradiciones cusqueñas.</p>
                    <img src="../img/blog/INTI-RAYMI-FESTIVITY-exclusive.jpg" alt="Festividad del Inti Raymi Cusco"
                        loading="lazy">
                    <br><br>
                    <h3>3.- Tres puntos turísticos de Cusco forman parte de la ruta de la
                        celebración</h3>
                    <p>El espectáculo comienza en el Complejo de Qorikancha, también conocido
                        como Templo del Sol. Este monumento fue erigido en el siglo XV como un
                        templo de adoración al dios Inti y su estructura ya estuvo adornada con
                        placas de oro. De allí, el cortejo colorido pasa por la Plaza de Armas,
                        corazón del centro histórico de Cusco y Patrimonio Cultural de la
                        Humanidad desde 1983. En el lugar también está la Catedral de Cusco y la
                        Iglesia de la Compañía de Jesús. El ápice de la fiesta ocurre en la
                        Explanada de Sacsayhuamán, donde el público ve el espectáculo desde las
                        gradas. En este complejo arqueológico, ubicado a 2 kilómetros de la
                        Plaza de Armas, es posible admirar el magnífico legado arquitectónico
                        dejado por los incas, que construyeron murallas de casi 300 metros
                        valiéndose solo de grandes piedras perfectamente encajadas entre sí.
                        <br>
                        <strong>Complejo de Qorikancha: calle Santo Domingo, s/n, centro
                            histórico de Cusco Plaza de Armas o Plaza Mayor de Cusco: entre el
                            Portal de Comercio, el Portal Belén y el Portal de Panes Explanada
                            de Sacsayhuamán: cerca de la calle Don Bosco</strong>
                    </p>
                    <h3>4.- Inti Raymi hoy es Patrimonio Cultural del país</h3>
                    <p>La fiesta no siempre tuvo tanto prestigio. En 1572, el virrey Francisco
                        Álvarez de Toledo la caracterizó de pagana y la prohibió. A pesar de
                        este impedimento, el evento siguió realizándose de modo clandestino
                        hasta 1944, cuando su ruta fue reformulada y la fecha entró nuevamente
                        al calendario oficial de la ciudad. La edición de 2019 marca los 75 años
                        de la recreación del festival, que pasó a ser considerado Patrimonio
                        Cultural de la Nación en 2001.</p>
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/inti-raymi-castellano"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
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
                                    src="{{ asset('img/blog/thumb/inti-raymi-andean-exclusive.webp') }}"
                                    alt="Card image cap">
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
                                    src="{{ asset('img/blog/thumb/lineas-de-nasca-colibri.webp') }}"
                                    alt="Card image cap">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">Lineas de Nazca</h5>
                                <p class="card-text">Las mas misteriosas y enormes figuras talladas en el desierto de
                                    Ica,
                                    es una constante pregunta de ¡Para qu'e estan hechos?</p>
                                <a href="#" id="botonnuevo">Leer artículo</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card" style="width: 18rem;">
                            <a href="{{ route('dia-de-la-pachamama') }}">
                                <img style="height: 165px; width:100%"
                                    src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}"
                                    alt="Card image cap">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">Día de la Madre Tierra</h5>
                                <p class="card-text">Al ser pateísta la religion Inca, esta tenia un día especial para
                                    la
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
