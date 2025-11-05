<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Día de la madre tierra | 1ro de Agosto | Día de la Pachamama</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/pachamama-ceremony-andean-exclusive.jpg">
    <meta name="keywords" content="Pachamama, costumbres cusco, viaja a Peru, tradiciones" />
    <meta name="description"
        content="La celebración por el día de la Pachamama es un evento que se lleva a cabo en el Cusco, se trata de una celebración" />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/dia-de-la-pachamama">
    <meta property="og:title" content="Día de la madre tierra | 1ro de Agosto | Día de la Pachamama">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/pachamama-ceremony-andean-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/dia-de-la-pachamama">
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
                                        <a href="{{route('pachamama-ceremony')}}">
                                            <button type="button" class="botondjm">English </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{route('pachamama-ceremony')}}">
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
                            <li><i class="fa fa-caret-right"></i> <a href="#eventoCultural">Un evento cultural y
                                    artístico</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#queSeCelebra">¿Qué se celebra el día de la
                                    Pachamama?</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#como">¿Cómo se celebra el día de la
                                    Pachamama?</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#mitologia">Mitologia Inca</a></li>
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
                    <div class="text-center">
                        <h1 id="titulo1">Día de la madre tierra: Pachamama Raymi</h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center">Sugerido por: "Andean Exclusive"</p>
                        <i class="fa fa-calendar"></i> 01/08/2021
                    </div>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/Pachamama-andean-exclusive.jpg" id="img-blog"
                            alt="celebración por el dia de la tierrra - Cusco">
                    </div>
                    <div id="separador"></div>
                    <div id="eventoCultural" style="padding-top:70px">
                        <h2>Un evento cultural y artístico</h2>
                    </div>
                    <p> El primero de agosto se celebra el día de la tierra o en quechua: "Pachamama Raymi", en las
                        comunidades alto andinas de Ecuador, Perú, Bolivia y Argentina. La región de los andes se
                        caracteriza por su profundo legado cultural y folclórico proveniente de las culturas Incas y
                        pre-Incas que se expresa en sus fiestas, costumbres y tradiciones. <br>
                        Es una tradición que continúa desde tiempos ancestrales incas. Muchas personas tienen un
                        estrecho vínculo con nuestra Madre Tierra (Pachamama en quechua), por lo tanto cada 1° de agosto
                        realizan una Ceremonia de Pago a la Pachamama que representa a la tierra en el más amplio
                        sentido de la palabra o sea a toda la naturaleza que nos provee el lugar y los recursos que
                        necesitamos para vivir. <br>
                        Los incas le guardaban el mayor respeto, era una de sus deidades como lo era el Dios Inti o Sol.
                        Antes de alguna festividad que realizaban, solían hacerle una previa ceremonia de
                        agradecimiento, era el tiempo de que la tierra comiera, bebiera y fumara. Era el tiempo de darle
                        mucho de lo que ella nos ha dado.</p>
                    <br>
                    <div id="queSeCelebra" style="padding-top:70px">
                        <h2>¿Qué se celebra el día de la Pachamama?</h2>
                    </div>
                    <p>Es importante saber que la mayoría de comunidades alto andinas han tenido y tienen una profunda
                        dependencia y conexión con la agricultura siendo el mes de agosto el mes en el que normalmente
                        se hacen surcos en la tierra, preparándola para recibir las semillas y por ende las nuevas
                        cosechas. En el día de la Pachamama el hombre andino trata de devolver o retornar hacia la
                        tierra todo lo que ha recibido de ella, demostrando su agradecimiento y pidiéndole que las
                        cosechas venideras sean también abundantes y prosperas. <br>
                        Dentro de los ingredientes que necesitamos para realizar el agradecimiento están: la hoja de
                        coca, feto de algún animal (de preferencia un animal andino), algunas monedas, lliqlla (manta
                        típica con motivos incas), bebidas alcohólicas (mayormente vino o cerveza), cigarrillos y
                        comida. En las festividades por el día de la Pachamama participan los hombres del campo, las
                        amas de casa, los trabajadores, los agricultores, los niños, las autoridades, las juntas
                        vecinales y otras organizaciones.</p>
                    <div class="text-center">
                        <img src="../img/blog/pachamama-pago-a-la-tierra.jpg" id="img-blog"
                            alt="Pago a la tierra - Pachamama">
                    </div>
                    <br> <br>
                    <div id="como" style="padding-top:70px">
                        <h2>¿Cómo se celebra el día de la Pachamama?</h2>
                    </div>
                    <p>Como parte de las celebraciones por el día de la Pachamama los hombres y mujeres de las
                        comunidades alto andinas se reúnen el primero de agosto en una ceremonia que incluye entre otras
                        cosas:
                    <ul>
                        <li>Un hoyo o hueco cavado en el suelo, que es donde se pondrán todas las ofrendas y regalos
                            para la Pacha Mama. Este hoyo tiene una profundidad de 50 centímetros y un diámetro de 1
                            metro aproximadamente, dependiendo del número de regalos y el tamaño de la ceremonia.</li>
                        <li>Una manta de lana o ‘Lliclla’ que es la prenda con la que se envolverán los regalos, antes
                            de cubrirlos con tierra.</li>
                        <li>Fetos de animales, generalmente se utiliza fetos de cordero, alpaca o llama. Simbolizan
                            fertilidad.</li>
                        <li>Cerveza, comida, hoja de coca, cigarros, confeti, dulces, galletas.</li>
                    </ul>
                    Durante la procesión algunos carguyoqs dan regalos a los asistentes, objetos como postales,
                    recuerdos, etc; son repartidos sin distinción alguna. La procesión termina aproximadamente a las 12
                    del medio día después de que todos los santos regresen a la Catedral donde permanecerán hasta el
                    próximo jueves en que los fieles, el carguyoq, alcalde y demás personas retornan a sus santos hasta
                    su lugar de origen, el templo del que fueron sacados.
                    <strong>La idea es ‘hacer comer, beber y fumar a la Pacha Mama’. Al final de la ceremonia se coloca
                        madera o leña en el hueco, se envuelve todo lo anterior con la lliclla y finalmente todo es
                        consumido por el fuego, para luego ser cubierto con tierra. Durante toda la ceremonia las
                        personas beben cerveza, consumen diversos platos típicos y celebran con danzas y
                        música.</strong>
                    </p> <br>
                    <div class="text-center">
                        <img src="../img/blog/pachamama-ceremony-andean-exclusive.jpg" id="img-blog"
                            alt="Ceremonia de pago a la tierra">
                    </div> <br><br>
                    <div id="mitologia" style="padding-top:70px">
                        <h2>Mitología Inca</h2>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/pachamama-trilogia-inca.jpg" id="img-blog" alt="Trilogia andina">
                    </div>

                    <p>Los incas tenían una religión ‘panteista’ es decir que creían que el Dios creador y la naturaleza
                        (montañas, valles, ríos, rayos, truenos, etc.) eran en realidad una sola entidad o concepto. Los
                        incas no conocían la escritura de modo que la mayoría de las tradiciones y mitos se han
                        transmitido de manera oral de generación en generación. <br>
                        De acuerdo a la cosmovisión de los Incas, el espacio o tiempo estaba dividido en tres planos:
                    </p>
                    <ul>
                        <li> <strong>Hanan Pacha:</strong> Esta palabra significa ‘el mundo de arriba’. Es representado
                            por un cóndor. Era el mundo celestial, reservado para aquellas personas que habían sido
                            justas durante su vida y al cual se accedía a través de un puente elaborado con cabellos. En
                            este mundo superior habitan los dioses más importantes como Wiracocha, como más adelante
                            ampliaremos.</li>
                        <li><strong>Kay Pacha:</strong> Esta palabra significa ‘el mundo presente, el aquí y ahora’. Es
                            representado por uin puma. Es el mundo donde habitamos los humanos, los animales y algunos
                            Dioses como la Pachamama, Mama Sara y el Pariacaca.</li>
                        <li><strong>Uku Pacha:</strong> Esta palabra significa ‘el mundo de abajo, el mundo de los
                            muertos’. Es representado por una serpiente. En este mundo según la mitología andina habitan
                            los niños no nacidos, los muertos y todo lo que estaba por debajo de la superficie de la
                            tierra o del mar. Ellos creían que las cuevas, las fuentes y otras aberturas de la
                            superficie terrestre eran en realidad los portales o líneas de conexión entre el Uku Pacha y
                            el Kay Pacha. El Dios más importante de este mundo es llamado ‘Supay’ aunque también habita
                            allí la Diosa Mama Cocha y el Dios Pachacamac.</li>
                    </ul>
                    <iframe width="100%" height="400" src="https://www.youtube.com/embed/xADyW6k8Epk"
                        title="2 de agosto: El 1 de Agosto se celebra el Día de la Pachamama - Historia al Día"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe><br><br>


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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/dia-de-la-pachamama"
                            data-width="100%" data-numposts="5"></div>
                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            </div><br><br>
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
