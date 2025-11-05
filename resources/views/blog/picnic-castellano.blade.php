<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>¡Disfruta de un fantástico picnic Almuerzo en los Andes!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="Picnic Almuerzo, Almuerzo en los andes, Picnic, Picnic Tour. picnic lunch de lujo, picnic lunch, almuerzo campestre, almuerzo con hermosa vista" />
    <meta name="description"
        content="Hoy les traigo una publicación que esta siendo una nueva tendencia en estos tiempos y esta entrando con fuerza en Perú." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/picnic-castellano">
    <meta property="og:title" content="¡Disfruta de un fantástico picnic Almuerzo en los Andes!">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/andean-exclusive-cusco-picnic.JPG" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/picnic-castellano">



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
                                        <a href="{{ route('picnic-en') }}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('picnic-en') }}">
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
                    <a>Picnic en los Andes</a>
                </div>
                <div class="col-lg-3 col-sm-2">
                    <div class="line"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="sticky-top" style="top:100px; z-index: 40">
                        <h4 class="text-center">Tabla de contenido:</h4>
                        <div id="separadordjm2"></div>
                        <ul class="tabla-de-contenido" style="list-style:none;">
                            <li><i class="fa fa-caret-right"></i> <a href="#descripcion">Descripción</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#realizacion">¿Cómo se realiza el Picnic
                                    Lunch?</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#comidas">Platos del Picnic Lunch</a> </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#lugares">Lugares para el Picnic Lunch</a>
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
                        <h1 id="titulo1">PICNIC ANDINO</h1>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-pencil-square"></i> Escrito por: David Miranda<br>
                        <i class="fa fa-calendar"></i> 11/06/2021
                    </center><br>
                    <img src='{{ asset('img/blog/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.webp') }}'
                        width="100%" alt="Picnic en los Andes" loading="lazy">
                    <div id="descripcion" style="padding-top:70px">
                        <h2>DESCRIPCIÓN:</h2>
                    </div>
                    <p>
                        Andean Exclusive Tours, tiene la característica de estar siempre innovando con las nuevas nuevas
                        tendencias que existen y esta vez, les hablaremos sobre una tendencia que se puso muy de moda
                        en Perú: Hablamos de los almuerzos campestres en los andes de Perú. Aprovechamos los
                        maravillosos
                        paisajes que tienen una geografia llena de montañas y nevados que rodean el valle Sagrado de los
                        Incas en Cusco, para
                        disfrutar de estos increibles almuerzos campestres.<br><br>
                        Este tipo de experiencia van de la mano con los distintos tours que ofrecemos, alguno de ellos
                        son:
                        ciclismo, motocros, caminatas, cabalgatas o Visitas en movilidad privada. Ya que normalmente
                        durante estos tours es posible tomar un descanso en medio del tour para relajarse, asearse y
                        poder disfrutar de esta experiencia en medio de las montañas.
                    </p>
                    <div id="realizacion" style="padding-top:70px">
                        <h2>Cómo se realiza el Picnic Andino</h2>
                    </div>
                    <p>Como parte de el tour que se esté realizando, esperaremos al momento del almuerzo o tiempo de
                        descanso y llegando a la zona se realizara el armado de nuestro picnic en una zona totalmente
                        privada, ubicada en un lugar
                        estratégico y con impresionantes vistas panóramicas privilegiadas en medio de la naturaleza,
                        donde podrán relajarse y disfrutar de la comodidad y tranquilidad del lugar.
                        Cabe resaltar que podrán observar la exclusividad de nuestras herramientas, utensilios e
                        indumentaria utilizadas para brindarle la mejor experiencia, asi como la excelente presentación
                        de nuestro personal de atención hasta la exquisita presentación de nuestros platos de comida.

                        Seguidamente podrán disfrutar de snacks y bebidas para amenizar y aguardar a degustar el
                        restante de platos que tenemos para ofrecer, tenemos también mates calientes de hierbas
                        naturales, o bebidas frías para aclimatar el cuerpo según el clima lo requiera.

                    </p>
                    <div id="comidas" style="padding-top:70px">
                        <h2>Comidas que podrá disfrutar en nuestro Picnic Lunch andino</h2>
                    </div>
                    <p>
                        El Valle Sagrado de los Incas, es conocido por ser un lugar propicio para poder cultivar
                        diferentes productos, los cuales son consumidos por los cusqueños de la region en su día a día.
                        Es así que la gran parte de los platos que se ofrece en el almuerzo campestre, están preparados
                        con los productos criados en está zona y de esta manera se puede decir que se ayuda
                        al campesino Cusqueño.
                        Podrá disfrutar de deliciosos aperitivos, platos de fondo y bebidas frías y calientes, todas con
                        ingredientes 100% naturales y nativos, creando una agradable y única experiencia para su
                        paladar, con el sello "Andean Exclusive". Algunas de nuestros platos en el Picnic andino son:
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
                                alt="Picnic andino de lujo en Cusco peru">
                        </div>
                        <div class="col-lg-6">
                            <img src="../img/blog/Picnic-lunch-peru.jpg" width="100%" loading="lazy"
                                alt="Picnic - Almuerzo campestre Perú"><br><br>
                        </div>
                    </div>

                    <h2>Nuestro Equipo de Picnic Lunch</h2>
                    <ul>
                        <li> Carpa Comedor</li>
                        <li> Carpa de Buffet</li>
                        <li>Carpa Baño</li>
                        <li> Mesas, Sillas</li>
                        <li> Vajilla Completa de Porcelana</li>
                        <li> Copas, Vasos</li>
                        <li> Cubiertos</li>
                        <li> Grifo Portátil para lavarse las manos, con toallas de tela</li>
                    </ul>
                    <img src="{{ asset('img/blog/Picnic-lunch-almuerzo-campestre-Cusco.webp') }}" loading="lazy"
                        alt="Presentacion de platos de Picnic Lunch" width="100%">
                    <i>Picnic Lunch con comunidad de Chinchero</i><br><br>
                    <div id="lugares" style="padding-top:70px">
                        <h2>Lugares donde se realiza el Picnic Lunch en Cusco</h2>
                    </div>
                    <ul>
                        <li> Laguna de Huacarpay</li>
                        <li> Valle sur: hacienda Chuquijahuana</li>
                        <li> Comunidad de Chinchero</li>
                        <li> Cerca al complejo arqueológico de Moray</li>
                    </ul>
                    <img src="{{ asset('img/blog/picnic-equipo-atencion.webp') }}" loading="lazy"
                        alt="Picnic andino de lujo en Cusco peru" width="100%">
                    <i>Equipo de 'Andean Exclusive Tours'</i>
                    <div id="separador"></div>
                    <h2>Video de Picnic Lunch </h2>

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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/picnic-castellano"
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
