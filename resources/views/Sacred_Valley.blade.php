@extends('layouts.general-en')
@section('metas')
    <title>Enjoy this tour in the Heart of the Sacred Valley. Tour to Perú - Machu Picchu</title>
    <meta name="keywords" content="Experiences in Perú, luxury tours, Lima, Cusco, Exclusive Cusco" />
    <meta property="og:description" content="Meet the amazing Sacred Valley of the Inkas in the center of Cusco." />
    <meta property="og:title" content="Enjoy this tour in the Heart of the Sacred Valley.">
    <meta property="og:type" content="webside">
    <meta property="og:image" content="https://www.andeanexclusive.com/img/tours/valle-sagrado.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/Sacred_Valley" />
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="/" rel="home" class="logo-text-link">
                                    <img src="img/andean-exclusive-logo.png" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu')
                                    <li id="display" class="menu-has-children">
                                        <a>
                                            <button type="button" class="botondjm">Language <i
                                                    class="fa fa-chevron-down"></i></button>
                                        </a>
                                        <ul>
                                            <li><a href="valle-sagrado">Español</a></li>
                                            <li><a>Ingles</a></li>
                                        </ul>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="valle-sagrado">ES</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a>EN</a>
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
        <section class="section-content no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="blog-item blog-single">
                            <div class="entry-excerpt">
                                <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                    data-onepage-slug="home"
                                    class="vc_row wpb_row valle vc_row-has-fill vc_row-o-full-height vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    @if (session()->has('flash'))
                                                                        <center>
                                                                            <div class="alert alert-success" id="alerta"
                                                                                style="width: 60%;">
                                                                                {{ session()->get('flash') }}<button
                                                                                    type="button" onclick="bye()"
                                                                                    style="float: right;">X</button>
                                                                            </div>
                                                                        </center>
                                                                    @endif
                                                                    <h1><?php $tour = 'Sacred Valley';
                                                                    echo $tour; ?></h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>
                                <div data-onepage-title="Services" data-onepage-slug="services"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
                                    <div id="separador"></div>
                                    <div class="row" id="rowdjm">
                                        <div class="location">
                                            <div class="row text-center">
                                                <div class="col-lg-3 col-sm-2" id="display2">
                                                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-10"><a href="/">Home</a> /
                                                    <a href="experiences">Popular</a> /
                                                    <a>Sacred Valley of the Incas</a>
                                                </div>
                                                <div class="col-lg-3 col-sm-2" id="display2">
                                                    <div style="width: 90%; height:1px; background:grey; margin-top:0.6em">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 id="titulo1" class="text-center">The heart of the Sacred Valley</h2>
                                        <div id="separadordjm2"></div>

                                        <div class="col-lg-3 col-md-3">
                                            <h5 class="text-center">"Enjoy of a unique experience in the Inca
                                                Heartland with the Rajchi Community"</h5>
                                            <div id="separador"></div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <p class="text-center" style="color: #0c8178"><i class="fa fa-map-marker"></i>
                                                Cusco - Ollantaytambo - Pisaq
                                            </p>
                                            <h4>Overview:</h4>
                                            <p>We begin picking you up in your hotel around 8:00 to start our tour of
                                                sacred valley from Cusco of one day long. First, we visit Pisac town
                                                famous for the beautiful handcrafting and the archeological center. At
                                                noon, we’ll have lunch in Urubamba, then we visit Ollantaytambo and we
                                                end up in Chinchero known by its textile. Around 18:30h we get back to
                                                Cusco.
                                            </p>
                                            <h4>Itinerary:</h4>
                                            <p>
                                                At 8:00h we pick you up from your hotel and 45m later we’ll be at
                                                Pisac’s archeological center. There you’ll meet the advanced
                                                arquitecture of the Incas. Many thought it was a fortress but now we
                                                know it was a "real farm" that belonged to Inca Pachacutec. Compound by
                                                platforms, domestic structures and aqueducts. In Pisac you must visit
                                                its handcraft Market there you’ll buy nice and unique souvenirs.
                                                At noon, we will have a buffet lunch at Urubamba; you’ll enjoy the
                                                traditional Peruvian food. Urubamba was known for being one of the main
                                                agricultural centers of the Inca Empire. Now days the best corn in the
                                                world is harvest there.
                                                After noon we will visit the fortress of Ollantaytambo what become an
                                                important strategic military center, as well as, agriculture and
                                                religious centers needed to control the whole Inca’s sacred valley. This
                                                archeological place was built as an overlap terrace with huge carved
                                                stones.
                                                In our way back to Cusco, we will visit Chinchero, a towm known by
                                                Nuestra Señora de Monserrat, church built over Tupac Yupanqui palace in
                                                1607 and we also visit Andenería and its market. End of the Sacred
                                                Valley Tour from Cusco 1 day.
                                            </p>
                                            <h4>Included:</h4>
                                            <p>
                                                &#10004; Entrances to the Archaeological site of Pisac, Ollantaytambo
                                                and Chinchero.<br>
                                                &#10004; Transfer to all mentioned places<br>
                                                &#10004; Guided in group, Professional English - Spanish Guide.<br>
                                                &#10004; Lunch
                                            </p>
                                            <h4>Not Included:</h4>
                                            <p>
                                                &#88; Hotel in Cusco.<br>
                                                &#88; Breakfast and dinner.<br>
                                                &#88; Other services not mentioned in the program.
                                            </p>
                                            <h4>Booking</h4>
                                            <p>
                                                ✤ Minimum payment of 50% to guarantee your reservation<br>
                                                ✤ You can pay with any credit/debit card or by bank deposit<br>
                                                ✤ Check availability before making the payment<br>
                                                ✤ The price of the tour varies according to the type of accommodation
                                            </p> <br>
                                            @include('layouts.payments')

                                            <h5 class="text-center">Share</h5>
                                            <!---------redes sociales------------------------>
                                            <script type="text/javascript"
                                                src="https://platform-api.sharethis.com/js/sharethis.js#property=6232ae6fcdbe0200193e185e&product=inline-share-buttons"
                                                async="async"></script>
                                            <div class="sharethis-inline-share-buttons"></div>
                                            <div id="fb-root"></div>
                                            <script async defer crossorigin="anonymous"
                                                src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v13.0&appId=588015035734857&autoLogAppEvents=1"
                                                nonce="RA0e6tQN"></script>
                                            <div class="fb-comments" data-href="https://andeanexclusive.com/Sacred_Valley"
                                                data-width="100%" data-numposts="5"></div>
                                            <!---------Fin redes sociales------------------------>

                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <div class="form text-center">
                                                <div class="form text-center">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>

                                <div data-onepage-title="Services" data-onepage-slug="services"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461227943574">
                                            <div class="wpb_wrapper">
                                                <div class='heading  text-center '>
                                                    <div id="separador"></div>
                                                    <h3>Similar Tours</h3>
                                                    <div id="separadordjm"></div>
                                                </div>
                                            </div>
                                            <div class="vc_row wpb_row vc_inner vc_row-fluid vc_custom_1461228476759">
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228417147">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a class='entry-link' href="urubamba-market"><img
                                                                            src="img/thumbnail/urubamba-market.png"
                                                                            alt="Urubamba Market - Sacred Valley"></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <h3>Urubamba Market</h3>
                                                                    <div class='info'>
                                                                        Duration: 1 day
                                                                    </div>
                                                                    <div class='readmore'>
                                                                        <a href='urubamba-market' class='button'>Read
                                                                            more</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228442150">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a class='entry-link' href="Hiram_bingham"><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/Machupicchu.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt=""
                                                                            srcset="img/thumbnail/Machupicchu.jpg 400w"
                                                                            sizes="(max-width: 400px) 100vw, 400px" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <h3>Machu Picchu By Hiram Bingham</h3>
                                                                    <div class='info'>
                                                                        Duration: 1 days
                                                                    </div>

                                                                    <div class='readmore'>
                                                                        <a href="Hiram_bingham" class='button'>Read
                                                                            more</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a class='entry-link' href="peru-6-days-5-nights"><img
                                                                            width="400" height="400"
                                                                            src="img/thumbnail/chincheros.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt=""
                                                                            srcset="img/thumbnail/chincheros.jpg 400w"
                                                                            sizes="(max-width: 400px) 100vw, 400px" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <h3>Peru 6 days</h3>
                                                                    <div class='readmore'>
                                                                        <a class='button' href="peru-6-days-5-nights">Read
                                                                            more</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpb_column vc_column_container vc_col-sm-3">
                                                    <div class="vc_column-inner vc_custom_1461228449756">
                                                        <div class="wpb_wrapper">
                                                            <div class='travel-item'>
                                                                <div class='entry-img'>
                                                                    <a class='entry-link'
                                                                        href="peru-10-days-9-nights"><img width="400"
                                                                            height="400"
                                                                            src="img/thumbnail/Machupicchu_AET.jpg"
                                                                            class="attachment-post-grid-s size-post-grid-s"
                                                                            alt="Tour in Machu Picchu - Cusco" /></a>
                                                                </div>
                                                                <div class='entry-info'>
                                                                    <h3>Perú for 10 days</h3>
                                                                    <div class='readmore'>
                                                                        <a href='peru-10-days-9-nights'
                                                                            class='button'>Read more</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
