<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Conoce Lima "La Ciudad de los Reyes"</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/plaza-de-armas-cusco.jpg">
    <meta name="keywords"
        content="conoce lima, lima, visitar lima, lugares que visitar en Lima, actividades en lima, conocer Lima, tour en Lima, turismo en Lima, museos de lima, parques de Lima" />
    <meta name="description"
        content="Conoce la historia de la ciudad de Lima, y conoce 10 lugares que visitar, imprescindibles para que no te falte ningun detalle al viajar a la Ciudad de los Reyes." />
    <meta property="og:url" content="https://andeanexclusive.com/blog/lima-ciudad-de-reyes">
    <meta property="og:title" content="Conoce Lima 'La Ciudad de los Reyes'">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/plaza-de-armas-cusco.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/lima-ciudad-de-reyes">
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
                                        <a href="{{ route('lima-city-of-the-kings') }}">
                                            <button type="button" class="botondjm">English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('lima-city-of-the-kings') }}">
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
                    <a>Lima, Ciudad de los Reyes</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#fundacion">Fundación y la Lima moderna</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#lugares">10 lugares qué conocer en Lima</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#elolivar">Parque El Olivar</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#larco">Museo Larco</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#circuitomagico">Circuito Mágico del Agua</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#miraflores">Malecón de Miraflores y vuelos
                                    en parapente </a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#parqueamor">Parque del Amor</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#huaca">Huaca Puccllana</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#realfelipe">Fortaleza del Real Felipe</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#palomino">Islas Palomino</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#puntanegra">Punta Negra</a></li>

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
                        <h1 id="titulo1">Conoce Lima "La ciudad de los Reyes" </h1>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-pencil-square"></i> Escrito por: David Miranda<br>
                        <i class="fa fa-calendar"></i> 10/05/2021
                    </center>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/conoce-lima.webp" id="img-blog" alt="Turismo en Lima">
                    </div>
                    <div id="separador"></div>
                    <p> En el siglo XVI, Lima fue la metrópoli más importante y poderosa de
                        Latinoamérica gracias a su ubicación, diversidad natural y riqueza
                        cultural, actualmente exhibida en sus museos, barrios tradicionales y
                        galerías artesanales. La arquitectura barroco y renacentista maravilla a
                        los viajeros por sus construcciones con estilo virreinal, resaltando la
                        conservación de sus iglesias, conventos y casonas con elegantes balcones
                        tradicionales..</p>

                    <div id="fundacion" style="padding-top:70px">
                        <h2>Fundación de Lima</h2>
                    </div>
                    <p>
                        Año 1532. Un contingente de conquistadores españoles, dirigidos por
                        Francisco Pizarro, derrotaron a Atahualpa, conquistando así el Imperio
                        Incaico. Como la Corona Española le otorgó la facultad a Pizarro de
                        autonombrarse gobernador de toda ciudad, pueblo o espacio que
                        conquistase,
                        el colonizador español inició la búsqueda de lo que sería su nueva
                        capital,
                        ya que desecharon instalarse en el Cusco. <br>

                        La primera opción fue entonces Jauja, en lo que ahora conocemos como
                        Junín.
                        Sin embargo, los exploradores españoles Ruy Díaz, Juan Tello y Alonso
                        Martín
                        de Don Benito, desestimaron esta idea debido a su lejanía con el mar, su
                        altitud andina y lo accidentado que podría ser llegar hasta este punto
                        cuando el clima no era favorable. Así, estos mismos guías, al mando de
                        Francisco Pizarro, dieron cuenta de un lugar ideal para establecerse
                        donde
                        levantar su nueva urbe. Sería el Valle del Rímac, con su clima templado,
                        madera por doquier, espaciosos campos de cultivo, interminables
                        provisiones
                        de agua y la cercanía con el Océano Pacífico, lo que lo convertía en la
                        zona
                        perfecta.<br>

                        Así, con fecha de fundación del 18 de enero de 1535, este espacio del
                        valle
                        del Rímac (pronunciado como Limac por los Yungas de la zona) se
                        convirtió en
                        la ‘Ciudad de los reyes’, la nueva capital del Perú, que soportó
                        invasiones,
                        guerras y rebeliones. Pero que, a pesar de lo difícil que fue sostenerla
                        en
                        el tiempo, siempre se quedó de pie, para convertirse en la región más
                        importante del Perú hasta la fecha. <br>
                    </p>
                    <img src="../img/blog/fundacion-lima.webp" width="100%" alt="Plaza de Armas- Lima"
                        loading="lazy"><br><br>

                    <h3>La Lima Moderna</h3>
                    <p>Lejos de los rifles y las hondas del pasado, hoy por hoy Lima goza de una
                        estabilidad y atrae a muchos turistas del mundo entero. La capital del
                        Perú es rica en cultura, historia y gastronomía, y cuenta con un sinfín
                        de actividades para realizar y atractivos destinos por conocer. <br>
                        Desde
                        visitar su centro histórico, que aún guarda edificaciones de la época
                        colonial, museos, iglesias, hasta realizar una ruta para degustar su
                        reconocida gastronomía. <br> Además, tiene un atractivo extra al ser la
                        única
                        capital de Sudamérica con vista al mar. Así es Lima, mágica, emblemática
                        y, sobre todo, bella.</p> <br>

                    <img src="../img/blog/lima-moderna.webp" width="100%" loading="lazy" alt="Centro de Lima">

                    <div id="lugares" style="padding-top:70px">
                        <h2>10 lugares turísticos de Lima que necesitas
                            conocer</h2>
                    </div>
                    <h3>1. CENTRO HISTÓRICO DE LIMA </h3>
                    <p>
                        Una de las principales atracciones de las ciudades más longevas a lo
                        largo del mundo entero, es la visita a su Centro Histórico. Y el de la
                        majestuosa ciudad de Lima no podría ser la excepción: en las calles,
                        iglesias y edificios, puedes dar un salto y visitar el pasado en primera
                        persona.Como si se tratase de una máquina del tiempo a la cual acudes
                        para ver la antigua Lima, esa que guarda su toque colonial hasta hoy en
                        día. <br>
                        Recorrer su centro histórico, declarado Patrimonio Cultural de la
                        Humanidad, es convivir con el pasado y el presente. Sus hermosas plazas
                        y monumentos; sus imponentes casonas con balcones coloniales y
                        republicanos; sus múltiples iglesias que albergan invaluables obras del
                        arte; y sus restaurantes y tabernas repletas de sabor y tradición, son
                        algunas de las opciones que pueden recorrerse a pie. <br>
                        Todo el mundo coincidirá que lo primero que tienes que conocer es la
                        Plaza de Armas y los atractivos que lo rodean: además de presenciar uno
                        de los puestos más emblemáticos de la ciudad, adornada con una hermosa
                        pileta de la época colonial, podrás ver el esplendor de la Catedral de
                        Lima. Esta es una de las incontables iglesias, claustros y santuarios
                        que están ubicadas a lo largo del centro de Lima. <br>

                    </p>
                    <img src="../img/blog/centro-historico-lima.webp" width="100%" loading="lazy"
                        alt="Catedral de Lima"><br><br>
                    <p>Muy cerca se encuentra la Municipalidad de Lima, que guarda en su
                        interior un elegante y fino acabado en cada uno de sus pasillos. Por
                        otro lado, podrás presenciar el Palacio de Gobierno, desde donde rige el
                        máximo mandatario del país y en el cual puedes presenciar el cambio de
                        guardia al medio día, considerado casi un espectáculo por los turistas.
                        <br>
                        Desde una larga caminata por la siempre concurrida Jirón de la Unión -la
                        calle más importante del Centro Histórico- hasta llegar al barrio chino,
                        donde puedes degustar de los famosos restaurantes chifas (comida fusión
                        peruana-oriental); hasta las visitar a los museos más impresionantes de
                        la capital, como el Museo de la Santa Inquisición, sin dejar de pasar
                        por las Catacumbas de Lima, para tener un escalofriante y culturizarte
                        recorrido a la vez. <br>
                    </p>
                    <img src="../img/blog/centro historico-lima2.webp" width="100%" loading="lazy"
                        alt="Centro Histórico de Lima"> <br> <br>

                    <h3>CÓMO LLEGAR </h3>
                    <p> <i> Las empresas de transporte urbano más importantes del país rodean
                            las
                            calles principales del centro, por lo que llegar a ella no será
                            complicado. Igual, puedes transportarte hacia el centro con un taxi
                            o
                            una aplicación de traslados, para sentirte más seguro y ganar algo
                            de
                            tiempo extra para recorrer sus calles. </i>
                    </p>
                    <div id="elolivar" style="padding-top:70px">
                        <h3>2. PARQUE EL OLIVAR </h3>
                    </div>
                    <p>
                        El Parque El Olivar es un hermoso parque tipo bosque en pleno distrito
                        financiero de San Isidro, poblado con más de 1500 árboles de olivos y
                        227 árboles de otras especies.
                        Este parque data del período colonial, época en la cual comenzaron
                        también a construirse casas alrededor del parque, por lo que hoy día
                        podemos encontrar casonas que con el pasar de los años y en una Lima
                        modera, poseen aun una arquitectura de esa época. <br>
                        Por su antigüedad el Parque El Olivar fue declarado Monumento Nacional
                        en 1959.<br>

                        Un pulmón verde de la ciudad, el Parque El Olivar de San Isidro es hogar
                        de una serie de árboles de Olivo únicos en el Perú por su longevidad. El
                        Parque también posee centros culturales, museos, bibliotecas, un teatro
                        y una galería de arte. <br>
                        Este parque poblado de arboles olivares es muy popular entre los limeños
                        que acuden los fines de semana para disfrutar de la calma y el verdor
                        del lugar, ideal para hacer un picnic. A unas pocas cuadras, por la
                        calle Esquilache, encontrarás un supermercado Vivanda donde podrás
                        comprar todo lo que necesitas para una comida en el parque.
                    </p>
                    <img src="../img/blog/Parque-El-Olivar-de-San-Isidro.webp" id="img-blog"
                        alt="Parque el Olivar - San Isidro" loading="lazy">
                    <br><br>
                    <h3>CÓMO LLEGAR </h3>
                    <i>
                        Para llegar, Desde Avenida Arequipa debes dirigirte hacia el este
                        por Pallardeli, donde llegarás al centro del parque. La opción más
                        recomendada por la comodidad y seguridad, es en taxi.
                    </i>
                    <div id="larco" style="padding-top:70px">
                        <h3>3. MUSEO LARCO</h3>
                    </div>
                    <p>
                        Perú posee una gigantesca historia precolombina que debes conocer cuando
                        visites Lima. Uno de los mejores lugares para absorber esa cultura es
                        visitando el Museo Larco.<br>
                        Rodeada por hermosos jardines, una mansión virreinal del siglo XVIII
                        resguarda una impresionante colección privada de más de 40 mil piezas de
                        arte del Perú precolombino. que datan de hasta 5 mil años de antigüedad.
                        Dentro de la muestra permanente del museo destaca la Sala de Oro y Joyas
                        con maravillosas piezas hechas por virtuosos orfebres del antiguo Perú
                        para honrar a sus dioses. No menos atención capta la sala de huacos
                        eróticos, que nos muestra su interesante visión sobre la sexualidad.
                        <br>
                        Definitivamente, visitar el museo Larco es conectarse y reconectarse con
                        la cosmovisión andina. <br>
                    </p>
                    <img src="../img/blog/museo-larco.webp" id="img-blog" alt="Museo Larco Lima" loading="lazy">
                    <br><br>
                    <h3>¿Cómo llegar?</h3>
                    <i>
                        Recomendamos usar un tour para visitar el museo ya que no es una
                        zona muy turística.
                        El servicio de un taxi también pudiera convenir, prevee el regreso
                        al mismo tiempo. El recorrido desde Miraflores puede tomar entre 15
                        y 30 minutos. <br><strong> Horario de atención: </strong> lunes a
                        domingo de 11:00 a.m. a 7:00 p.m.
                    </i>
                    <div id="circuitomagico" style="padding-top:70px">
                        <h3>4. CIRCUITO MÁGICO DEL AGUA </h3>
                    </div>
                    <p>
                        El Circuito Mágico del Agua es un parque de Lima en donde podrás
                        disfrutar de un espectáculo nocturno de fuentes de agua y luces,
                        se ubica en el Parque la Reserva y también es conocido como el “Parque
                        de las Aguas”, siendo una de las actividades obligadas qué hacer en
                        Lima. <br>
                        Fue inaugurado en el 2007 y en el 2009 le fue otorgado el récord
                        Guinness por ser el complejo de fuentes más altas del mundo en un parque
                        público.<br>
                        El Circuito Mágico del Agua es un alucinante recorrido por 13 fuentes
                        ornamentales que son controladas electrónicamente y en conjunto con un
                        juego de luces sincronizadas ofrecen un espectáculo visual.
                        Lo más impresionante son las proyecciones de bailarines en movimiento en
                        una nube gigante de agua que se forma en la fuente principal del Parque
                        de las Aguas. <br>
                        Las personas pueden ingresar a algunas de las fuentes e interactuar con
                        los chorros de agua.
                        Especialmente en una de las fuentes que forma un largo lunes de agua y
                        que puede ser atravesado por los visitantes.<br>
                    </p>

                    <img src="../img/blog/circuito-del-agua.webp" id="img-blog" alt="Circuito Mágico del Agua Lima"
                        loading="lazy"><br> <br>
                    <h3>¿Cómo llegar? </h3>
                    <ul>
                        <li>En bus metropolitano hasta la estación Estadio Central. Otra
                            opción es usando el Sistema Integrado de Transporte que recorre toda
                            la avenida Arequipa, hasta la estación Ramón Dagnino en todo el
                            frente del parque.</li>
                        <li>También recomendamos usar taxi para llegar al Parque de la
                            Reserva. Es más seguro sobre todo si lo visitas en la noche.</li>
                        <li>Finalmente, la mejor opción es tomar un tour que incluya el
                            recorrido por el circuito mágico del agua.
                            <strong> Horario de atención: </strong> martes a Domingo a partir de
                            las 15h hasta las 22h30. Inclusive los días feriados.
                            Sin embargo, los espectáculos de agua comienzan a las 19h15, 20h15 y
                            21h30.
                        </li>
                    </ul>

                    <div id="miraflores" style="padding-top:70px">
                        <h3>5. MALECÓN DE MIRAFLORES</h3>
                    </div>
                    <p>
                        El Malecón de Miraflores es un parque con caminerías que recorre el
                        borde superior del acantilado de la Costa Verde que pertenece al
                        distrito de Miraflores. La vista del océano pacífico desde el Malecón de
                        Miraflores no tiene discusión, es hermosa. Por esta razón es una de las
                        cosas que no puedes dejar de hacer en Lima. <br>
                        Y no sólo por su vista si no por sus abundantes espacios verdes, este
                        malecón es sin dudas, una de las zonas más bonitas de Miraflores y
                        también de todo Lima, te lo garantizamos. Son 5 kilómetros en total los
                        que conforman el recorrido del Malecón de Miraflores, que está dividido
                        en 3 tramos.<br>
                        A todo lo largo del malecón se encuentran parques, jardines con flores,
                        canchas deportivas, estaciones de ejercicios y monumentos. Todo tan
                        perfectamente cuidado que dan ganas de no irse. En verano la gente
                        aprovecha para hacer picnic o recostarse en la grama disfrutando del sol
                        y los parapentes que abundan en el lugar.<br>
                        No puedes perderte la puesta del sol desde cualquier punto del Malecón
                        de la Marina, desde donde tendrás una vista envidiable de la Costa Verde
                        y sobre todo el Morro Solar de Chorrillos.<br>
                        Estando en el Malecón no puedes dejar de ver el famoso ‘Faro de la
                        Marina’ de 21 metros de altura y un alcance de 45 kilómetros que fue
                        construido en 1900. A un lado de la calzada, a todo lo largo del malecón
                        hay una ciclovía que puedes usar si deseas hacer el recorrido en
                        bicicleta, que se pueden alquilar en Larcomar. <br> Alista tu ropa y
                        zapatos
                        más cómodos y prepárate para un paseo único en este hermoso Malecón de
                        Miraflores.<br>
                    </p>
                    <img src="../img/blog/malecon-miraflores.webp" id="img-blog" alt="Malecón Miraflores"
                        loading="lazy">
                    <br><br>
                    <h3>La experiencia de volar en parapente en Lima </h3>
                    <p>
                        Exactamente en el Parque Antonio Raimondi verás despegar y aterrizar los
                        parapentes que sobrevuelan todo el malecón de Miraflores.
                        Por su costo, es una actividad que no querrás perderte, además que
                        tendrás la oportunidad de ver a Lima desde un ángulo único.
                        Los paseos de parapente en Lima duran 10 minutos y comienzan todos los
                        días desde las 10h siempre y cuando haya las condiciones necesarias de
                        viento y visibilidad.
                    </p>
                    <img src="../img/blog/vuelo-parapente.webp" id="img-blog" alt="Vuelo en parapente, Lima"
                        loading="lazy"><br> <br>
                    <h3>CÓMO LLEGAR </h3>
                    <p>
                        Usando el Sistema Integrado de Transporte que transita la avenida
                        Larco hasta la parada frente a ‘Larcomar’. <br>
                        La opción más recomendada es en taxi. Tendrás que indicar al chofer
                        a que nivel del malecón desea ir.
                    </p>
                    <div id="parqueamor" style="padding-top:70px">
                        <h3>6. EL PARQUE DEL AMOR</h3>
                    </div>
                    <p>
                        El parque del Amor es uno de los parques de Lima con más visitas de
                        turistas y locales, debido a su historia y la vista espectacular del
                        mar. Inaugurado en honor al día de los enamorados, el parque del Amor se
                        conoce como tal producto de la costumbre de los enamorados de disfrutar
                        el atardecer desde allí. <br><br>
                        Los parques de Lima se caracterizan por su verdor, sobre todo en
                        Miraflores, siendo este parque uno de los lugares infaltables en tu
                        visita en la ciudad. Disfruta de la vista espectacular que ofrece el
                        parque del Amor hacia el océano Pacifico, sentado en unos banquitos con
                        mosaicos en cerámica de varios colores con mensajes varios. El parque te
                        recibe con un jardín de flores en forma de corazones. Aunque el parque
                        no es muy grande, posee puntos para sentarse y disfrutar de las vistas
                        al mar. <br><br>
                        En el centro del parque se encuentra una escultura llamada “el beso”
                        realizada por el artista Víctor Delfín, en ella dos personas se dan un
                        beso, siendo el icono principal del lugar. Muchas fotografías se asocian
                        a los malecones de Miraflores con el parque del Amor, y es que el lugar
                        es verdaderamente de foto así que prepara tu cámara para tener tus
                        recuerdos de allí. <br> Miraflores es uno de los distritos más seguros
                        de
                        Lima y preferido por los turistas, así que podrás disfrutar
                        tranquilamente de tu visita en el parque. <br><br>
                        Es recomendado conocerlo en tour porque no pasarás mucho tiempo allí y
                        puedes aprovechar el tiempo para conocer más lugares de la ciudad.
                        <br>
                    </p>

                    <img src="../img/blog/parque-del-amor.webp" id="img-blog" alt="Parque del amor - Lima"
                        loading="lazy"> <br> <br>
                    <h3>CÓMO LLEGAR </h3>
                    <p>
                        Ubicado en un tramo del Malecón de Miraflores. Lo más seguro y
                        recomendado es acceder a un taxi o visitarlo con un tour turístico.
                    </p>
                    <div id="huaca" style="padding-top:70px">
                        <h3>7. HUACA PUCLLANA </h3>
                    </div>
                    <p>
                        Siguiendo en línea con el pasado precolombino del Perú, uno de los
                        lugares turísticos de Lima más recomendados es la Huaca Pucllana.
                        Perteneciente a la cultura Lima, data del período de los desarrollos
                        regionales (200 – 700 d. C.). El complejo arqueológico posee una
                        instalación de luces LED que hacen que su visita durante la noche sea
                        aún más espectacular. <br><br>
                        La Huaca Pucllana es un sitio arqueológico ubicado en el distrito de
                        Miraflores.
                        Estos vestigios pertenecientes entre los años 400 y 1400 de las culturas
                        pre-incas Lima, Wari e Ychsma, fueron abandonados y no valorados durante
                        mucho tiempo hasta el año 1967 cuando fue recuperado este mítico sitio.
                        En 1984 es inaugurada oficialmente la Huaca Pucllana y en 1987 fue
                        declarada zona arqueológica intangible por el estado peruano. Una de las
                        visitas que recomendamos hacer durante tu viaje a Lima. <br><br>
                        Las visitas guiadas inician explorando las partes bajas de la pirámide
                        en donde se observan vasijas y vestigios de antiguas plazas de los
                        antiguos pobladores de Lima.<br><br><br>
                        En alguna parte del recorrido, una maqueta muestra, más allá de los
                        límites del sitio arqueológico actual, una representación de espacios y
                        la manera cómo funcionaba la cultura Lima. <br><br><br>
                        Parte del recorrido nos muestra la flora y la fauna típicas de la época
                        prehispánica, en donde se pueden ver las simpáticas llamas.
                        El atractivo principal es la pirámide truncada de 7 niveles, que fue
                        construida con ‘adobitos’, unos ladrillos rectangulares de barro y
                        secados al sol. A lo alto de la pirámide, resulta interesante ver el
                        contraste de las ruinas con los edificios y casas modernas de las calles
                        que rodean el sitio arqueológico.
                    </p>
                    <img src="../img/blog/huaca-pucllana.webp" id="img-blog" alt="Huaca Pucllana - Lima"
                        loading="lazy">
                    <br><br>
                    <h3>Restaurante HUACA PUCLLANA </h3>
                    <p>Dentro del sitio arqueológico se encuentra el Restaurante Huaca Pucllana
                        con su chef Marilú Madueño y su propuesta de comida peruana con estilo
                        moderno. Es sin duda una experiencia diferente probar estas versiones
                        modernas de la cocina típica peruana mientras se admiran las ruinas de
                        los antiguos habitantes de Lima.
                        El Restaurante Huaca Pucllana se encuentra abierto todos los días de
                        12H30 a 16h y de 19h a 24h en donde te recomendamos degustar algunas de
                        sus especialidades como: </p>
                    <ul>
                        <li>Rocoto relleno a la arequipeña.</li>
                        <li>Postre quizás, el mousse de lúcuma, quinua roja crocante y doble
                            chocolate.</li>
                    </ul>
                    <div class=row>
                        <div class="col-lg-6 text-center">
                            <img style="margin-bottom: 1em"
                                src="{{ asset('img/blog/restaurante-huaca-pucllana.webp') }}" id="img-blog"
                                alt="Huaca Pucllana restaurante" loading="lazy">
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src=" {{ asset('img/blog/comida-huaca-pucllana.webp') }}" id="img-blog"
                                alt="Restaurante Huaca Pucllana" loading="lazy">
                        </div> <br> <br>
                    </div>
                    <h3>CÓMO LLEGAR </h3>
                    <ul>
                        <li>En bus metropolitano hasta la estación Angamos. Al salir de la
                            estación es necesario caminar 7 cuadras por la avenida Angamos en
                            dirección Oeste hasta llegar a la calle General Borgoño donde se
                            debe cruzar a la derecha. Enseguida verás a la pirámide
                            truncada.</li>
                        <li>De igual forma se recomienda llegar a través de un servicio de
                            taxi con destino a este lugar. </li>
                        <li><strong> Horario de atención: </strong> Visita diurna: De miércoles
                            a Lunes a partir de las 9h hasta las 17h. Visita nocturna: De
                            Miércoles a Domingo a partir de las 19h hasta las 22h. (Durante la
                            visita nocturna no se podrá llegar al nivel más alto del a pirámide
                            por razones de seguridad.).</li>
                    </ul>

                    <div id="realfelipe" style="padding-top:70px">
                        <h3>8. FORTALEZA DEL REAL FELIPE</h3>
                    </div>
                    <p>
                        La Fortaleza del Real Felipe es una de las mejores atracciones
                        turísticas del Callao, que también es conocido con el Museo del
                        Ejército. La construcción de esta edificación militar culminó en 1775 y
                        se realizó por pedido del virrey José Antonio Manso de Velasco para
                        evitar los ataques de piratas, aprovechando la visita del francés Luis
                        Godin y otros expertos españoles. Hoy en día es uno de los más visitados
                        lugares turísticos cerca de Lima.<br><br>
                        Fue construida en piedra y calicanto (mezcla de arena, cal y clara de
                        huevo de aves guaneras), ocupando una extensión de aproximadamente 7
                        hectáreas. Su forma es pentagonal, y en cada punta del pentágono, hay un
                        bastión.
                        Las visitas son guiadas de aproximadamente 120 minutos y en ellas te
                        explican todos los puntos de interés ubicados en cada uno de los 5
                        bastiones de la Fortaleza del Real Felipe.<br><br>
                        <strong>La primera parada</strong> es en el parque de la artillería. Un
                        patio con una muestra de cañones que el ejercitó utilizó en diferentes oportunidades.
                        También está el parque de vehículos blindados con una exposición de
                        tanques de guerra. <br><br>
                        <strong>La segunda atracción</strong> es la Casa del Gobernador, un
                        museo con exposiciones de piezas y uniformes militares de diferentes épocas.
                        Antiguamente era el lugar de residencia del jefe militar de la
                        fortaleza.<br><br>
                        El recorrido continúa con una mirada a la réplica parcial de la Casa de
                        la Respuesta (casa de relevancia histórica ubicada en Arica), acompañada
                        por el monumento al soldado desconocido, que rinde honor a los militares
                        caídos. <br><br>
                        <strong>Sigue el Torreón de la Reina</strong>, al cual no se puede
                        acceder y toca admirarlo desde afuera. Este lugar sirvió de deposito de municiones y
                        también de calabozos para los prisioneros.<br><br>
                        Y finalmente se llega al <strong>Torreón del Rey</strong>, que posee 3
                        niveles, a los cuales sí se pueden acceder. El interior de esta torre sirvió de búnker
                        con pasillos angostos que dan la impresión de ser un laberinto. <br> <br>
                        En la parte superior se encuentran 2 niveles en donde se encuentran
                        plataformas con cañones antiguos. Las vistas que se tienen de la costa,
                        son muy bonitas.
                    </p>
                    <img src="{{ asset('img/blog/real-felipe.webp') }}" id="img-blog"
                        alt="Fortaleza Real Felipe - Lima - Callao" loading="lazy">
                    <br><br>
                    <h3>CÓMO LLEGAR </h3>
                    <p>
                        No es fácil llegar desde Lima hasta este museo militar. Por lo que
                        recomendamos tomar un tour que incluya esta atracción. Las empresas
                        de tours más reconocidas se ubican en el parque Kennedy donde podrás
                        comprar tu boleto.
                        <strong> Horario de atención: </strong> Martes a Domingos de 9h a
                        16h.
                    </p>
                    <div id="palomino" style="padding-top:70px">
                        <h5>9. ISLAS PALOMINO</h5>
                        <p>
                            Uno de los lugares turísticos de Lima más populares para quienes deseen
                            una experiencia directa con la fauna marina de la capital. Las Islas
                            Palomino, llamadas también Islotes Palominos, son un grupo de pequeñas
                            islas que albergan una numerosa población de lobos marinos y aves
                            marinas.<br>
                            Este grupo de islas están llenas de historias, como la isla San Lorenzo
                            que es conocida como la isla misteriosa porque se cree habían piratas y
                            sus tesoros.<br> También está la isla Frontón que en la colonia era una
                            cárcel política por lo que se ganó el nombre de isla muerto.<br>
                            A pocos minutos de Lima, la isla Palominos se convierte en una opción
                            muy recomendada como lugar turístico cerca de la ciudad, sobre todo para
                            los amantes de la naturaleza.<br>

                            • La isla Palominos es el hogar de los lobos de mar, los cuales habitan
                            en las costas de Suramérica, muy característicos por su melena rojiza y
                            por tener una vida de hasta 50 años. <br>
                            • Vive la experiencia de nadar junto a estos animales desde la isla
                            Palominos.<br>
                            • Disfruta además del paisaje que ofrece esta isla, ya que es digna de
                            muy buenas fotografías. <br>
                            • La isla vecina se llama Cavinzas, donde se refugian los lobos de mar
                            viejos, pero además es el hogar de una variedad de aves guaneras como
                            los pelicanos. <br>
                        </p>
                        <img src="../img/blog/islas-palomino.webp" id="img-blog"
                            alt="Islas Palomino - Islas Ballestas - Lima" loading="lazy">
                        <br><br>
                        <h3>CÓMO LLEGAR </h3>
                        <p>
                            Para llegar a las Islas Palomino solo basta zarpar desde la Punta en
                            el Callao y en 45 minutos aproximadamente ya estás junto a los lobos
                            de mar, el paseo allí por lo general es de 4 horas.<br>
                            Paseo que se recomienda hacer en el verano de Lima, ya que la
                            temperatura del mar no está tan baja para muchos turistas, aunque
                            por lo general los operadores de tours surten de trajes
                            especializados para nadar sin problema.
                        </p>
                        <div id="puntanegra" style="padding-top:70px">
                            <h3>10. PUNTA NEGRA</h3>
                        </div>
                        <p>
                            Ubicada al sur de Lima, Punta Negra es un balneario que cuenta con una
                            serie de playas hermosas. Un destino espectacular si te interesa el
                            surf, ya que sus olas son de las mejores del distrito.<br><br>
                            Si estás buscando un retiro en la naturaleza, la playa Punta Negra posee rocas inmensas y
                            oscuras que ofrecen un paisaje particularmente hermoso al atardecer.
                            Ubicada exactamente entre San Bartolo y Punta Hermosa, este distrito es
                            conocido principalmente por Punta Rocas donde es usual la práctica y
                            campeonatos nacionales e internacionales de surf y bodyboard.<br><br>
                            Aquí regularmente se celebran certámenes de estos deportes y cuenta con
                            excelente infraestructura hotelera y comercial especializada en estas
                            prácticas.
                            Las Pocitas también son famosas por ser adecuada para los niños. Se
                            trata de formaciones hechas por piedras que simulan pequeñas piscinas.
                        </p>
                        <img src="../img/blog/punta-negra.webp" id="img-blog" alt="Punta Negra - Playa -lima"
                            loading="lazy">
                        <br> <br>
                        <h3>¿Cómo llegar?</h3>
                        <p>
                            Desde Lima viaja por la antigua Panamericana Sur hasta el kilómetro
                            47. Existe servicio de transporte público con ruta al sur de Lima,
                            la duración del trayecto es de 1 hora o ahorra tiempo en un vehículo
                            particular, cuyo viaje es aproximadamente de 45 minutos.
                        </p>
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
                            <div class="fb-comments" data-href="https://andeanexclusive.com/blog/lima-ciudad-de-reyes"
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
                                    <p class="card-text">El Inti Raymi es una de las fechas más importantes para la
                                        cultura
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
                                    <p class="card-text">Las mas misteriosas y enormes figuras talladas en el desierto
                                        de
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
                                    <p class="card-text">Al ser pateísta la religion Inca, esta tenia un día especial
                                        para
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
