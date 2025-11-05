<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>La festividad del Corpus Christi en Cusco</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="keywords"
        content="Corpus Christi, Corpus christi en Cusco, La festividad del Corpus Christi, ceremonia corpus christi, que fecha se celebra el corpus christi?, Cusco Corpus Christi 2022, Corpus christi cusqueño, datos que no sabias del corpus christi, chiriuchu, donde comer chiriuchu, plato tipico del Cusco, santos del cusco, procesion de santos en cusco">
    <meta name="twitter:image"
        content="https://andeanexclusive.com/img/blog/corpus-cristy-SAN-CRISTOBAL-ANDEAN-EXCLUSIVE.jpg">
    <meta name="description"
        content="Conoce el Corpus Christi en Cusco, una magnífica costumbre traida de Europa desde la edad media." />
    <meta property="og:url" content="https://andeanexclusive.com/blog/corpus-christi-cusco-castellano">
    <meta property="og:title" content="Corpus Christi Cusco">
    <meta property="og:type" content="article">
    <meta property="og:image"
        content="https://andeanexclusive.com/img/blog/corpus-cristy-SAN-CRISTOBAL-ANDEAN-EXCLUSIVE.jpg" />
    <meta name="author" content="DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/corpus-christi-cusco-castellano">

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
                                        <a href="{{ route('corpus-en') }}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('corpus-en') }}">
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
                    <a>Festividad de Corpus Christi</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#corpus">Corpus Christi en Cusco y su
                                    Historia</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#inicio">Inicio de la festividad y los 15
                                    santos</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#bajada">Bajada de los Santos</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#chiriuchu">Chiriuchu: El plato Tradicional
                                </a></li>

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
                        <h1 id="titulo1">Corpus Christi en Cusco</h1>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-pencil-square"></i> Escrito por: David Miranda<br>
                        <i class="fa fa-calendar"></i> 18/04/2021
                    </center>
                    <div id="corpus" style="padding-top:70px">
                        <h2>Historia del Corpus Christi</h2>
                    </div>
                    <p>
                        El <strong>Corpus Christi</strong> (cuerpo de Cristo), es la celebración religiosa más
                        grande del calendario litúrgico Cusqueño, es en sí una gigantesca
                        confluencia de tradiciones y costumbres de los diversos pueblos que
                        componen el valle del Cusco y que pocas veces se observan en un solo
                        lugar, la cual se celebra en una fecha movible que por lo general son en
                        los meses de Mayo y Junio, de acuerdo al calendario católico, se celebra
                        luego de los 60 dias después de la Pascua de Resurección; en Cusco
                        siempre se celebra un día jueves qué es el día Central, pero un día
                        antes qué es el día miércoles las imágenes que saldrán en procesión
                        realizan su entrada a la ciudad de diversos templos de diferentes
                        distritos.<br><br>
                        En el día central se lleva a cabo una misa TE-DEUM, en la cual se
                        congrega a todos los sacerdotes de la Ciudad; luego se da inicio a la
                        procesión de una hermosa Carroza de Plata en el cual sostiene a la
                        Custodia, qué está hecho de oro en la cual está el Santísimo Sacramento
                        (cuerpo de Cristo), a lo largo de la Plaza Mayor, después de ello las
                        imágenes proseguirán con la procesión.<br><br>

                        Son 15 las imágenes de los santos qué con procesiones individuales,
                        salen de su templo o iglesia habitual para hacer una visita a la Plaza
                        Mayor del Cusco y luego entrar a la Basílica de la Catedral, lugar dónde
                        hay una creencia popular qué los Santos realizan una asamblea anual y el
                        qué toma anotes de dicha reunión es San Jerónimo (que precisamente
                        sostiene un libro en sus manos); en el cual permanecerán por 8 días
                        hasta que llegue ¨La Octava¨, día en que los santos son sacados y
                        llevados a su templo o iglesia de procedencia.<br>
                        Se trata de la máxima expresión de la religiosidad cusqueña que combina
                        alegría, color, danza y música, y que ha sido declarada en 2004 como
                        Patrimonio Cultural del Perú.

                    </p>
                    <img src="../img/blog/corpus-cristy-SAN-CRISTOBAL-ANDEAN-EXCLUSIVE.jpg" width="100%"
                        loading="lazy" alt="Patrón San Cristobal">
                    <i>Foto: Patrón San Cristobal</i><br><br>

                    <h2>Una historia que nace desde el incanato</h2>
                    <p>
                        Durante la época del Tahuantinsuyo los incas realizaban cada año masivas
                        procesiones de las momias de sus difuntos por los alrededores de la
                        ciudad cusqueña. Estas eran personas que en vida tuvieron un alto
                        estatus y que, tras fallecer, sus cuerpos eran muy bien cuidados por los
                        núcleos familiares o ayllus. Sin embargo, todo cambió con la llegada de
                        los españoles; ellos, al ver esta práctica, la consideraron como pagana
                        por lo que decidieron transformarla cambiando los cadáveres momificados
                        por figuras de vírgenes y santos católicos.<br><br>
                        Según cuentan cronistas de la época, durante las procesiones los incas
                        ocultaban a sus momias por debajo de las esculturas a fin de no perder
                        sus sagradas costumbres. Con el tiempo, la ceremonia dio un giro
                        importante y creó una nueva tradición: los cuerpos momificados fueron
                        reemplazados por efigies religiosas, los núcleos familiares dieron paso
                        a los nuevos cuidadores: las iglesias, y empezó a conocerse como la
                        “Celebración del Corpus Christi”, nombre que continúa hasta la
                        actualidad.
                    </p>

                    <div id="inicio" style="padding-top:70px">
                        <h2>Inicio de la festividad del Corpus Christi y los 15 santos</h2>
                    </div>
                    <i>Inicia con la octava:</i>
                    <p>
                        El inicio de esta festividad es el día miércoles (un día antes de la
                        procesión o día central) con la salida de cada santo de su templo de
                        procedencia. Cada uno con una procesión donde se encuentra a los
                        Mayordomos o el ¨Carguyoq¨, en algunos casos el alcalde, los fieles que
                        voluntariamente deciden acompañar a su santo y acompañados por una banda
                        de músicos o ¨Q´aperos¨. Luego de llegar a la Plaza Mayor, las estatuas
                        de los santos son llevadas a la Catedral y permanecen dentro hasta el
                        día siguiente que es la procesión. El día jueves todas estas personas
                        sacan a su santo o virgen de la Basílica de la Catedral y comienza la
                        procesión. La Plaza Mayor se llena de gente. El orden en que salen los
                        santos es el siguiente:
                    <ul style="list-style:none">
                        <li>1. San Antonio de la parroquia de San Cristóbal.</li>
                        <li>2. San Jerónimo de la parroquia del distrito de San Jerónimo.</li>
                        <li>3. San Cristóbal de la parroquia de San Cristóbal.</li>
                        <li>4. San Sebastián de la parroquia del distrito de San Sebastián.</li>
                        <li>5. Santa Bárbara de la parroquia del distrito de Poroy.</li>
                        <li>6. Santa Ana de la parroquia de Santa Ana.</li>
                        <li>7. Santiago Apóstol de la parroquia de Santiago.</li>
                        <li>8. San Blas de la parroquia de San Blas.</li>
                        <li>9. San Pedro de la parroquia de San Pedro.</li>
                        <li>10. San José de la parroquia de Belén.</li>
                        <li>11. Virgen de la Natividad de la parroquia de Almudena.</li>
                        <li>12. Virgen de los Remedios de la iglesia de Santa Catalina.</li>
                        <li>13. Virgen Purificada de la parroquia de San Pedro.</li>
                        <li>14. Virgen de Belén de la parroquia de Belén.</li>
                        <li>15. Virgen de la Inmaculada Concepción llamada también "La Linda" de
                            la Basilica de la Catedral.</li>
                    </ul>
                    </p>
                    <img src="../img/blog/Corpus-Christi-Cusco-ESCLUSIVE.jpg" alt="San Jose en Corpus Christi"
                        width="100%" loading="lazy">
                    <i>Foto: Santo San José</i><br><br>
                    <p>
                        Los santos y las vírgenes están vestidos con hermosos <strong>bordados
                            de oro</strong> en
                        vestiduras diseñadas por manos artesanas. Los asistentes también visten
                        ropas tradicionales y con mucho color. Los “carguyocs”, que son personas
                        con alto nivel adquisitivo, regalan a los asistentes objetos y
                        recuerdos, mientras que los mayordomos, quienes organizan el evento,
                        cargan consigo la imagen en miniatura del santo o la virgen de su
                        pueblo, también llamadas “dote”. Detrás de los mayordomos siguen los
                        danzarines. Ellos marcan el paso de quienes tienen la función de cargar
                        la imagen del santo correspondiente. Al final de la procesión se ubican
                        las bandas de música que acompañan cada paso, y son seguidas por los
                        fieles que hacen el recorrido.
                    </p>
                    <img src="{{ asset('img/blog/corpus-christy-bandas.jpg') }}"
                        alt="Mayordomos en Corpus Christi Cusco" width="100%" loading="lazy">
                    <i>Foto: Mayordomos o carguyos</i><br><br>

                    <div id="bajada" style="padding-top:70px">
                        <h2>Bajada de Santos - Corpus Christi:</h2>
                    </div>
                    <p>
                        Se realiza por lo general al día siguiente de la octava, consiste en el
                        retorno de la sagradas imágenes a su templos o iglesias, sin embargo,
                        hay otros como la virgen de Belén que siguen protocolos distintos, esta
                        actividad multiplica los files asistentes y las danzas que acompañaran a
                        los santos patronos, de tal manera que seguir cualquiera de estos
                        cortejos resulta absolutamente deleitante.
                    </p>
                    <p>
                        También debemos mencionar las representaciones de baile que los barrios
                        protagonizan en homenaje no solo al Señor, sino también a las
                        divinidades prehispánicas, como parte de nuestro legado multicultural.
                        ¡Esta fiesta es parte de nuestro orgullo!
                    </p>
                    <img src="{{ asset('img/blog/Danza-Majeno-corpus-christi.webp') }}"
                        alt="Danza Majeños en el Corpus Christi" width="100%" loading="lazy">
                    <i>Foto: Danza Majeño en Corpus Christi</i><br><br>

                    <div id="chiriuchu" style="padding-top:70px">
                        <h2>Chiriuchu! El plato típico de esta festividad!</h2>
                    </div>
                    <p>
                        El plato tradicional de esta festividad es el Chiriuchu, plato frio y
                        picante exquisito para todos los paladares exigentes. Una mezcla de 10
                        ingredientes como son: cuy al horno (se debe tener en cuenta que la
                        carne de este roedor debe ser tierna, para que cuando alguien la consuma
                        esta sea suave ya que así se conserva el sabor característico), cau cau
                        (huevera de pescado), gallina sancochada, cecina, qochayuyo (alga
                        marina), chorizo, cancha de maíz blanco, queso, rocoto en rodajas y la
                        torreja típica. Esta torreja de consistencia esponjosa es la combinación
                        de algunos ingredientes como: maíz, zapallo y un poco de caldo de
                        gallina durante la preparación para que tenga un sabor característico al
                        momento de servirse.
                    </p>

                    <img src="../img/blog/chiriuchu-andean-exclusive.jpg" id="img-blog"
                        alt="Chiriuchu en Corpus Christi" loading="lazy">
                    <i>El Chiri Uchu acompaña las celebraciones del Corpus Christi siendo
                        muy demandado por el público asistente.</i><br><br>
                    <p>
                        Una vez tenga el plato delante lo que deberá hacer e tomar un pedazo de
                        cada uno de los elementos que lo componen y solo de esta manera sentirá
                        el exquisito sabor que hace que este plato sea un delirio cusqueño y sea
                        infaltable no solo en esta festividad, si no también en los momentos más
                        importantes de nuestras vidas, pues, sin bien la pandemia iniciada el
                        2020 no nos ha permitido seguir celebrando nuestra fiestas de la misma
                        manera, estamos seguros de que el Chiri Huchu a trascendido y de que hoy
                        03 de junio, (día en que se publica este post), día de Corpus Christi se
                        encuentra en la mesa de los Cusqueños que añoramos impacientes nuestras
                        costumbres
                    </p>
                    <img src="../img/blog/chiriuchu.jpg" width="100%" loading="lazy"
                        alt="Chiriuchu plato típico de Cusco">
                    <br>

                    <!-----Booking---------->
                    <div class="form text-center">
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
                            data-href="https://andeanexclusive.com/blog/corpus-christi-cusco-castellano"
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
            </div><br>
        </div>
    </section>
    @include('layouts.foot-castellano')

</body>

</html>
