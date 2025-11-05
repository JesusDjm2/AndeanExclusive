<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>The mistery of Nazca Lines - Nazca - Perú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/lineas-de-nasca-colibri.jpg">
    <meta name="keywords"
        content="Lineas de Nazca, Ica - Perú, tour en Nazca, tour de lujo en Perú nazca lines, nazca tour, exclusivve nazca tour, tours for nazca" />
    <meta name="description" content="Discover the Nazca lines, recognized by the mystery behind them." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/nazca-lines-peru">
    <meta property="og:title" content="The mistery of Nazca Lines - Nazca - Perú">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/lineas-de-nasca-colibri.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/nazca-lines-peru">
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
                                    @include('layouts.menu')
                                    <li id="display">
                                        <a href="{{ route('lineas-de-nazca') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('lineas-de-nazca') }}">
                                            <button type="button" class="botondjm">Español </button>
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
                <div class="col-lg-6 col-sm-10 text-center"><a href="{{ route('index') }}">Index</a> /
                    <a href="{{ route('blog-en') }}">Blog</a> /
                    <a>Nazca lines</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#comollegar">How to get to the Nazca lines?</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#datosUtiles">Useful data</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#colibri">Colibri</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#mono">Monkey</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#frigaAves">Giant bird</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#arania">Spider</a></li>
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
                    <h1 class="text-center">THE MISTERY OF NAZCA LINES</h1>
                    <div id="separadordjm2"></div>
                    <p class="text-center">Sugessted by: Andean Exclusive Tours</p>
                    <p class="text-center"><i class="fa fa-calendar"></i> 10/03/2022</p>
                    <img src="{{ asset('img/blog/lineas-de-nazca.jpg') }}" width="100%"
                        alt="Misterio de lineas de Nazca" loading="lazy"><br><br>
                    <h2 id="comollegar" style="padding-top:70px">¿
                        WHERE IS IT LOCATED AND HOW TO GET THERE?</h2>
                    <p>
                        The Nasca lines and geoglyphs are located in the Ica region, specifically in the valleys of
                        Palpa and Nasca, 450 kilometers south of Lima and near the Pacific Ocean. Located at the edge of
                        the Western Andes, they are 600 meters above sea level. To arrive by land you can travel by tour
                        buses that depart from the cities of Lima, Arequipa and Cusco. <br><br>
                        There are no scheduled commercial flights to Ica. Without a doubt, to enjoy and observe the
                        Nasca Lines, it is preferable to see them from the air, a space from where the perspective of
                        the figures is unique and unrepeatable. To do this, the visitor can make a trip in a plane.
                    </p>
                    <div id="datosUtiles" style="padding-top:70px">
                        <h2>USEFUL DATA:</h2>
                    </div>
                    <ul>
                        <li>It is necessary to carry original identity documents (DNI or Passport). Foreign citizens who
                            do not carry their original passport may present a copy of their passport and the original
                            of a document issued by their country of origin.</li>
                        <li>Accommodation can be found from $ 10 to $ 50 per night in the closest hostels to the town of
                            Nasca.</li>
                        <li>Admission is with prior presentation of ticket or ticket. For the overflight it is
                            recommended to make a reservation in advance, mainly during high seasons (June-August).</li>
                        <li>The aircraft that fly over this attraction have a qualified guide who will comment on the
                            figures to be observed with the most important information of each of them.</li>
                    </ul>
                    <div id="colibri" style="padding-top:70px">
                        <h2>Colibri</h2>
                    </div>
                    <h3>What is it?</h3>
                    <p>
                        It is the most remembered of the figures for its harmonic dimensions, the distance between its
                        two wings is approximately 66 meters.
                    </p>
                    <h3>History and Discovery</h3>
                    <p>
                        Considered through the years as one of the most representative figures of this tourist
                        attraction, its discovery and subsequent study would suppose religious activities and cults that
                        took place among the settlers of the ancient Nasca.
                    </p>
                    <h3>Theories and mysteries</h3>
                    <p>
                        As the historian Maria Rostworowski mentions, the figures were made to be seen from the sky.
                        However, the function of this figure was not to understand the stars, but to pay tribute to a
                        flying God respected and feared by the people. In this way, it would serve as an offering so
                        that this deity does not punish them with absences of rain, which in a dry land like Nasca would
                        produce devastating consequences.
                    </p>
                    <img src="{{ asset('img/blog/lineas-de-nasca-colibri.jpg') }}" width="100%" alt="Colibri"
                        loading="lazy">
                    <i>Picture: 'Colibri'</i><br> <br>

                    <div id="mono" style="padding-top:70px">
                        <h2>Monkey:</h2>
                    </div>
                    <h3>What is it?</h3>
                    <p>
                        The curiosity of this figure is that it shows the monkey with nine fingers and a tail in the
                        form of a spiral. It is believed that he is representing the Big Dipper. It is also considered
                        one of the most transcendental, with an approximate size of 135 meters.
                    </p>
                    <h3>History and Discovery</h3>
                    <p>
                        The discovery of these strokes by the German researcher Maria Reiche was the starting point
                        necessary to feed the theories that had been developing on a possible astronomical origin, where
                        the ancient settlers of Nasca used this gigantic geoglyph as a starting point to study the sky
                        and prepare for the events that could arrive.
                    </p>
                    <h3>Theories and mysteries</h3>
                    <p>
                        As mentioned by the researcher, the group of space constellations had a direct relationship with
                        the formation of the tail and hands of the monkey geoglyph. The appearance in the sky of these
                        stars would be directly related to the approach of rainy seasons, extremely important for a land
                        such as Nasca has.
                    </p>
                    <img src="{{ asset('img/blog/linea-nazca-mono.jpg') }}" width="100%" alt="Colibri"
                        loading="lazy">
                    <i>Picture: 'Monkey</i><br> <br>

                    <div id="frigaAves" style="padding-top:70px">
                        <h2>BIRD FRIGATE:</h2>
                    </div>
                    <h3>What is it?</h3>
                    <p>
                        With an approximate length of 160 meters, the most characteristic of this figure is in its own
                        head, which is formed by segments of circles that have a radius that varies between 10 and 20
                        centimeters. In profile, two circles of equal size could represent your eyes.
                    </p>
                    <h3>History and Discovery</h3>
                    <p>
                        As mentioned by María Reiche at the time of making the discovery, the immensity of this figure
                        is that if you stand at the end of one wing you can not see the other side. Approximately, it is
                        the equivalent to the measure of 6 football fields.
                    </p>
                    <h3>Theories and mysteries</h3>
                    <p>
                        The realization of this geoglyph would be within astronomy, because its alignment would be
                        responding to a configuration of the night sky. It can be seen that it is not aligned with
                        sunsets or the moon. However, if it is aligned with Fomalhaut, a very bright star that is in the
                        Pisces constellation.
                    </p>
                    <img src="{{ asset('img/blog/nazca-lines-giant-bird.jpg') }}" width="100%"
                        alt="Ave gigante Friga" loading="lazy">
                    <i> Picture: BIRD FRIGATE </i> <br> <br>

                    <div id="arania" style="padding-top:70px">
                        <h2>Spider:</h2>
                    </div>
                    <h3>What is it?</h3>
                    <p>
                        Being part of a huge network of lines located on the edge of a trapezoid on the earth's surface,
                        it is another of the most outstanding figures thanks to the skill to make your stroke. It
                        measures approximately 46 meters long.
                    </p>
                    <h3>History and Discovery</h3>
                    <p>
                        The skill with which this figure was constructed would not only represent an impressive act of
                        art, but since its discovery was related to the constellation of Orion, which is known worldwide
                        for being one of the brightest in outer space.
                    </p>
                    <h3>Theories and mysteries</h3>
                    <p>
                        This geoglyph has both astronomical and religious aspects. Both are related to the realization
                        of omens about the rainy season. In this way, when being in a very arid place, the settlers and
                        students used this figure to avoid times of droughts.
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/nazca-lines-peru"
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
                        <a href="{{ route('inti-raymi') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/inti-raymi-andean-exclusive.webp') }}"
                                alt="Festival of the sun 'Intiraymi'">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">The festival of the sun</h5>
                            <p class="card-text">The Inti Raymi is one of the most important dates for the Inca
                                culture, and in Cusco it is celebrated in a big way</p>
                            <a href="{{ route('inti-raymi') }}" id="botonnuevo">Read article</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('picnic-en') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Picnic-lunch-peru.jpg') }}"
                                alt="Andean picnic lunch">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Andean Picnic</h5>
                            <p class="card-text">Enjoy a country lunch, with an incredible view and typical food of Cusco</p>
                            <a href="{{ route('picnic-en') }}" id="botonnuevo">Read article</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('pachamama-ceremony') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}"
                                alt="Day of pacha mama">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Mother earth day</h5>
                            <p class="card-text">Being pantheistic, the Inca religion had a special day for mother
                                earth.</p>
                            <a href="{{ route('pachamama-ceremony') }}" id="botonnuevo">Read article</a>
                        </div>
                    </div>
                </div>
            </div><br><br>
        </div>
    </section>
    @include('layouts.foot-castellano')

</body>

</html>
