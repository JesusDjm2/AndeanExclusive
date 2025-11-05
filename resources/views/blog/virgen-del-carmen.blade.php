<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Festividad de la virgen del Carmen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="http://andeanexclusive.com/blog/img/virgen-del-carmen-andean-exclusive.jpg">
    <meta name="keywords" content="virgen del Carmen, festividad en cusco, festejo, costumbres." />
    <meta name="description" content="Esta gran festividad se da una vez al año y dura un promedio de una semana." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/virgen-del-carmen">
    <meta property="og:title" content="Festividad de la virgen del Carmen">
    <meta property="og:type" content="article">
    <meta property="og:image" content="http://andeanexclusive.com/blog/img/virgen-del-carmen-andean-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/virgen-del-carmen">
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
                                        <a href="{{ route('virgin-of-carmen') }}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('virgin-of-carmen') }}">
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
                    <a>Festividad de la Virgen del Carmen</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#historia">Historia</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#programa">Programa de la festividad de la
                                    virgen del Carmen</a>
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
                        <h1 id="titulo1">Festividad de la virgen del Carmen</h1>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-pencil-square"></i> Escrito por: David Miranda<br>
                        <i class="fa fa-calendar"></i> 15/07/2021
                    </center><br>
                    <img src="../img/blog/virgen-del-carmen-andean-exclusive.jpg" width="100%"
                        alt="virgin of Carmen" loading="lazy">
                    <i>Diablos de la festividad colgados de los balcones de las casas</i>
                    <div id="separador"></div>
                    <div id="historia" style="padding-top:70px">
                        <h2>Historia de la virgen del Carmen:</h2>
                    </div>
                    <p> A Mediados de Julio las calles de Paucartambo se llenan de música, colores, fe y una gran
                        devoción religiosa, la Fiesta de la Mamacha del Carmen reúne el folklore peruano y la mayor
                        cantidad de feligreses, entre el 15 al 18 de Julio de cada año miles de personas se conglomeran
                        con un solo sentir para rendir culto a la mamacha del Carmen, celebrándose el 16 de julio su día
                        central. Son varias las versiones sobre el origen de la festividad en honor a la Virgen del
                        Carmen, en este blog les contamos algunas: <br><br>
                        Se cuenta que existían grupos de comerciantes venidos del altiplano, llamados “Qollas”, quienes
                        intercambiaban productos como cañiwa, chuño y quesos con otros con azúcar, licor de caña,
                        chancaca y otros procedentes de la costa. La tradición indica que un día, al momento de bajar
                        los productos que venían del altiplano, uno de los Qollas encontró entre las vasijas la cabeza
                        de la imagen de la Virgen del Carmen. Esto provocó sorpresa y asombro entre los pobladores. La
                        cabeza de la imagen fue entregada a una mujer que ofrecía hospedaje a los viajeros y fue ella
                        quien mandó a confeccionar el cuerpo de la Virgen del Carmen, el cual subsiste hasta la
                        actualidad. Desde entonces los Qollas le rinden pleitesía y honores a la patrona.<br> <br>
                        Otra versión refiere que en la época colonial existían en Paucartambo haciendas y colonos
                        españoles que se dedicaban a la extracción de minerales en los lavaderos de Qosñipata. Al
                        observar que se propagaban enfermedades como el paludismo y otras que atacaban fácilmente a los
                        lugareños, los hacendados se vieron en la necesidad de llevar esclavos afroperuanos desde la
                        costa para realizar dichos trabajos. Estos nuevos moradores, que eran devotos de la Virgen del
                        Carmen, llevaron la imagen a las haciendas y la veneraban a escondidas. Cuentan que el patrón de
                        una de las haciendas los descubrió y tras convertirse en devoto mandó a construir una capilla
                        para rendirle culto. Esta versión se respalda en la danza Qhapaq Negro, una de las más
                        celebradas en la festividad de la Virgen de Paucartambo.</p>
                    <img src="../img/blog/virgen-del-carmen-andean.jpg" alt="feast in Paucartambo - Cusco"
                        width="100%" loading="lazy">
                    <i>Procesión de la virgen del Carmen</i>
                    <br>
                    <br>
                    <div id="programa" style="padding-top:70px">
                        <h2>Programa de la festividad de la virgen del Carmen</h2>
                    </div>
                    <h4>15 de Julio</h4>
                    <p>Se da inicio a la fiesta con un espectáculo de fuegos artificiales y el sonido de campanazos.
                        Luego ingresa una comparsa y la noche se convierte en cómplice para realizar una fogata. Después
                        un grupo de pobladores escenifica el purgatorio mediante saltos acrobáticos, carreras y
                        diferentes travesuras. A las 11:00 p.m. la banda de músicos le interpreta una serenata a la
                        virgen hasta el día siguiente.</p> <br>
                    <h4>16 de Julio</h4>
                    <p>Es el día central de esta majestuosa festividad. Empieza a las 5:00 a.m. con una misa de Aurora,
                        luego a las 10:00 a.m. se da la misa de fiesta donde interpretan las canciones de los Qollas y
                        los Qhapac negros (Antiguas culturas de Paucartambo). Para finalizar este sagrado día en las
                        calles se puede escuchar comparsas, danzas y cantos.</p> <br>
                    <h4>17 de julio</h4>
                    <p>Día de la gran procesión de la Virgen del Carmen y empieza a las 3:00 p.m. lo maravilloso y único
                        de esta festividad es el desenvolvimiento de los danzantes vestidos con colores brillantes y
                        máscaras, ellos suben a los techos y balcones realizando movimiento que siguen a la figura
                        musical de la banda. También están los saqras quienes son los encargados de esperar a la
                        “Patrona de los mestizos”. Este día es considerado el de la bendición y el pasacalle se dirige
                        rumbo al cementerio, para demostrar su gratitud a los danzantes fallecidos.</p> <br>
                    <h4>18 de Julio</h4>
                    <p>Fecha en que la virgen es llevada al templo y los niños esperan ser bendecidos por el sacerdote,
                        realizando el tradicional “ocarikuy”. A las 4:00 p.m. el festejo se da por finalizado con la
                        bendición del sacerdote a toda la población.</p> <br>
                    <h4>19 de Julio</h4>
                    <p>Es el último día de júbilo. Desde las 11:00 a.m. la imagen es colocada en su altar y se da inicio
                        al cambio de ropa de la sagrada escultura, una ceremonia muy especial para los pobladores. Ellos
                        se despiden rezando y acompañando hasta el último momento a la Virgen del Carmen.</p> <br>
                    <div class="text-center">
                        <img src="../img/blog/virgen-del-carmen-danza-tipica.jpg" id="img-blog"
                            alt="Danza en Paucartambo">
                    </div> <br><br>
                    <p>La Virgen del Carmen fue coronada por el papa Juan Pablo II durante su peregrinaje al Perú en
                        1985. Este acontecimiento es celebrado hoy en día en el mundo andino, como un acontecimiento muy
                        importante por la ceremonia realizada en Sacsayhuamán, en la ciudad del Cuzco. Cabe mencionar
                        que la festividad de la Virgen del Carmen de Paucartambo fue declarada Patrimonio Cultural
                        Inmaterial por el Instituto Nacional de Cultura, el 11 de abril del 2006. </p>
                    <div class="row">
                        <div class="col-12">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Xn_oFKSdTPo"
                                    frameborder="0" allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/virgen-del-carmen"
                            data-width="100%" data-numposts="5"></div>

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
