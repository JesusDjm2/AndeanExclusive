<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Corpus Christi in Cusco</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:title" content="Corpus Christi in Cusco">
    <meta name="twitter:description"
        content="Places to visit in Cusco, streets of Cusco, visit the Cathedral of Cusco.">
    <meta name="keywords"
        content="Corpus Christi, Corpus christi in Cusco, Corpus Christi festival, ceremony of corpus christi, what date is corpus christi celebrated?, Cusco Corpus Christi 2022, facts you did not know about corpus christi, chiriuchu, Where to eat chiriuchu, Cusco's typical dish, saints of cusco, Procession of Saints in Cusco">
    <meta name="twitter:image" content="location.href='corpus-christi-cusco">
    <meta name="description"
        content="Get to know Corpus Christi in Cusco, a magnificent custom brought from Europe from the Middle Ages." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/corpus-christi-cusco">
    <meta property="og:title" content="Corpus Christi">
    <meta property="og:type" content="article">
    <meta property="og:image"
        content="https://andeanexclusive.com/img/blog/corpus-cristy-SAN-CRISTOBAL-ANDEAN-EXCLUSIVE.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/corpus-christi-cusco">

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
                                        <a href="{{ route('corpus-christi-castellano') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('corpus-christi-castellano') }}">
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
                    <a>Corpus Christi in Cusco</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#corpus">Corpus Christi in Cusco and History</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#beginning">Beginning of the Festival and the 15 Saints</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#descent">Descent of the Saints</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#chiriuchu">Chiriuchu: The traditional dish </a></li>


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
                        <h1 id="titulo1">Corpus Christi in Cusco</h1>
                        <p class="text-center">Written by: David Miranda</p>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-calendar"></i> 18/04/2021
                    </center>
                    <div id="corpus" style="padding-top:70px"><p>
                        <strong>The Corpus Christi </strong>(Body of Christ), is the largest
                        religious
                        celebration of the Cusqueño liturgical calendar, it is in itself a
                        gigantic confluence of traditions and customs of the various peoples
                        that make up the valley of Cusco and that are rarely observed in one
                        place, which is celebrated on a moveable date that is usually in May
                        and June, according to the Catholic calendar, is celebrated after 60
                        days after Easter; In Cusco, a Thursday is always celebrated, which is
                        the Central Day, but a day before, which is Wednesday, the images that
                        will go out in procession make their entrance to the city from various
                        temples in different districts.<br><br>
                        On a central day a TE-DEUM mass is held, in which all the priests of the
                        City are gathered; then begins the procession of a beautiful Silver
                        Carriage in which it holds the Custody, which is made of gold in which
                        is the Blessed Sacrament (body of Christ), along the Plaza Mayor, after
                        that the images will continue with the procession.<br>
                        There are 15 images of the saints who, with individual processions,
                        leave their usual temple or church to visit the Plaza Mayor of Cusco and
                        then enter the Basilica of the Cathedral, where there is a popular
                        belief that the Saints perform an annual assembly and the one who takes
                        notes of said meeting is Saint Jerome (who precisely holds a book in his
                        hands); in which they will remain for 8 days until "La Octava" arrives,
                        the day in which the saints are taken out and taken to their temple or
                        church of origin.<br><br>
                        It is the maximum expression of Cusco religiosity that combines joy,
                        color, dance, and music, and which has been declared in 2004 as a
                        Cultural Heritage of Peru.

                    </p>
                    <div class="text-center">
                        <img src="../img/blog/corpus-cristy-SAN-CRISTOBAL-ANDEAN-EXCLUSIVE.jpg" width="100%"
                            id="img-blog" alt=" San Cristobal">

                    </div>
                    <p style="font-style: italic">Picture: San Cristobal</p>


                    <h2>A story that is born from the incanato</h2>
                    <p>
                        During the time of Tahuantinsuyo, the Incas carried out massive
                        processions of the mummies of their deceased every year around the city
                        of Cusco. These were people who had a high status in life and who, after
                        they died, their bodies were very well cared for by the family nuclei or
                        ayllus. However, everything changed with the arrival of the Spanish;
                        They, seeing this practice, considered it pagan so they decided to
                        transform it by changing the mummified corpses for figures of virgins
                        and Catholic saints.<br><br>
                        According to chroniclers of the time, during the processions, the Incas
                        hid their mummies under the sculptures in order not to lose their sacred
                        customs. Over time, the ceremony took an important turn and created a
                        new tradition: the mummified bodies were replaced by religious effigies,
                        the family nuclei gave way to the new caretakers: the churches, and it
                        became known as the “Corpus Christi Celebration”, a name that continues
                        to the present day.
                    </p>
                    </div>
                    <div id="beginning" style="padding-top:70px"><h2>Beginning of the festival of Corpus (Body of Christ) and the 15 saints
                    </h2>
                    <p>
                        As we previously indicated, this festivity begins on Wednesday (one day
                        before the procession or central day) with the departure of each saint
                        from their temple of origin. <br>Each one with an individual procession
                        where the Mayordomos or the ¨Carguyoq¨, in some cases the mayor of the
                        various districts; the faithful who voluntarily decide to accompany
                        their saint and accompanied by a band of musicians or ¨Q´aperos¨. After
                        arriving at the Plaza Mayor, the images of the saints are taken to the
                        Cathedral and remain inside until the next day, which is the procession.
                        On Thursday with the prelude to the procession of the Blessed Sacrament
                        (Body of Christ), then all these people take their saint or virgin from
                        the Basilica of the Cathedral and the procession takes place. <br>The
                        Plaza
                        Mayor is filled with people. The order in which the saints come out is
                        as follows:
                    <ul>
                        <li>1. San Antonio of the parish of San Cristóbal.v</li>
                        <li>2. San Jerónimo of the parish of the district of San Jerónimo.</li>
                        <li>3. San Cristóbal of the parish of San Cristóbal.</li>
                        <li>4. San Sebastián of the parish of the district of San Sebastián.
                        </li>
                        <li>5. Santa Bárbara of the parish of the district of Poroy.</li>
                        <li>6. Santa Ana of the parish of Santa Ana.</li>
                        <li>7. Santiago Apóstol of the parish of Santiago.</li>
                        <li>8. San Blas of the parish of San Blas.</li>
                        <li>9. San Pedro of the parish of San Pedro.</li>
                        <li>10. San José of the parish of Bethlehem.</li>
                        <li>11. Virgin of the Nativity of the parish of Almudena.</li>
                        <li>12. Virgin of the Remedies of the church of Santa Catalina.</li>
                        <li>13.Purified Virgin of the parish of San Pedro.</li>
                        <li>14. Virgin of Bethlehem of the parish of Bethlehem.</li>
                        <li>15. Virgin of the Immaculate Conception also called "La Linda" in
                            the Basilica of the Cathedral.</li>
                    </ul>
                    </p>
                    <img src="../img/blog/Corpus-Christi-Cusco-ESCLUSIVE.jpg" id="img-blog"
                        alt="San Jose in Corpus Christi" width="100%" loading="lazy">
                    <i>Picture: San José</i><br><br>
                    <p>
                        The saints and virgins are dressed in beautiful outfits which have been
                        elaborated with <strong>gold embroidery</strong> on garments designed by
                        artisan's hands.
                        Attendees also wear traditional and colorful clothing. The "carguyocs",
                        who are people with high purchasing power, give the attendees objects
                        and souvenirs, while the mayordomos, who organize the event, carry with
                        them the miniature image of the saint or the virgin of their district,
                        also called "dowry”. Behind the butlers follow the dancers. They mark
                        the pace of those who have the function of carrying the image of the
                        corresponding saint. At the end of the procession, the music bands that
                        accompany each step are located and are followed by the faithful who
                        make the journey.
                    </p>
                    <img src="{{ asset('img/blog/corpus-christy-bandas.jpg') }}"
                        alt="Mayordomos en Corpus Christi Cusco" width="100%" loading="lazy">
                    <i>Picture: Butlers or 'Carguyos'</i><br><br>
                    </div>
                    <div id="descent" style="padding-top:70px"><h2>Descent of the Saints - Corpus Christi:</h2>
                    <p>
                        It is usually carried out the day after the eighth, it consists of the
                        return of the sacred images to their temples or churches, however, there
                        are others such as the Virgin of Bethlehem that follow different
                        protocols, this activity multiplies the faithful attendees and the
                        dances that will accompany the patron saints, in such a way that
                        following any of these processions is delightful.
                    </p>
                    <p>
                        We must also mention the dance performances that the neighborhoods star
                        in, they are part of our multicultural legacy. This party is part of our
                        Cusco pride!
                    </p>
                    <img src="{{ asset('img/blog/Danza-Majeno-corpus-christi.webp') }}"
                        alt="Danza Majeños en el Corpus Christi" width="100%" loading="lazy">
                    <i>Picture: Dance Majeño in Corpus Christi</i><br><br>
                    </div>

                    <div id="chiriuchu" style="padding-top:70px"><h2>Chiriuchu! The typical dish of this holiday!</h2>
                    <p>
                        The traditional dish of this festivity is the Chiriuchu, an exquisite
                        cold and spicy dish for all demanding palates. A mixture of 10
                        ingredients such as baked guinea pig (it must be taken into account that
                        the meat of this rodent must be tender, so that when someone consumes it
                        is soft since this way the characteristic flavor is preserved), cau cau
                        (egg cup of fish), parboiled chicken, cured meat, qochayuyo (seaweed),
                        chorizo, white corn field, cheese, sliced rocoto pepper, and the typical
                        torreja. This torreja of spongy consistency is the combination of some
                        ingredients such as corn, pumpkin, and a little chicken broth during
                        preparation so that it has a characteristic flavor when served.
                    </p>

                    <div class="text-center">
                        <img src="../img/blog/chiriuchu-andean-exclusive.jpg" id="img-blog"
                            alt="Chiriuchu in Corpus Christi">
                    </div>
                    <i>The Chiri Uchu accompanies the Corpus Christi celebrations and is highly
                        demanded by the attending public.</i><br><br>
                    <p>
                        Once you have the dish in front of you, what you should do is take a
                        piece of each of the elements that make it up, only in this way will you
                        feel the exquisite flavor that makes this dish a cusqueño delirium and
                        is inevitable not only on this festivity but also in the most important
                        moments of our lives, because, although the pandemic that began in 2020
                        has not allowed us to continue celebrating our parties in the same way,
                        we are sure that the Chiri Huchu has transcended and that today, June
                        03, (the day this post is published), Corpus Christi day is at the table
                        of the Cusqueños who impatiently yearn for our customs.
                    </p>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/chiriuchu.jpg" id="img-blog" alt="Chiriuchu">
                    </div>
                    <br>
                    <br>


                    <!-----Booking---------->
                    <div class="form text-center">
                        <h5>Share</h5>
                        <!---------redes sociales------------------------>
                        <script type="text/javascript"
                            src="https://platform-api.sharethis.com/js/sharethis.js#property=6232ae6fcdbe0200193e185e&product=inline-share-buttons"
                            async="async"></script>
                        <div class="sharethis-inline-share-buttons"></div>
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v13.0&appId=588015035734857&autoLogAppEvents=1"
                            nonce="RA0e6tQN"></script>
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/corpus-christi-cusco"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>

                    </div>
                </div>
            </div> <br><br>
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
                            <p class="card-text">The most mysterious and huge figures carved in the desert of
                                Ica,
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
                            <p class="card-text">Being pantheistic, the Inca religion had a special day for
                                mother
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
