<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Feast of the Virgin of Carmen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:title" content="Feast of the Virgin of Carmen">
    <meta name="twitter:description" content="Virgin of Carmnen, Traditions, Paucartambo Cusco.">
    <meta name="twitter:image" content="http://andeanexclusive.com/blog/img/virgen-del-carmen-andean-exclusive.jpg">
    <meta name="keywords" content="festivity in cusco, celebration, customs, Virgin of Carmen." />
    <meta name="description"
        content="The streets of Paucartambo are filled with music, colors, faith and great religious devotion." />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/virgin-of-carmen  ">
    <meta property="og:title" content="Feast of virgin of Carmen">
    <meta property="og:type" content="article">
    <meta property="og:image" content="http://andeanexclusive.com/blog/img/virgen-del-carmen-andean-exclusive.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/virgin-of-carmen">

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
                                        <a href="{{ route('virgen-del-carmen') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('virgen-del-carmen') }}">
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
                    <a>Virgin of Carmen</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#history">History</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#partyprogram">Party Program</a></li>
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
                        <h1 id="titulo1">Feast of virgin of Carmen</h1>
                        <p class="text-center">Written by: David Miranda</p>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-calendar"></i> 15/07/2021
                    </center>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/virgen-del-carmen-andean-exclusive.jpg" id="img-blog"
                            alt="virgin of Carmen">
                    </div>
                    <div id="separador"></div>
                    <div id="history" style="padding-top:70px">
                        <h5>History</h5>
                        <p> In mid-July the streets of Paucartambo are filled with music, colors, faith and a great
                            religious devotion, the Mamacha del Carmen Festival brings together Peruvian folklore and
                            the largest number of parishioners, between July 15 to 18 of each year Thousands of people
                            congregate with a single feeling to worship the Mamacha del Carmen, celebrating its central
                            day on July 16. There are several versions on the origin of the festival in honor of the
                            Virgen del Carmen, in this blog we tell you some:</p> <br>
                        <p>It is said that there were groups of merchants from the highlands, called "Qollas", who
                            exchanged products such as cañiwa, chuño and cheeses with others with sugar, cane liquor,
                            chancaca and others from the coast. Tradition indicates that one day, when the products
                            coming from the highlands were downloaded, one of the Qollas found the head of the image of
                            the Virgen del Carmen among the vessels. This caused surprise and amazement among the
                            settlers. The head of the image was given to a woman who offered lodging to travelers and it
                            was she who ordered the body of the Virgen del Carmen to be made, which remains to this day.
                            Since then the Qollas pay homage and honor to the patron saint. </p> <br>
                        <p>Another version refers that in colonial times there were in Paucartambo Spanish estates and
                            colonists who dedicated themselves to the extraction of minerals in the Qosñipata laundries.
                            Noting that diseases such as malaria and others that easily attacked locals were spreading,
                            the landowners saw the need to bring Afro-Peruvian slaves from the coast to carry out such
                            work. These new inhabitants, who were devotees of the Virgen del Carmen, took the image to
                            the haciendas and secretly worshiped it. They say that the patron saint of one of the
                            haciendas discovered them and after becoming a devotee he ordered the construction of a
                            chapel to worship him. This version is supported by the Qhapaq Negro dance, one of the most
                            celebrated on the feast of the Virgin of Paucartambo.
                        </p>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/virgen-del-carmen-andean.jpg" id="img-blog"
                            alt="feast in Paucartambo - Cusco">
                    </div>
                    <br>
                    <br>

                    <div id="partyprogram" style="padding-top:70px">
                        <h5>Party program:</h5>
                        <h4>July 15</h4>
                        <p>The party begins with a fireworks show and the sound of bells. Then a troupe enters and the
                            night becomes an accomplice to make a bonfire. Afterwards, a group of villagers stage
                            purgatory through acrobatic jumps, races and different pranks. At 11:00 p.m. the band of
                            musicians performs a serenade to the virgin until the next day.</p> <br>
                        <h4>July 16</h4>
                        <p>It is the central day of this majestic festival. It starts at 5:00 a.m. with an Aurora mass,
                            then at 10:00 a.m. the celebration mass is given where they interpret the songs of the
                            Qollas and the black Qhapac (ancient cultures of Paucartambo). To end this sacred day in the
                            streets you can listen to troupes, dances and songs.</p> <br>
                        <h4>July 17</h4>
                        <p>Day of the great procession of the Virgen del Carmen and begins at 3:00 p.m. The wonderful
                            and unique thing about this festivity is the unfolding of the dancers dressed in bright
                            colors and masks, they go up to the ceilings and balconies making movements that follow the
                            musical figure of the band. There are also the saqras who are in charge of waiting for the
                            "Patroness of the mestizos". This day is considered the day of the blessing and the parade
                            goes to the cemetery, to show their gratitude to the deceased dancers.</p> <br>
                        <h4>July 18</h4>
                        <p>Date when the virgin is taken to the temple and the children expect to be blessed by the
                            priest, performing the traditional "ocarikuy". At 4:00 pm. the celebration is concluded with
                            the priest's blessing to the entire population.</p> <br>
                        <h4>July 19</h4>
                        <p>It is the last day of joy. From 11:00 a.m. the image is placed on her altar and the change of
                            clothing for the sacred sculpture begins, a very special ceremony for the residents. They
                            say goodbye praying and accompanying until the last moment the Virgen del Carmen.</p> <br>
                        <div class="text-center">
                            <img src="../img/blog/virgen-del-carmen-danza-tipica.jpg" id="img-blog"
                                alt="Danza en Paucartambo">
                        </div> <br><br>
                        <p>The Virgen del Carmen was crowned by Pope John Paul II during his pilgrimage to Peru in 1985.
                            This event is celebrated today in the Andean world, as a very important event by the
                            ceremony held in Sacsayhuamán, in the city of Cuzco. It is worth mentioning that the
                            festivity of the Virgen del Carmen de Paucartambo was declared Intangible Cultural Heritage
                            by the National Institute of Culture, on April 11, 2006. </p>
                    </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item"
                                        src="https://www.youtube.com/embed/Xn_oFKSdTPo" frameborder="0"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div> <br><br>
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
                            <div class="fb-comments" data-href="https://andeanexclusive.com/blog/virgin-of-carmen"
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
