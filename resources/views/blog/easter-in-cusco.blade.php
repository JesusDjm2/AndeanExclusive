<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Holy Week in Cusco | Andean Exclusive Tours</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords"
        content="easter in cusco, lord of tremors, señor de los temblores, Cusco customs, Cusco holidays, holidays in Cusco">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/thumb/semana-santa-cusco.jpg">
    <meta name="keywords" content="Ausangate mountain, Ausangate snowy, Apu Perú." />
    <meta name="description"
        content="Holy week in Christianity and liturgical year, is the previous one towards Easter, beginning on Palm Sunday and concluding on Resurrection Sunday." />
    <meta property="og:title" content="Easter in Cusco | Andean Exclusive Tours">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/thumb/semana-santa-cusco.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/easter-in-cusco">
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
                                        <a href="{{ route('semana-santa') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('semana-santa') }}">
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
                    <a>Easter week in Cusco</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#description">Description of activities</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#history">History of the Lord of the
                                    Tremors</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#holyThursday">Holy Thursday</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#holyFriday">Holy friday</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#gastronomy">The tradition of the 12 dishes</a></li>
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
                    <center>
                        <h1 id="titulo1">Holy Week in Cusco</h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center">Written by: "David Miranda"</p>
                        <p class="text-center"><i class="fa fa-calendar"></i> 10/04/2022</p>
                    </center>
                    <div class="text-center">
                        <img src="../img/blog/semana-santa-cusco_2022.webp" id="img-blog" width="100%"
                            height="auto">
                        <p>Photo: Señor de los templores</p>
                    </div>

                    <p> Holy week in Christianity and the liturgical year, is the previous one
                        towards Easter, beginning on Palm Sunday and concluding on Resurrection
                        Sunday.<br>
                        The imperial city, Cusco, is no stranger to this festivity since it is
                        one of the most important traditions and faith, characteristics that the
                        people of the city of Cusco carry in their minds and persons.
                        Unlike other commemorations, Holy Week in Cusco has Holy Monday as its
                        central day because on that date the Sworn Patron of Cusco, the “Lord of
                        the Tremors”, goes out in procession. The Archbishop of the city offers
                        the Communion Mass in the Cathedral, starting at 8 am. Masses are
                        celebrated every hour until noon.
                        <br>
                        Holy Week in Cusco is celebrated beteen the second fortnight of March to
                        the first week of April, the date is movable.
                    </p>
                    <div id="description" style="padding-top:70px">
                        <h2>Description of activities in Holy Week 2022 </h2>
                    </div>
                    <h3>Palm Sunday</h3>
                    <p>
                        On Sunday, April 10, 2022, the festival begins with "Palm Sunday", where
                        the entry of Jesus Christ into Jerusalem is commemorated, the families
                        of Cusco attend the different parishes of the city carrying palm leaves
                        and crosses woven from the same plant, those that are blessed in the
                        Eucharistic celebrations of the different temples of the city; to later
                        take them to the houses and place them behind the doors, as a symbol of
                        protection for the home.
                    </p>
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <img src="https://andeanexclusive.com/img/blog/domingo-de-ramos-1.webp" id="img-blog"
                                alt="palm sunday" loading="lazy">
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src=" https://andeanexclusive.com/img/blog/domingo-de-ramos-bendicion.webp"
                                id="img-blog" alt="Palm sunday cusco" loading="lazy">
                        </div>
                        <br>
                        <br>
                    </div>
                    <br>

                    <h3>Holy Monday:</h3>
                    <p>
                        Holy week in<a href="https://andeanexclusive.com/cusco-6-days-5-nights" class="a-tours"
                            target="_black"> Cusco </a> has as its
                        central day Holy Monday (April 11, 2022), since on this date the sworn
                        Patron of Cusco (Lord of the Tremors) goes out in procession. The
                        Catholic-Andean syncretism of the Cusco population is revealed. The
                        procession of the Taytacha (denomination in the Quechua language that
                        means "Daddy") gathers around 80 thousand devotees waiting for the
                        blessing of the Sacred Image.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/lunes-santo-cusco.webp" id="img-blog" width="100%" height="auto">
                        <p>Photo: The Lord of the Tremors </p>
                    </div>
                    <div id="history" style="padding-top:70px">
                        <h2> History of the Lord of the Tremors</h2>
                    </div>
                    <p>
                        The Archaeological Capital of America and former main head of the
                        once-powerful Tahuantinsuyo Empire celebrates Holy Week by worshiping
                        the image of the Lord of the Tremors or Taytacha Temblores, in Quechua.
                        According to some sources, the history of this Christ with gaunt
                        features and an overwhelming appearance dates back to when Emperor
                        Carlos V sent the effigy to Cusco, made especially for the Indians,
                        copying their rough features. The Spaniards sought to consolidate the
                        Conquest made by the sword and impose their worship. <br>
                        But it was on May 31, 1650, when the flame of faith of the people of
                        Cusco for the Lord of the Tremors was further lit. That afternoon, an
                        earthquake measuring 8 on the Richter scale hit the city, demolishing
                        many houses and temples.<br> To placate the wrath of the Lord, the
                        ecclesiastical authority ordered the Immaculate Virgin and a Christ who
                        was not well known in the Cathedral of Cusco to go out in procession. At
                        around four in the afternoon, the two images began the procession
                        touring the perimeter of the Plaza de Armas. <br>People with fear,
                        terror,
                        and despair decided to follow the two images. It was then at that moment
                        that the disastrous earthquake was calmed thanks to the image of the
                        previously known Lord of the Good Death. Since then he was nominated
                        <strong>'Lord of the Tremors'.</strong> Since that time, the litters of
                        this mestizo Christ come out in procession every Holy Monday, followed
                        by a multitude of parishioners who accompany him with humble fervor.
                        <br>In
                        the windows of the houses, where the effigy passes, refined pieces of
                        velvety tapestries with gold fringes, bright fabrics, and carpets are
                        placed, which families reserve especially for this occasion. Meanwhile,
                        the Camaretos or small mortars, firecrackers, and rockets shake the
                        atmosphere with their noise, making the chants and prayers of the crowd
                        almost inaudible.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/historia-señor-de-los-temblores.webp" id="img-blog" width="100%"
                            height="auto">
                        <p>Photo: The lord of the tremors" </p>
                    </div>
                    <h3> Route</h3>
                    <p>
                        The Procession of the Lord of the Tremors was instituted on March 31,
                        1650, to commemorate the earthquake, but in 1971, the date was moved to
                        Holy Monday. The procession of the Jurado Patron of Cusco begins at 2:00 p.m. touring
                        the perimeter of the Plaza de Armas until heading to Plateros Street and
                        arriving at the Temple of Santa Teresa, where the shroud of the sacred
                        image is changed for a new one. Once this process is completed, the
                        Taytacha goes to the Plaza San Francisco, where he performs the first
                        blessing to the people of Cusco.<br><br>
                        After this, the sacred image continues its journey to the Minor Basilica of La Merced, where the
                        shroud is changed again. At night, the patron Jurado goes to the Cathedral of
                        Cusco, where he performs his last blessing to the entire population of
                        Cusco, filling everyone with an emotional and telluric moment full of
                        faith, hope, and forgiveness.
                    </p>
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <img src="https://andeanexclusive.com/img/blog/recorrido-calle-señor-de-los-temblores.webp"
                                id="img-blog" alt="lord of tremors tour" loading="lazy">
                        </div>

                        <div class="col-lg-6 text-center">
                            <img src=" https://andeanexclusive.com/img/blog/recorrido-plaza-señor-de-los-temblores.webp"
                                id="img-blog" alt="lord of tremors tour" loading="lazy">
                        </div>
                    </div><br> <br>

                    <h3> Chayñas of Cusco</h3>
                    <p>The singers of Taytacha (Chayñas del Cusco) are women singers who sing
                        during and after the procession of the Lord of the Tremors, these women
                        have a fervor and magic in their voices, typical of the Andes and Cusco
                        singing with their acute and delicate singing voices in Quechua.
                    </p>
                    <div class="row relacionado">
                        <div class="col-lg-4">
                            <a href="https://andeanexclusive.com/blog/inti-raymi-castellano" target="_blanc">
                                <img src="https://andeanexclusive.com/img/blog/thumb/inti-raymi-andean-exclusive.jpg"
                                    alt="Inti raymi Cusco" loading="lazy">
                            </a>
                        </div>
                        <div class="col-lg-8">
                            <div class="similar-entre">
                                <a href="https://andeanexclusive.com/blog/inti-raymi-castellano" target="_blanc">
                                    <p class="p-similar"><strong>Inti Raymi The sun's
                                            festival</strong><br>
                                        Inti Raymi is a Quechua word that means ‘Fiesta del
                                        Sol’. Every winter solstice (between June 20 and 23) the
                                        Inca performed a ritual in honor of the sun, the highest
                                        divinity in the Tahuantinsuyo.
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3> Ñucchu</h3>
                    <p> A red flower of intense color, crimson flower, is a flower native to the
                        Quechua region that grows in height only on the days of Holy Week and
                        especially on Holy Monday. It is said that the resin of this flower is
                        what caused the dark color of the Patron of Cusco.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/ñucchu-procesion.webp" id="img-blog" width="100%" loading="lazy"
                            alt="Procesion nucchu">
                        <p>Photo: Red Flowers "Ñucchu" </p>
                    </div>
                    <div id="holyThursday" style="padding-top:70px">
                        <h2>Holy Thursday:</h2>
                    </div>
                    <p>
                        Holy Week continues with “Holy Thursday”. Thursday, April 14, 2022, is
                        the day when the last supper is remembered throughout the Christian
                        community.
                        In the Basilica of the Cathedral, the Archbishop washes the feet of
                        twelve elders, in memory of the washing of the feet of Jesus to his
                        apostles, while families gather to enjoy the "twelve dishes" remembering
                        the "Last Supper" (This activity may vary for Holy Thursday or Holy
                        Friday according to the preference of each family). Here you can see the
                        mixture of Andean and Western cuisine, while in the Plaza San Francisco
                        and the Central Market you can buy sweets such as crispy maicillos,
                        bizcochuelos, suspiros, empanadas, and other delicacies of traditional
                        Cusco pastries. At night, families walk the streets and squares of the
                        Historic Center on the route of the "Seven Temples" that remember the
                        falls of Jesus on his way to Calvary.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/jueves-santo-lavado-de-pies-cusco.webp" id="img-blog" width="100%"
                            alt="Jueves santo en cusco" loading="lazy">
                        <p>Photo: Traditional "Foot washing" </p>
                    </div>
                    <div id="holyFriday" style="padding-top:70px">
                        <h2> Holy Friday:</h2>
                    </div>
                    <h3> Way of the Cross:</h3>
                    <p> On Holy Friday, Friday, April 15, 2022, as in Ayacucho, the meeting of
                        the litters of Christ in the Holy Sepulcher and the Virgin of Sorrows
                        takes place. The way of the cross is carried out from Plaza San
                        Francisco to the Pope's Cross located in Sacsayhuamán, this event is
                        organized by the Convent of San Francisco. <br>

                        That day, unlike what happens in other places, is not one of abstinence
                        in Cusco. The custom is to taste twelve different typical dishes
                        (variable date for Holy Thursday or Holy Friday) that include various
                        soups and stews either based on dried fish, wheat, and olluco, to
                        delicious desserts such as apple, corn or corn candies.
                    </p>

                    <h3> Hampi Rantikuy (Sale of Medicinal Herbs).</h3>
                    <p> Medicinal herbs fair where the entire population of Cusco goes out to
                        buy herbs, cross-shaped thorns, amulets, etc. These are placed on the
                        doors of Cusco homes to protect themselves from evil.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/hampirantikuy-viernes-santo-cusco.webp" id="img-blog" width="100%"
                            alt="Hampirantikuy viernes santo" loading="lazy">
                        <p>Photo: Traditional Fair "Hampirantikuy". </p>
                    </div>
                    <div id="gastronomy" style="padding-top:70px">
                        <h2> Gastronomy and the 12 dishes.</h2>
                    </div>
                    <p> After fasting until noon, the Cusco families taste the 12 dishes, in
                        commemoration of the 12 apostles and the Last Supper. The most common
                        dishes are:
                    </p>
                    <div class="row">
                        <div class="col-lg-6">
                            <p> SALTY: </p>
                            <ul>
                                <li> Lawa (Sweet Corn Cream) </li>
                                <li> Lisas soup</li>
                                <li> Trout sweat</li>
                                <li> Salted cod</li>
                                <li> Fried fish</li>

                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <p> SWEETS: </p>
                            <ul>
                                <li> Fruit stew</li>
                                <li> Rice pudding</li>
                                <li> Porridge</li>
                                <li> Empanadas, maicillos</li>
                                <li> Cake bread</li>
                                <li> Chocolate</li>

                            </ul>
                        </div>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/doce-platos-salados-cusco.webp" id="img-blog" width="100%"
                            alt="tipical dishes easter week cusco" loading="lazy">
                        <i>Photo: 12 traditional dishes from Cusco. </i>
                    </div>
                    <h3>Procession of Our Lady of Sorrows and the Holy Sepulchre:</h3>
                    <p> At around 5 in the afternoon, the image of the Virgen Dolorosa and Holy
                        Sepulchre leave in procession from the Basilica Menor de la Merced,
                        remembering the death of Jesus Christ. This procession is accompanied by
                        thousands of faithful followed by their faith.
                    </p>

                    <h3>Easter Sunday</h3>
                    <p>
                        Holy Week comes to an end on Easter Sunday. Sunday, April 17, 2022, the
                        day comes when Jesus Christ rises from the dead, for this, the Cusco
                        families go to the parishes to participate in the homilies. <br>After the
                        procession and the celebration of the mass, through the main streets,
                        you can perceive the exquisite aroma of the delicacies that delight
                        locals and strangers, such as the tasty chicken broth, the empanadas,
                        the sweet white corn, the tamales, and the cakes. Thus, in a pagan way,
                        the Andean Holy Week closes in the thousand-year-old imperial city of
                        Cusco.
                    </p>

                    <div class="text-center">
                        <img src="../img/blog/postres-semana-santa-cusco.webp" id="img-blog"
                            alt="Semana Santa Cusco">
                        <p>Photo: Empanadas, "sighs", and maicillos. </p>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/platos-dulces-cusco.webp" id="img-blog"
                            alt="Postres semana santa cusco">
                        <p>Photo: Bread cake, donuts, biscuits. </p>
                    </div>
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/easter-in-cusco"
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
    @include('layouts.foot')

</body>

</html>
