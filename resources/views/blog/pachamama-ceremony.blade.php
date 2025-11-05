<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Mother earth day | August 1 | Pachamama Day</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:title" content="Mother earth day | August 1 | Pachamama Day">
    <meta name="twitter:description"
        content="The celebration for the day of Pachamama is an event that takes place in Cusco, it is a celebration.">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/pachamama-ceremony-andean-exclusive.jpg">
    <meta name="keywords"
        content="Pachamama, Cusco customs, travel to Peru, traditions, pachamama ceremony, ceremony for pacha mama, pacha mama exclusive tour" />
    <meta name="description"
        content="The celebration for the day of Pachamama is an event that takes place in Cusco, it is a celebration" />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/pachamama-ceremony">
    <meta property="og:title" content="Mother earth day | August 1 | Pachamama Day">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/pachamama-ceremony-andean-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/pachamama-ceremony">
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
                                        <a href="{{ route('dia-de-la-pachamama') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('dia-de-la-pachamama') }}">
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
                    <a>Pacha Mama day</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#eventoCultural">A cultural and artistic
                                    event</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#queSeCelebra">What is celebrated on
                                    Pachamama day?</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#como">How is Pachamama Day celebrated?</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#mitologia">Inca mitology</a></li>
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
                    <div class="text-center">
                        <h1 id="titulo1">MOTHER EARTH DAY: PACHAMAMA RAYMI </h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center">Written by: David Miranda</p>
                        <i class="fa fa-calendar"></i> 01/08/2021
                    </div><br>
                    <div class="text-center">
                        <img src="../img/blog/Pachamama-andean-exclusive.jpg" id="img-blog"
                            alt="celebración por el dia de la tierrra - Cusco">
                    </div>
                    <div id="eventoCultural" style="padding-top:70px">
                        <h2>A cultural and artistic event</h2>
                    </div>
                    <p>
                        The first of August is celebrated on Earth Day or in Quechua: "Pachamama Raymi", in the high
                        Andean communities of Ecuador, Peru, Bolivia and Argentina. The Andes region is characterized by
                        its deep cultural and folk legacy from the Inca and pre-Inca cultures that is expressed in its
                        festivals, customs and traditions.<br><br>
                        It is a tradition that continues from ancient Inca times. Many people have a close bond with our
                        Mother Earth (Pachamama in Quechua), therefore every August 1 they perform a Pachamama Payment
                        Ceremony that represents the earth in the broadest sense of the word, that is, the entire nature
                        that provides us with the place and resources we need to live.<br><br>
                        The Incas had the greatest respect for him, he was one of their deities as was the Inti or Sun
                        God. Before any festivity they performed, they used to make him a previous thanksgiving
                        ceremony, it was the time for the earth to eat, drink and smoke. It was time to give her a lot
                        of what she has given us.
                    </p>
                    <br>
                    <div id="queSeCelebra" style="padding-top:70px">
                        <h2>What is celebrated on Pachamama day?</h2>
                    </div>
                    <p>
                        It is important to know that the majority of high Andean communities have had and still have a
                        deep dependence and connection with agriculture, being the month of August the month in which
                        furrows are normally made in the land, preparing it to receive the seeds and therefore the new
                        ones. crops. On the day of the Pachamama, the Andean man tries to return or return to the earth
                        everything he has received from her, showing his gratitude and asking him that the coming
                        harvests are also abundant and prosperous.<br><br>
                        Among the ingredients that we need to give thanks are: the coca leaf, the fetus of some animal
                        (preferably an Andean animal), some coins, lliqlla (typical blanket with Inca motifs), alcoholic
                        beverages (mostly wine or beer), cigarettes and food. Rural men, housewives, workers, farmers,
                        children, authorities, neighborhood associations and other organizations participate in the
                        festivities for the day of Pachamama.
                    </p>
                    <div class="text-center">
                        <img src="{{ asset('img/blog/pachamama-pago-a-la-tierra.jpg') }}" id="img-blog"
                            alt="Pago a la tierra - Pachamama" loading="lazy">
                        <i>Picture: Offerings to Pacha Mama</i>
                    </div> <br>
                    <div id="como" style="padding-top:70px">
                        <h2>How is Pachamama Day celebrated?</h2>
                    </div>
                    <p>As part of the Pachamama Day celebrations, the men and women of the high Andean communities
                        gather on August 1 in a ceremony that includes, among other things:</p>
                    <ul>
                        <li>A hole or hole dug in the ground, which is where all the offerings and gifts for the Pacha
                            Mama will be placed. This hole has a depth of 50 centimeters and a diameter of approximately
                            1 meter, depending on the number of gifts and the size of the ceremony.</li>
                        <li>A wool blanket or ‘Lliclla’ which is the garment with which the gifts will be wrapped,
                            before covering them with earth.</li>
                        <li>Animal fetuses, generally using lamb, alpaca or llama fetuses. They symbolize fertility.
                        </li>
                        <li>Beer, food, coca leaf, cigars, confetti, sweets, cookies.</li>
                    </ul>
                    <p>
                        During the procession some carguyoqs give gifts to the assistants, objects such as postcards,
                        souvenirs, etc; they are distributed without distinction. The procession ends at approximately
                        12 noon after all the saints return to the Cathedral where they will remain until next Thursday
                        when the faithful, the carguyoq, the mayor and others return their saints to their place of
                        origin, the temple. from which they were taken.<br><br>
                        The idea is ‘make Pacha Mama eat, drink and smoke’. At the end of the ceremony, wood or firewood
                        is placed in the hole, all the above is wrapped with the lliclla and finally everything is
                        consumed by the fire, to be later covered with earth. Throughout the ceremony people drink beer,
                        consume various typical dishes and celebrate with dance and music.
                    </p>
                    <div class="text-center">
                        <img src="{{ asset('img/blog/pachamama-ceremony-andean-exclusive.jpg') }}" id="img-blog"
                            alt="Altomisayoc in the middle of the ceremony of payment to the land" loading="lazy">
                        <i>Altomisayoc in the middle of the ceremony of payment to the land</i>
                    </div> <br>
                    <div id="mitologia" style="padding-top:70px">
                        <h2>Inca mythology</h2>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/pachamama-trilogia-inca.jpg" id="img-blog" alt="Trilogia andina"
                            loading="lazy">
                        <i>Andean cosmology</i>
                    </div><br>

                    <p>
                        The Incas had a ‘pantheistic’ religion, that is, they believed that the creator God and nature
                        (mountains, valleys, rivers, lightning, thunder, etc.) were actually a single entity or concept.
                        The Incas did not know the writing so that most of the traditions and myths have been
                        transmitted orally from generation to generation. According to the Inca worldview, space or time
                        was divided into three planes:
                    </p>
                    <ul>
                        <li><strong>Hanan Pacha:</strong> This word means ‘the world from above’. It is represented by a
                            condor. It was
                            the heavenly world, reserved for those people who had been righteous during their lives and
                            which was accessed through a bridge made with hair. In this higher world inhabit the most
                            important gods such as Wiracocha, as we will expand later.</li>
                        <li><strong>Kay Pacha:</strong> This word means ‘the present world, the here and now’. It is
                            represented
                            by a puma. It is the world where we humans, animals and some Gods like Pachamama, Mama
                            Sara and Pariacaca live.</li>
                        <li><strong>Uku Pacha:</strong> This word means ‘the world below, the world of the dead’. It is
                            represented by a snake. In this world, according to Andean mythology, unborn children,
                            the dead and everything that was below the surface of the earth or the sea inhabit. They
                            believed that caves, fountains, and other openings on Earth's surface were actually the
                            portals or connecting lines between the Uku Pacha and the Kay Pacha. The most important
                            God in this world is called ‘Supay’ although the Goddess Mama Cocha and the God
                            Pachacamac also live there.</li>
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/pachamama-ceremony"
                            data-width="100%" data-numposts="5"></div>
                        <!---------Fin redes sociales------------------------>
                    </div>
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
