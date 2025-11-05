@extends('layouts.general-es')
@section('metas')
    <title>Find what our customer says about Andean Exclusive Tours</title>
    <meta name="keywords" content="Experiences in Perú, opinions, Lima, Cusco, Exclusive, luxury, testimonials" />
    <meta property="og:description" content="opinions from customer of Andean Exclusive." />
    <meta property="og:url" content="https://www.andeanexclusive.com/testimonios">
    <meta property="og:title" content="Find what our customer says about Andean Exclusive Tours">
    <meta property="og:type" content="webside">
    <meta property="og:image" content="https://www.andeanexclusive.com/img/testimonials.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/testimonios" />
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="index-castellano" rel="home" class="logo-text-link"><img
                                        src="img/andean-exclusive-logo.png" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{ route('testimonials') }}">
                                            <button type="button" class="btn botondjm">English</button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2">
                                        <a href="{{ route('testimonials') }}">
                                            <button type="button" class="btn botondjm">English</button>
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
        <section class="section-content no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="blog-item blog-single">
                            <div class="entry-excerpt">
                                <div data-vc-full-width="true" data-vc-full-width-init="false" data-onepage-title="Home"
                                    data-onepage-slug="home"
                                    class="vc_row wpb_row vc_row-fluid testimonials vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    <h1>Lo que nuestros clientes dicen de Andean Exclusive
                                                                    </h1>
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
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461227943574">
                                            <div class="wpb_wrapper">
                                                <div class='heading  text-center '>
                                                    <div id="separador"></div>
                                                    <h3>GUEST REVIEW </h3>
                                                    <div id="separadordjm"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="separador"></div>
                                    <div class="vc_row-full-width vc_clearfix"></div>
                                </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <div class="card-container">
                    <div class="card u-clearfix text-center">
                        <span class="card-number card-circle subtle text-center">01</span>
                        <span class="card-author subtle text-center">Emmanuel Burgio</span>
                        <h2 class="card-title">President Blue Parallel. Inc.</h2>
                        <span class="card-description subtle"> <i class="fa fa-quote-left"></i> On behalf of all
                            Blue Parallel team, I would like to
                            thank you a million for all the
                            hard and excellent work over the past 12 years. It has been a real pleasure to
                            work with you and we always felt that we could count on you regardless of the
                            day / time and regardless of how challenging the request was. We will miss you
                            a lot as we almost felt you were part of our day -to day team. We wish you the
                            best of luck, and we hope that our paths will again cross in the future. <i
                                class="fa fa-quote-right"></i>
                        </span>
                        <center>
                            <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                        </center>
                        <div class="row">
                            <center>
                                <div class="primer">
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
                <div style="height: 100px"></div>
                <div class="card-container">
                    <div class="card u-clearfix text-center">
                        <span class="card-number card-circle subtle text-center">02</span>
                        <span class="card-author subtle text-center">The Anderson Family</span>
                        <h2 class="card-title">Hello Carmen:</h2>
                        <span class="card-description subtle">
                            }<i class="fa fa-quote-left"></i>
                            Thank you for our delicious lunch with you. We enjoyed it very much and also our
                            weaving visit and guinea pig!! You and your mother are twins!! I just wanted to
                            confirm we leave lake Titilaka hotel with Octavio tomorrow at 07.00am. Thank you
                            and until next time
                            <i class="fa fa-quote-right"></i>
                        </span>
                        <center>
                            <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                        </center>
                        <div class="row">
                            <center>
                                <div class="herberth">
                                </div>
                            </center>
                        </div>

                    </div>
                </div>
                <div style="height: 100px"></div>
                <div class="card-container">
                    <div class="card u-clearfix text-center">
                        <span class="card-number card-circle subtle text-center">03</span>
                        <span class="card-author subtle text-center">Travel Planner in Aracari
                        </span>
                        <h2 class="card-title">Dear Carmen, Marleny, Luisa and Raquel:</h2>
                        <span class="card-description subtle">
                            }<i class="fa fa-quote-left"></i>
                            I hope you are good I just wanted to tell you from the date my load left as a Travel
                            Planner in Aracari. Keep in touch with the company and I hope we can find again in
                            the future It has been a pleasure working together with Andean Exclusive team and I
                            thank you very much for your professionalism, patience, support and dedication. I
                            leave my contact information.
                            <i class="fa fa-quote-right"></i>
                        </span>
                        <center>
                            <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                        </center>
                        <div class="row">
                            <center>
                                <div class="dear-carmen">
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
                <div style="height: 100px"></div>
                <div class="card-container">
                    <div class="card u-clearfix text-center">
                        <span class="card-number card-circle subtle text-center">04</span>
                        <span class="card-author subtle text-center">Grace Mary Riggs Brousseau *** Susan David ***
                            Nancy Neglia Willard</span>
                        <h2 class="card-title">Dear Andean Exclusive Team:</h2>
                        <span class="card-description subtle">
                            }<i class="fa fa-quote-left"></i>
                            We were extremely pleased with our tour. Andean Exclusive, Carmen and her
                            team, are creative and attentive. The guides were top-notch and the attention
                            was personalized. We were particularly pleased with those special touches
                            and surprises that were included, such as the picnic lunch in the beautiful
                            countryside and the “chaman” ceremony of offering to the “Pachamama”. We
                            were treated like royalty at all times. The sites chosen and included in our tour
                            were exactly what we wanted, in order to really appreciate the history as
                            well as present day customs and daily living in Cusco and surrounding areas.
                            We not only visited monuments but attended demonstrations, for example, on
                            the dying of fibers and the weaving of those beautiful alpaca articles Cusco is
                            so famous for.
                            <i class="fa fa-quote-right"></i>
                        </span>
                        <center>
                            <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                        </center>
                        <div class="row">
                            <center>
                                <div class="susan-david">
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
                <div style="height: 100px"></div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-container">
                            <div class="card u-clearfix text-center">
                                <span class="card-number card-circle subtle text-center">05</span>
                                <span class="card-author subtle text-center">Erna</span>
                                <span class="card-description subtle">
                                    }<i class="fa fa-quote-left"></i>
                                    So wonderful to hear from you!! Our time in Peru was amazing and I think in some
                                    way we will
                                    be forever touched by our time spent there. You know that we were overwhelmingly
                                    taken with
                                    our trip. In particular, some of your team were so outstanding that it is
                                    difficult for others to be
                                    compared. Veronica, Freddie, Lucio and Mario were amazing!! They delivered
                                    outstanding
                                    professional services. Along with their patience, their pleasure in making the
                                    best experience
                                    possible for us and delightful company, these things set them apart from all
                                    others. Monica and I
                                    send you our warmest thank you for watching over us and all your special
                                    attention!!! <i class="fa fa-quote-right"></i><br>
                                    <strong>Best, Erna</strong>
                                </span>
                                <center>
                                    <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                                </center>
                                <div class="row">
                                    <center>
                                        <div class="susan-david">
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-container">
                            <div class="card u-clearfix text-center">
                                <span class="card-number card-circle subtle text-center">06</span>
                                <span class="card-author subtle text-center">Dear Carmen </span>
                                <h2 class="card-title">Hi there!!!</h2>
                                <span class="card-description subtle">
                                    <i class="fa fa-quote-left"></i>
                                    Hi there!!! Denise and I and our families want to thank you for such a GREAT
                                    trip!!! We had such a great experience in your country. All of the guides and
                                    drivers were SO great
                                    -
                                    knowledgeable, friendly, accommodating and FLEXIBLE. We were very leary of the
                                    whole process but you made believers out of us. So THANK YOU for your hard work
                                    to
                                    make sure we got what we wanted and worked with us on price and details! Also,
                                    thank you, and
                                    your family. for having us over for dinner AND for the blanket that David
                                    brought for us to
                                    take home - SO nice!! Your family was so nice and went out of their way to make
                                    such a nice
                                    dinner! Please thank them again for us! Sorry to have missed you, but like we
                                    told your family, you
                                    are always welcome at our home in the US if you ever come!!! We would welcome
                                    hosting
                                    you!!! :) Thanks again Carmen, and hope to see you again someday!!!!

                                    <i class="fa fa-quote-right"></i>
                                </span>
                                <center>
                                    <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                                </center>
                                <div class="row">
                                    <center>
                                        <div class="denise">
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-container">
                            <div class="card u-clearfix text-center">
                                <span class="card-number card-circle subtle text-center">07</span>
                                <span class="card-author subtle text-center">Allow me to introduce Carmen-</span>
                                <span class="card-description subtle">
                                    <i class="fa fa-quote-left"></i>
                                    she is my favorite travel guru in Peru and can help your Dad with any
                                    and all of his travel needs in Peru.
                                    I have sent many family and friends to her over the year and have never been
                                    anything but blown
                                    away by her professionalism and expertise. There is nobody better for luxury in
                                    Peru!! And she can get
                                    you often better prices than going direct to the outfitters because of her
                                    volume. Carmen, meet our good
                                    friend Jon. HE is looking for a few options for river cruises for his dad in
                                    Peru. I thought you might look at
                                    the Delfin as well as maybe Aqua...?? I will leave it up to you which others you
                                    think he might like.
                                    Please take very good care of him as I know you will!!
                                    Ok, I will leave it with you guys now to figure out the details. <i
                                        class="fa fa-quote-right"></i><br>
                                    <strong>Best, Amber</strong>
                                </span>
                                <center>
                                    <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                                </center>
                                <div class="row">
                                    <center>
                                        <div class="amber">
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-container">
                            <div class="card u-clearfix text-center">
                                <span class="card-number card-circle subtle text-center">08</span>
                                <h2 class="card-title">Dear Carmen, </h2>
                                <span class="card-description subtle">
                                    <i class="fa fa-quote-left"></i>
                                    Our October 23-November 1st was a real success and the travellers left Peru with
                                    unforgettable
                                    memories. I wanted to thank you personally since you were the architect of the
                                    trip. When these VIP
                                    travellers asked me to organize their trip to Peru, you were obviously my first
                                    choice as a local
                                    operator. My trust was based on our longtime relationship when I was a regional
                                    director for
                                    Butterfield and Robinson and you were Auqui's chief of operations. I was happy
                                    to realize that your
                                    standards were as high as ever and that we still shared the same attention to
                                    details. I will definitely
                                    communicate with you next time I organize a trip to Peru
                                    <i class="fa fa-quote-right"></i><br>
                                    <strong>Warm regards, Jean-Louis Doss</strong>
                                </span>
                                <center>
                                    <img src="{{ asset('img/line.png') }}" alt="" width="50%">
                                </center>
                                <div class="row">
                                    <center>
                                        <div class="louis">
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="row">
                        <h3 class="text-center">Personalities Served</h3>
                        <div id="separadordjm"></div>
                        <div style="height: 70px"></div>
                        <div class="col-lg-7">
                            <p style="font-size: 16px; line-height:55px">
                              <i class="fa fa-angle-right"></i> Corporation Cisneros y su invitado
                                especial Sr. David Rockefer. <br>
                                <i class="fa fa-angle-right"></i> 1999 - Princesa Sayako Nori <br>
                                <i class="fa fa-angle-right"></i> 1999 - Cesar Gaviria Presidente de la
                                OEA <br>
                                <i class="fa fa-angle-right"></i> Jose Aznar – Presidente de España <br>
                                <i class="fa fa-angle-right"></i> Febrero 2012: Visita a Peru de Paul
                                David Hewson conocido mundialmente
                                como Bono , vocalista de la banda U2<br>
                                <i class="fa fa-angle-right"></i> Diciembre 2018 Exjequesa Mozah bint
                                Nasser al Missned, 3ra esposa del Ex
                                emir de Qatar Hamad Bin Khalifa al
                                Thani.<br>
                                <i class="fa fa-angle-right"></i> Enero 2019 : Mr. Robert Anthony De
                                Niro<br>
                                <i class="fa fa-angle-right"></i> Julio 2019 : Tonny Robbins Platinum
                                Lyons<br>
                                <i class="fa fa-angle-right"></i> Octubre 2019: Damien Hirst Steven
                                conocido como el Picaso Moderno<br>
                                <i class="fa fa-angle-right"></i> Febrero 2020: Sthephen Smith
                                Inversionista<br>
                            </p>
                        </div>
                        <div class="col-lg-5"><br><br>
                          <div class="img-super-2"></div>
                          <div class="img-super-1"></div>
                          <div class="img-super-3"></div>
                          <div class="img-super-4"></div>
                          <div class="img-super-5"></div>
                        </div>
                        
                    </div>-->
            </div>
        </section>
    </div>
@endsection
