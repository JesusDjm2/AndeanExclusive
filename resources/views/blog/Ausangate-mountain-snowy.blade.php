<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Apu Ausangate snowy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords"
        content="Ausangate mountain, apu Ausangate, Ausangate tour, Tour in Ausangate, Ausangate trek, Trek in Ausangate">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/Ausangate.jpg">
    <meta name="keywords" content="Ausangate mountain, Ausangate snowy, Apu Perú." />
    <meta name="description"
        content="The imposing Apu Ausangate is the fifth highest mountain in Peru, it is called the 'Creator of the
        Waters', for its enormous glacial mass." />
    <meta property="og:title" content="Apu Ausangate snowy">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/Ausangate.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/Ausangate-mountain-snowy">
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
                                        <a href="{{ route('nevado-ausangate') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('nevado-ausangate') }}">
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
                    <a>Snowy Ausangate</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#apu">Apu Ausangate</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#locacion">Ausangate location</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#altitud">Altitude - Latitude</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#comoLlegar">How to get to the snowy
                                Ausangate?</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#cosmovision">Andean cosmovision</a></li>
                        </ul>
                        <div class="text-center">
                            <button class="boton-info"><a href="#openModal">Ask for information</a></button>
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
                    <h1 class="text-center">The Apu Ausangate</h1>
                    <div id="separadordjm2"></div>
                    <p class="text-center">Suggested by: Andean Exclusive Tours</p>
                    <p class="text-center"><i class="fa fa-calendar"></i> 01/02/2022</p>
                    <img src="{{ asset('img/blog/Ausangate.jpg') }}" width="100%"
                        alt="The best of peruvian gastronomy" loading="lazy"><br><br>
                    <h2 id="apu" style="padding-top:70px">Apu Ausangate</h2>
                    <p>
                        Is the fifth highest mountain in Peru, is called the "Creator of the Waters", because its
                        glacial mass gives rise to the Sibinacocha lagoon, the second largest in Cusco, this in turn
                        forms a series of bofedales that preserve the Andean flora and fauna. However, the water
                        resource plays a fundamental role in the electrification of the region.<br><br>

                        60% of these waters go to the Vilcanota river, the most surprising thing is that these waters
                        serve to move the machines of the Machu Picchu Hydroelectric Power Plant in dry seasons.<br><br>

                        Another aspect that we must take into account is that in the Andean Cosmovision in the Cusco
                        region, this mountain is considered as the most influential and most important Apu due to the
                        spiritual energy it emanates, in addition to that for the people of the area, the Ausangate is
                        the origin of the water, therefore of the life and the fields of the place.
                    </p>
                    <div id="locacion" style="padding-top:70px">
                        <h2>Location of Ausangate:</h2>
                    </div>
                    <p>
                        Se encuentra en la cordillera de los Andes, ubicada a unos 100 kilómetros al sureste de la
                        ciudad de Cusco en una sección llamada Cordillera de Vilcanota, que corresponde a la región de
                        Cusco en Perú.
                    </p>
                    <img src="{{ asset('img/blog/snowy-Ausangate-Cusco-trek.webp') }}" width="100%"
                        alt="OSnowy Ausangate Trek Cusco" loading="lazy"> <br> <br>

                    <div id="altitud" style="padding-top:70px">
                        <h2>Altitude:</h2>
                    </div>
                    <p>
                        The main peak of this mountain is 6,384 meters above sea level, with lagoons
                        large with a luminous green/blue color.
                    </p>
                    <h2>Latitude:</h2>
                    <p>
                        The mountain is at a latitude of 13° 47' 18'' S
                    </p>
                    

                    <div id="comoLlegar" style="padding-top:70px">
                        <h2>How to get to Ausangate?:</h2>
                    </div>
                    <p>
                        <strong>On your own:</strong><br>
                        You can take the public bus (Cusco - Tinke), the estimated travel time is 3 hours to
                        get to Tinke and then you can take a mobile phone to Pacchanta, when you get to Pacchanta,
                        where you can walk and from there you can see the imposing Nevado Ausangate.<br><br>

                        <strong>Hiring a tour operator:</strong><br>
                        This option is the most recommended since the company will take care of your security and help
                        you with quality and guaranteed professionals, make sure it is a travel agency authorized for
                        adventure tourism. Book this tour here.
                    </p>
                    <img src="{{ asset('img/blog/caminata-al-venado-Ausangate-Cusco.webp') }}" width="100%"
                        alt="Laguna en el nevado Ausangate" loading="lazy">
                    <i> Photo: Lagoon on the walk to Ausangate</i> <br> <br>

                    <div id="cosmovision" style="padding-top:70px">
                        <h2>Andean cosmovision</h2>
                    </div>

                    <p>In the Inca mythology of this mountain and the nearby lakes (such as Sibinacocha) energy is born
                        masculine that fertilizes mother earth Pachamama; After a long flow, the waters
                        lost in the unknown lands of the Amazon to return, to fill the lakes and
                        glaciers every night turned into the river of stars or Willkamayu known in the west as
                        the Milky Way. The Chillca community, made up of llama and alpaca herders, is known
                        as the guardian of these beautiful places. <br><br>
                        Every year on the north side of Ausangate, the festival of Qoyllur Ritt'i (Quechua: "star of
                        snow») has been celebrated since time immemorial. Corpus Christi festival, during which
                        thousands of people make a pilgrimage to the Lord of Quyllur Ritt'i at the Sinakara temple
                        (painting made on a rock). This pilgrimage was included in the Representative List of Heritage
                        Intangible Culture of Humanity.
                    </p>
                    <p class="font-weight-bold">Recommendations:</p>
                    <ul>
                        <li>The main recommendation is that you do not go alone to do adventure tourism in this mountain
                            nevada, always hire a tour operator as they will take care of the details of
                            transportation, food, professional guide and above all your safety in each one. moment.</li>
                        <li>Bring soroche pills called Soroch Phills, for altitude sickness or otherwise
                            drink a lot of tea from coca leaves.</li>
                        <li>Comfortable and warm clothing, trekking pants, waterproof jacket, rain poncho
                            and comfortable shoes.</li>
                        <li>Apply sunscreen, at least every two hours.</li>
                        <li>Do not leave your rubbish in the place, take care of the environment.</li>
                    </ul>
                    <p>
                        An impressive adventure that you cannot stop living and much more if you are a person who loves
                        adrenaline, nature and the emotion of living to the limit.
                        Contact <strong>Andean Exclusive</strong>, always with the guarantee and
                        responsibility that we characterizes, we have a team of highly trained professionals that
                        guarantees that your adventure will be the best.
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/Ausangate-mountain-snowy"
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
                                alt="Card image cap">
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
                        <a href="{{ route('nazca-lines-peru') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/lineas-de-nasca-colibri.webp') }}"
                                alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Nazca lines</h5>
                            <p class="card-text">The most mysterious and huge figures carved in the desert of Ica,
                                is a constant question of what are they made for?</p>
                            <a href="{{ route('nazca-lines-peru') }}" id="botonnuevo">Read article</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('pachamama-ceremony') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}"
                                alt="Card image cap">
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
