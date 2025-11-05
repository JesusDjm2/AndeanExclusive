<!DOCTYPE html>
<html lang="en-US" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>TOUR DE RAFTING EN CUSCO </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="RAFTING EN CUSCO, rafting tour in Perú, rafting tour, Vilcanota River" />
    <meta name="description"
        content="River Rafting in Cusco is an activity to get the adrenaline pumping and unforgettable experience, as well as offering unique spectaculars views of the Cusco Andean Mountains in remote places hard to reach by road. " />
    <meta property="og:url" content="https://www.andeanexclusive.com/adventures/rafting-tour">
    <meta property="og:title" content="RAFTING EN CUSCO">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:image" content="https://andeanexclusive.com/img/tours/canotaje-tour-cusco.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/adventures/rafting-tour">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel='stylesheet' href="{{ asset('styles/js_composer.min5243.css') }}" type='text/css' media='all' />
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/estilo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script type='text/javascript' src="{{ asset('js/jquery4a5f.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/mediaelement-and-player.min45a0.js') }}"></script>
</head>

<body class="home page-template-default page page-id-44 wpb-js-composer js-comp-ver-5.4.5 vc_responsive"
    style="font-size: 14px; font-weight: 400; color:#131313">
    @include('layouts.wasa-blog')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="/" rel="home" class="logo-text-link"><img
                                        src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-blog')
                                    <li id="display" class="menu-has-children">
                                        <a href="{{route('tour-rafting')}}">
                                            <button type="button" class="botondjm">Español
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="{{route('tour-rafting')}}">
                                            <button type="button" class="botondjm">Español
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
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <article class="blog-item blog-single">
                        <div class="entry-excerpt">
                            <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                data-onepage-slug="home"
                                class="vc_row wpb_row canotaje vc_row-has-fill vc_row-o-full-height vc_row-o-content-middle vc_row-flex">
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
                                                                <h1><?php $tour = 'RIVER RAFTING IN CUSCO';
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
                        </div>
                    </article>
                </div>
            </div>
        </div>

    </div>
    <div id="separador"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3" id="display2">
                <div class="sticky-top" style="padding-top: 75px; z-index: 40">
                    <div>
                        <div data-onepage-title="Services" data-onepage-slug="services"
                            class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
                            <h1 class="text-center" style="font-size:22px; font-weight:500; margin-top:30px">
                                Similar Tours
                            </h1>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../biking" class="button">Biking in the mountains</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="islands-palomino-tour" class="button">Islands Palomino Tour</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="atv-tour" class="button">ATV</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../peru-8-days-7-nights" class="button">Perú for 8 days</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../peru-12-days" class="button">Tour in Perú for 12 days</a>
                            </div>
                            <div id="similares">
                                <div id="separador"></div>
                                <a href="../peru-20-days-tour" class="button">Tour in Perú for 20 days</a>
                            </div>
                            <div id="separador"></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                <div data-onepage-title="Services" style="text-align:justify" data-onepage-slug="services"
                    class="vc_row-fluid vc_custom_1461248392126">
                    <center>
                        <h1 id="titulo1">RIVER RAFTING IN CUSCO</h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center"><i class="fa fa-map-marker"></i> Cusco -Vilcanota </p>
                        <p class="text-center"><i class="fa fa-clock-o"></i> Full Day</p>
                        <p class="text-center"><i class="fa fa-exclamation-circle"></i> book 15 days in advance</p>
                    </center>
                    <div id="separador"></div>
                    <h5 style="padding: 0px 1px 0px 20px;">Overview</h5>
                    <p style="padding: 0px 1px 0px 20px;">
                        River Rafting in Cusco is an activity to get the adrenaline pumping and unforgettable
                        experience, as well as offering unique spectaculars views of the Cusco Andean Mountains in
                        remote places hard to reach by road. Some of the best rivers for rafting in South America are in
                        the mountains surrounding Cusco, so if you want to give it a try this is the place!

                        All our one day trips run various sections of the Urubamba River, around 1 and a half hours from
                        Cusco. This is the same river which flows through the Sacred Valley and was worshipped by the
                        Incas as the ‘Bringer of Life’. Cusco River Rafting offers you security and guarantee in our
                        services, come and enjoy your trip with Us.
                    </p>
                    <div id="separador"></div>
                    <h5>ITINERARY:</h5>
                    <p><strong>DAY 1. CUSCO - CHIQUISCA</strong></p>
                    <p>
                        Our Trip start at 8:30AM the guide will pick you up from your hotel (Cusco), Cusipata and
                        Chuquicahuana are buried deep in the heart of the Sacred Valley of Cusco, They are located in
                        the upper part or first part of the Sacred Valley, Here you will receive a safety talk and the
                        necessary equipment to enjoy this adventure, This section of the river is more adventurous. In
                        the dry season it remains fairly tame with rapids of up to level III, however in the wet season
                        rapids can get as big as level IV. After the rafting tour you can visit the Cusco Zipline in the
                        Sacred Valley.<br>
                        - Depart: 08:30 am pickup from hotel in Cusco<br>
                        - Return: approx. 5pm<br>
                        - Time spent on the river: approx. 2 and a half hour.
                    </p>
                    <p>
                        <strong>Cusco Rafting Levels</strong><br>
                        Level I: Really easy without any risk, we just let the stream take us down.<br>
                        Level II: Stream with some waves and small rocks.<br>
                        Level III: Stream, rocks, holes, waves (enjoyable).<br>
                        Level IV: Stream, rocks, holes with more gradient or more volume of water with a little bit of
                        risk. For this level, it’s not necessary to have a safety kayak with the group.<br>
                        Level V: Stronger and more enjoyable than the class IV with an element of risk, maximum
                        commercial rafting level. For these rapids it’s necessary to have a safety kayak with the group.
                    </p>
                    <div id="separador"></div>
                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist"
                        style="text-transform: uppercase; font-size: 12px; font-weight: 700">
                        <li class="nav-item">
                            <a class="nav-link active" id="drop" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">Include</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">No Include</a>
                        </li>

                    </ul>
                    <div class="tab-content pb-5" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <ul>
                                <li>Information session prior to
                                    departure.</li>
                                <li>Pick up from your hotel in the morning and
                                    transfer by private transport to Cachora- starting point of the walk.</li>
                                <li>Entrance ticket to Choquequirao, for
                                    visitors and staff of the agency.</li>
                                <li>Tents.</li>
                                <li>Professional guide (Spanish and
                                    English).</li>
                                <li>Cook and kitchen equipment.</li>
                                <li>Pack of animals (to carry the equipment
                                    tents, food and kitchen).</li>
                                <li>1 emergency horse.</li>
                                <li>Meals (03B, 04L, 03D). Vegetarian or
                                    special menus are available at no additional cost.</li>
                                <li>First aid kit.</li>
                                <li>Private transportation from Cusco to
                                    Cachora.</li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <ul>
                                <li>Other services not mentioned in the
                                    program.</li>
                                <li>Tips</li>
                            </ul>
                        </div>

                    </div>
                    @include('layouts.payments')
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/adventures/rafting-tour"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div><br><br>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="sticky-top" style="padding-top: 75px; z-index: 40">
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-onepage-title="Services" data-onepage-slug="services" id="display"
        class="vc_row wpb_row vc_row-fluid vc_custom_1461248392126">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="vc_column-inner vc_custom_1461227943574">
                <div class="wpb_wrapper">
                    <div class='heading  text-center'>
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
                                        <a class='entry-link text-center' href="atv-tour">
                                            <img src="../img/thumbnail/rafting-cusco-Peru.jpg"
                                                alt="Rafting tour Cusco Peru" />
                                        </a>
                                    </div>
                                    <div class='entry-info'>
                                        <h3>ATV Cusco</h3>
                                        <div class='readmore'>
                                            <a href='atv-tour' class='button'>More info</a>
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
                                        <a class='entry-link text-center' href="../biking">
                                            <img width="400" height="400"
                                                src='../img/thumbnail/biking-cusco.jpg'
                                                class="attachment-post-grid-s size-post-grid-s"
                                                alt="Cusco tours de 6 días" />
                                        </a>
                                    </div>
                                    <div class='entry-info'>
                                        <h3>Biking in the Andes</h3>
                                        <div class='readmore'>
                                            <a href="../biking" class='button'>More Info</a>
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
                                        <a class='entry-link text-center' href="choquequirao-tour">
                                            <img width="400" height="400"
                                                src="../img/thumbnail/saced-valley-cusco.jpg"
                                                class="attachment-post-grid-s size-post-grid-s"
                                                alt="Tour for Choquequirao" />
                                        </a>
                                    </div>
                                    <div class='entry-info'>
                                        <h3>Chequirao Trekking</h3>
                                        <div class='readmore'>
                                            <a class='button' href="choquequirao-tour">More Info</a>
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
    @include('layouts.foot-blog')

</body>

</html>
