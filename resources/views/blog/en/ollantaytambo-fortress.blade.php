@extends('layouts.general-en')
@section('metas')
    <title>Ollantaytambo Fortress - Andean Exclusive Tours</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords"
        content="ollantaytambofortress, ollantaytambo, cusco ollantaytambo, ollantaytambo ruins, ollantaytambo arqueological center, arqueological center, book ollantaytambo, reserve cusco, btg cusco, tourist ticket" />
    <meta name="description"
        content="It is a town that is located northeast of the colorful Valley of the Incas. Ollantaytambo was before the Incas a small growing town that entered the domain of the Incas at the time of the Inca Pachacútec" />
    <meta property="og:url" content="https://www.andeanexclusive.com/blog/en/ollantaytambo-fortress">
    <meta property="og:title" content="Ollantaytambo Fortress - Andean Exclusive Tours">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/templo-del-sol-ollantaytambo.webp" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/en/ollantaytambo-fortress">
    @include('layouts.links')
@endsection
@section('contenido')
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
                                        <a href="{{ route('fortaleza-ollantaytambo') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('gastronomia-peruana') }}">
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
                    <a>Peruvian gastronomy</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#construction">Construction of Ollantaytambo</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#temple">The temple of the Sun</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#curiosities">Ollantaytambo curiosities</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#placesToVisit">What places to visit in Ollantaytambo</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#tours">Tours Ollantaytambo</a></li>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-8">
                    <h1 class="text-center">Ollantaytambo Fortress</h1>
                    <div id="separadordjm2"></div>
                    <p class="text-center">By: David Miranda</p>
                    <p class="text-center"><i class="fa fa-calendar"></i> 02/12/2022</p>
                    <p>
                        It is a town that is located northeast of the colorful Valley of the Incas. Ollantaytambo was before the Incas a small growing town that entered the domain of the Incas at the time of the <strong>Inca Pachacútec</strong> (1418 - 1471). This town was established as a very important checkpoint, as well as being the place where corn could be domesticated for its abundant production, taking advantage of the fact that the Urubamba river was right next to the town.
                         <br><br>
 
                         This town is known for being one of the oldest human settlements in America that has been inhabited. Just by wandering through the streets of this town, a traveler can go back in time in a special way.
                     </p>
                    <img src="{{ asset('img/blog/OLLANTAYTAMBO.webp') }}" width="100%"
                        alt="Recomendaciones para viajar a Perú" loading="lazy">
                    <i>Ollantaytambo platforms</i>
                    <br><br>
                    <div id="construction" style="padding-top:70px">
                        <h2>Construction of Ollantaytambo</h2>
                    </div>
                    <p>
                        It is known that Pachacútec was the one who built the main buildings of the current
                        archaeological site of Ollantaytambo, but there is evidence that the origins of Ollantaytambo
                        date back approximately 400 years. <br><br>

                        There is evidence of the Huari culture in the town sites and indications of much older
                        settlements, so it is most likely that the Incas built on top of a city that already existed.
                    </p>

                    <div id="temple" style="padding-top:70px">
                        <h2>The temple of the Sun</h2>
                    </div>
                    <p>
                        At the top of Ollantaytambo built on a terraced tower on the west side, is the 'Aracama', which
                        is where the Temple of the Sun is located, this is a unique building in Cusco.<br><br>

                        It has 150 steps with a perfect carving of stones similar to the Tipón Valley to the south of
                        Cusco, among the architecture, the 6 erect monoliths stand out and joined by huge sheets of
                        stone, they were created with the purpose of cushioning seismic movements.<br><br>

                        It is because of the terraces of Ollantaytambo that this town is seen as one of the most
                        impressive examples of Inca architecture, engineering and agriculture, they are 700 meters high,
                        58 meters wide and 15 meters deep, built in relation to the sun, these create microclimates
                        which allowed to grow products from different altitudinal zones.
                    </p>
                    <img src="{{ asset('img/blog/templo-del-sol-ollantaytambo.webp') }}" width="100%"
                        alt="Lenguas más habladas en Perú" loading="lazy">
                    <i>Templo del Sol</i> <br> <br>

                    <div id="curiosities" style="padding-top:70px">
                        <h2>Ollantaytambo curiosities </h2>
                    </div>
                    <ul>
                        <li>It has the Temple of the Sun, which is considered one of the best construction works carried
                            out in the time of the Incas.</li>
                        <li>Both its cultural, scenic, natural, historical and architectural attributes that
                            Ollantaytambo possesses, have awarded it the merit as <strong>'Best Tourist Towns in the
                                World'</strong>, at the twenty-fourth General Assembly of the World Tourism Organization
                            (UNWTO). <i>Spain – Madrid 2021</i>
                        </li>
                        <li>Ollantaytambo has also been declared the <strong>'World Capital of Indianness'</strong> and
                            its Wallata dance from the Patacancha river basin was declared Cultural Heritage of the
                            Nation by the Ministry of Culture, on October 30, 2008.</li>
                        <li>It is located at a height of 2792 m.s.n.m. In the province of Urubamba 60 kilometers
                            northeast of Cusco, it is divided into 2 sectors: the town and the archaeological site.</li>
                        <li>The legend <strong>'El Drama Ollantay'</strong> maintains that in the time of Pachacútec
                            there was an admiral who revolted against the Inca for the love of his daughter. The word
                            'tambo', means in Quechua, place of rest or hostel for travelers. Which means that Ollantay
                            used the 'tambo' as its base.</li>
                        <li>In addition to having been a fortress, the constructions indicate that it was also a resting
                            place for both the Inca and the <strong>Colla</strong> (Inca's Wife).</li>
                        <li>To visit the archaeological site you must buy the Tourist Ticket that allows you to visit
                            other archaeological destinations. You can also visit the old abandoned quarries from where
                            the city's construction material was extracted.</li>
                        <li>This town currently still retains its Inca urban layout, it has a polygonal design and with
                            the main square in the middle and organized streets. It has many tourist services such as
                            hotels, restaurants, cafes and i's where you can take the train to go to the citadel of
                            Machu
                            Picchu.</li>
                        <li>It has several branches of the Inca trail <strong>'Qapac Ñan'</strong> in good condition.
                        </li>
                    </ul>
                    <img src="{{ asset('img/blog/el-drama-ollantay.webp') }}"
                        alt="Billetes de cien soles moneda peruana" loading="lazy">
                    <i>The Ollantay drama</i><br><br>
                    <div id="placesToVisit" style="padding-top:70px">
                        <h2>What places to visit in Ollantaytambo</h2>
                    </div>
                    <ul>
                        <li>The platforms</li>
                        <li>Megalithic walls</li>
                        <li>Hike to Inti Watana</li>
                        <li>View of Tunupa</li>
                        <li>Inca Profile</li>
                        <li>Sundial</li>
                        <li>Temple of the Condor</li>
                        <li>Royal baths of the ñusta</li>
                        <li>The town and its streets</li>
                    </ul>
                    <div id="tours" style="padding-top:70px">
                        <h2>Tours that include Ollantaytambo</h2>
                    </div>
                    <ul>
                        <li><a href="{{ route('cusco-5-days') }}"><strong>Cusco 5 days</strong></a> </li>
                        <li><a href="{{ route('cusco-6-days') }}"><strong>Cusco 6 days</strong></a> </li>
                        <li><a href="{{ route('peru-6-days') }}"><strong>Perú 6 days</strong></a> </li>
                        <li><a href="{{ route('peru-8-days') }}"><strong>Perú 8 days</strong></a> </li>
                    </ul>

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
                        <div class="fb-comments"
                            data-href="https://andeanexclusive.com/blog/en/ollantaytambo-fortress" data-width="100%"
                            data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            </div>
            <div class="row pt-5">
                <div class="col-lg-12 pt-5 text-center">
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
@endsection
