<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>The Sun Festival "Inti Raymi"</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/Inti-Raymi-vista-aerea.jpg">
    <meta name="keywords" content="Inti Raymi Cusco, the sun festival" />
    <meta name="description"
        content="Inti Raymi is a Quechua word that means ‘Fiesta del Sol’. All the winter solstices (between June 20 and 23) the Inca performed a ritual in honor of the sun, the highest divinity in the Tahuantinsuyo." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/inti-raymi">
    <meta property="og:title" content="The Sun Festival 'Inti Raymi'">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/Inti-Raymi-vista-aerea.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel ="canonical" href = "https://www.andeanexclusive.com/blog/inti-raymi">
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
                                <a href="{{ route('index') }}" rel="home" class="logo-text-link">
                                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo">
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu')
                                    <li id="display">
                                        <a href="{{ route('inti-raymi-castellano') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('inti-raymi-castellano') }}">
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
                            <li><i class="fa fa-caret-right"></i> <a href="#sunfestival">The Sun Festival</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#when">When and Where is celebrated?</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#funfacts">Fun Facts</a>
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
                        <p class="text-center">Written by: David Miranda</p>
                        <i class="fa fa-calendar"></i> 13/04/2021
                    </center>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/inti-raymi-andean-exclusive.jpg"
                            alt="Inti Raymi">
                    </div>
                    <div id="separador"></div>
                   <div id="sunfestival" style="padding-top:70px"> <h5>The Sun Festival</h5>
                    <p> Inti Raymi is a Quechua word that means ‘Fiesta del Sol’. Every winter
                        solstice (between June 20 and 23) the Inca performed a ritual in honor
                        of the sun, the highest divinity in the Tahuantinsuyo. Pachacutec
                        started this religious-cultural tradition in the XV century. According
                        to the Andean worldview, at that time the sun began its cycle again.
                        <br>
                        Formerly the Inti Raymi lasted about 15 days, sacrifices were made and
                        dances were presented to worship the "Sun God". The last Inti Raymi that
                        was made with the presence of the Inca Emperor was in the year of 1535;
                        a year before ¨La Conquista Española¨ the year of 1536. <br>
                        Currently the Inti Raymi is a theatrical performance, thousands of Cusco
                        and people from all over the world come together to this event that is
                        one of the most important cultural and traditional manifestations in
                        this part of the world. This Inca celebration makes each of us feel the
                        Inca blood run through their veins and revalue our past. </p>
                    <br>
                   </div>
                    <div id="when" style="padding-top:70px"><h5> When is it celebrated? </h5>
                    <p> On June 24 of each year, Cusco celebrates the Inti Raymi. This date is
                        due to the fact that it coincides with Peasant's Day. It is the most
                        colorful garment that the jubilee festivals of Cusco have to show the
                        world. </p>
                    <br>
                    <h5> Where is it celebrated? </h5>
                    <p> The staging takes place in 3 specific sites in Cusco with great
                        historical significance: </p>
                    <br>
                    <h6> 1st Qoricancha </h6>
                    <p> The Temple of the Sun or Qoricancha, from the first hours of the day,
                        begins the Festival of the Sun. This representation is performed in the
                        so-called golden garden. It begins with the appearance of the Inca. The
                        first rituals offer coca leaves, llamas, alpacas and more.</p>
                    <div class="text-center">
                        <img src="../img/blog/intiraymi-cusco-festivity.jpg" alt="Inti Raymi">
                    </div><br><br>
                    <h6> 2nd Plaza de Armas </h6>
                    <p> The historical center of Cusco is the nerve center of the celebrations
                        of the Sun Festival. The Plaza de Armas is cordoned off. The Inca is
                        carried on litters by eight servants and performs the so-called Coca
                        Ceremony. </p>
                    <div class="text-center">
                        <img src="../img/blog/intiraymi-24-de-junio-tours-fiesta-del-sol.jpg"
                            alt="The sun festival">
                    </div><br><br>
                    <h6> 3 ° Sacsayhuaman </h6>
                    <p> - The Chuquipampa esplanade in the Sacsayhuaman fortress is the main
                        stage of the Inti Raymi representation. This place was very important
                        during the incan period. </p>
                    <div class="text-center">
                        <img src="../img/blog/Inti-Raymi-vista-aerea.jpg"
                            alt="The sun festival">
                    </div><br><br>
                    </div>

                    <div id="funfacts" style="padding-top:70px"><h5> Fun Facts </h5>
                    <h6> 1.- Inti Raymi is one of the largest festivals in Peru </h6>
                    <p> The numbers are bombastic. It is estimated that the anniversary was
                        created in 1430 by the Inca Pachacútec. In those times, it lasted for
                        two weeks where there were dances and offerings to the sun-god Inti
                        (hence the name Inti Raymi, which in Quechua means "Feast of the Sun").
                        In 2019, about 200 thousand people went to Cusco for the festival, which
                        had 700 actors on stage. </p>
                    <h6> 2.- The Sun Festival marked the beginning of a new year in the Inca
                        calendar </h6>
                    <p> For ancient peoples, the Inti Raymi represented a new beginning by
                        closing one harvest cycle and opening another. Today, the festival is a
                        way to keep the ancient culture alive and revere the greatness of the
                        Cusco traditions. </p>
                    <div class="text-center">
                        <img src="../img/blog/INTI-RAYMI-FESTIVITY-exclusive.jpg"
                            alt="Intiraymi - Inti Raymi">
                    </div><br><br>
                    <h6> 3.- Three tourist spots in Cusco are part of the route of the
                        celebration </h6>
                    <p> The show begins at the Qorikancha Complex, also known as the Temple of
                        the Sun. This monument was erected in the 15th century as a temple of
                        worship to the god Inti and its structure was already adorned with gold
                        plates. From there, the colorful procession passes through the Plaza de
                        Armas, heart of the historic center of Cusco and Cultural Heritage of
                        Humanity since 1983. In the place there is also the Cathedral of Cusco
                        and the Church of the Company of Jesus. The apex of the party takes
                        place on the Esplanada de Sacsayhuamán, where the public watches the
                        show from the stands. In this archaeological complex, located 2
                        kilometers from the Plaza de Armas, it is possible to admire the
                        magnificent architectural legacy left by the Incas, who built walls of
                        almost 300 meters using only large stones perfectly fitted together.
                        <br> <strong> Qorikancha complex: Santo Domingo street, s / n, Cusco
                            historic center Plaza de Armas or Plaza Mayor de Cusco: between the
                            Commerce Portal, the Belén Portal and the Bread Portal Explanada de
                            Sacsayhuamán: near Don Bosco street.</strong> </p>
                    <h6> 4.- Inti Raymi today is the Cultural Heritage of the country </h6>
                    <p> The party was not always so prestigious. In 1572, the viceroy Francisco
                        Álvarez de Toledo characterized her as a pagan and prohibited her.
                        Despite this impediment, the event continued to take place clandestinely
                        until 1944, when its route was reformulated and the date entered the
                        city's official calendar again. The 2019 edition marks the 75th
                        anniversary of the recreation of the festival, which became the Cultural
                        Heritage of the Nation in 2001. </p>
                    </div>
                    <div id="separador"></div>

                    <!-----Booking---------->
                    <div class="form text-center">
                        <!---------redes sociales------------------------>
                        <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=6232ae6fcdbe0200193e185e&product=inline-share-buttons"
                                                                        async="async"></script>
                        <div class="sharethis-inline-share-buttons"></div>
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v13.0&appId=588015035734857&autoLogAppEvents=1"
                                                                        nonce="RA0e6tQN"></script>
                        <div class="fb-comments"
                            data-href="https://andeanexclusive.com/blog/inti-raymi"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3>Similar Topics</h3>
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
    @include('layouts.foot')

</body>

</html>
