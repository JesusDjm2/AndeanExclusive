@extends('layouts.general-en')
@section('metas')
    <title>More about Andean Exclusive Tours. Travel Agency </title>
    <meta name="description" content="Find a lot of experiences around Perú, luxury tours for enjoy each moment." />
    <meta property="og:url" content="https://www.andeanexclusive.com/about">
    <meta property="og:title" content="More about travel agency: Andean Exclusive Tours.">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/cusco-de-noche.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/about" />
@endsection
@section('contenido')
    <!--End Boton wasa-->
    <div class="wrapper">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-wrapper">
                            <div class="site-branding">
                                <a href="/" rel="home" class="logo-text-link"><img
                                        src="img/andean-exclusive-logo.png" id="logo"></a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu')
                                    <li id="display" class="menu-has-children">
                                        <a href="{{ route('nosotros') }}">
                                            <button type="button" class="botondjm">Español</button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="{{ route('nosotros') }}">
                                            <button type="button" class="botondjm">Español</button>
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
                                    class="vc_row wpb_row vc_row-fluid about vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    <h2>ABOUT US</h2>
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
                                <div data-vc-full-width="true" data-vc-full-width-init="false"
                                    data-onepage-title="Hot Tours" data-onepage-slug="hot-tours"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1523936007189 vc_row-has-fill">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner ">
                                            <div class="wpb_wrapper">
                                                <div class='heading  text-center '>
                                                    <img src="img/logo2.png">
                                                    <div id="separador"></div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <H3>about Andean Exclusive </H3>
                                                            <div class='seperator'></div>
                                                            <p>Andean Exclusive is a team with experience in the tourism
                                                                sector and very committed to our customers. We develop
                                                                vacations made to measure, adapting perfectly to the needs
                                                                and requirements.
                                                                If the priority is comfort, adventure, education, or a
                                                                combination of all of the above, we strive for exceeding all
                                                                expectations. Our style is characterized by kindness,
                                                                honesty, and sensitivity to desires and individual needs.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>

                                <div data-onepage-title="Blog" data-onepage-slug="press"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248495411">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner ">
                                            <div class="wpb_wrapper">
                                                <div class='heading mv8 mvt0 text-center '>
                                                    <div class="row justify">
                                                        <div class="col-lg-6">
                                                            <h3 class="text-center">Our City</h3>
                                                            <p>Cusco, Peru, is a jewel set in a mystical and magical
                                                                setting, which is the heart of our company. Though it is
                                                                described as a Spanish colonial city, the foundations of
                                                                many of the impressive buildings were built by the
                                                                indigenous people over 600 years ago, but also the ancestral
                                                                Inca culture that implies traditions, architecture and
                                                                mysteries of a wonderful civilization. We explored the
                                                                mountains that surround the city, rising another ten to
                                                                fourteen thousand feet. While interacting with international
                                                                tourists during our youth, we were encouraged to share our
                                                                culture. This inspired us to acquire more knowledge of
                                                                Peru’s history and value to the world. Concurrently, we
                                                                developed interest in other nations and cultures, and in
                                                                people motivated to travel hundreds to thousands of miles to
                                                                our homeland. Pride and curiosity were traits developed by
                                                                our interaction with people of other lands and the
                                                                foundation on which we built our company.</p>
                                                        </div>
                                                        <div class="col-lg-6 justify">
                                                            <h3 class="text-center">How we work?</h3>
                                                            <p>Your Peruvian vacation will be unforgettable. “Best trip of
                                                                our lives!” so many of our clients have exclaimed, and the
                                                                high praise has kept us focused on being consistent in
                                                                providing the very best in customer satisfaction. We develop
                                                                tailor-made vacations that perfectly fit your needs and
                                                                requests. Our signature style is characterized by
                                                                amiability, honesty, and sensitivity to individual’s wishes
                                                                and requirements. Further, the “exclusive” in the company’s
                                                                letterhead reflects on the company’s assistance from initial
                                                                contact, throughout the trip, and beyond. We’ve found the
                                                                attention to detail of genuine and invaluable importance to
                                                                our travel customers.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div data-vc-full-width="true" data-vc-full-width-init="false"
                                    data-onepage-title="Hot Tours" data-onepage-slug="hot-tours"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1523936007189 vc_row-has-fill">
                                    <div class="row text-center heading">
                                        <div class="col-lg-6">
                                            <h3>Mision</h3>
                                            <p>Offer a life experience to all people we whom we have the honor to work with
                                                Committed to innovation and the appropriate use of resources, creating a
                                                tourism of learning and sustainable.
                                                Our work is make traveler's dreams come true, bringing them closer to Peru,
                                                their history and reality.
                                            </p>
                                        </div>
                                        <div class="col-lg-6">
                                            <h3>Vision</h3>
                                            <p>Become one of the most important tour operators specialized in the Tourism
                                                Industry.
                                                Developing exceptional standards of satisfaction for our external, internal
                                                customers and collaborators Creating our own school as for the operation and
                                                relationships interpersonal turning us into a model.
                                            </p>
                                        </div>
                                        <div id="divdjm"></div>
                                        <h3>Our Values</h3>
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-10">
                                            <img src="img/valores AET.png">
                                        </div>
                                        <div class="col-lg-1"></div>

                                    </div>
                                </div>
                                <div data-onepage-title="Blog" data-onepage-slug="press"
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1461248495411">
                                    <div class="row">
                                        <h3 class="text-center">OUR STAFF</h3>
                                        <div id="separadordjm"></div>
                                        <div id="separador"> </div>
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Carmen Espejo</h3>
                                            <p><strong>Sales Manager & CoFounder</strong></p>
                                            <p class="justify">
                                                She is licensed in Tourism and a person with many positive qualities, is
                                                responsible and strict with the order of things. She is a leader whom
                                                everyone sees as an example. She loves to enjoy her work, travel with loved
                                                ones, meet new people, and learn new cultures and customs. Her personal
                                                projection with the agency is to grow up everything within the agency as a
                                                family. <br><strong><a id="ablue"
                                                        href="mailto:operacionesandeanexclusivetours.com">carmen.ceo@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/carmen_espejo_miranda.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Walter Medina </h3>
                                            <p><strong>General Manager & CoFounder </strong></p>
                                            <p class="justify">
                                                He is responsible for supervising movements inside and outside the agency,
                                                always looking for news that our passengers can enjoy. He is a trustworthy
                                                person, a responsible father and a dedicated husband. He likes cars and
                                                enjoys good landscapes. He is a proud Cusquenian of his land and his
                                                customs. <br><strong><a id="ablue"
                                                        href="mailto:walter.ceo@andeanexclusivetours.com">walter.ceo@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Walter_medina.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Gabriela Mora</h3>
                                            <p><strong>Behind the scenes</strong></p>
                                            <p class="justify">She is in charge of the company's finance area, her main
                                                function is the payment of suppliers. He studied administration at the local
                                                university. He speaks English fluently. She loves to travel and
                                                dance.<br><strong><a id="ablue"
                                                        href="mailto:guestrelations@andeanexclusive.com">guestrelations@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Gabriela_Mora.png" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Yenny Human </h3>
                                            <p><strong>Accounting </strong></p>
                                            <p class="justify">
                                                Her area is to be an accounting assistant, as a company worker, her
                                                objective is that of a quality service and that the passenger is satisfied
                                                with the services we provide them, and since we always work as a team, it is
                                                common to see our passengers return home happy. <br><strong><a
                                                        id="ablue"
                                                        href="mailto:contabilidad@andeanexclusivetours.com">contabilidad@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Yenny_chauchas.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Luisa Ancaypuro </h3>
                                            <p><strong>Operations</strong></p>
                                            <p class="justify">
                                                She is a very enthusiastic and responsible Tourism Guide, she enjoys doing
                                                her job and likes challenges. She works from the office in charge of doing
                                                all kinds of operations in the agency. She loves reading, singing and
                                                traveling. She wants to consolidate the agency as the best within the
                                                tourist sector providing quality services and give unforgettable experiences
                                                to our passengers. <br><strong><a id="ablue"
                                                        href="mailto:operaciones@andeanexclusivetours.com">operaciones@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Luisa_ancaypuro.jpeg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">David Miranda </h3>
                                            <p><strong>Web designer and positioner.</strong></p>
                                            <p class="justify">
                                                He is a person with fixed aims to his objectives, retailer and willing to
                                                solve problems. He is responsible for advertising & marketing within the
                                                company. Enjoy your work and travel with good company. Think that "Andean
                                                Exclusive" is built on a large base and is an example to project always to
                                                grow more since the agency always invites to be better. <br><strong><a
                                                        id="ablue"
                                                        href="mailto:mirandadjmdjm@gmail.com">sistemas@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/David_jesus_miranda.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Julio Humpire </h3>
                                            <p><strong>Customer Services</strong></p>
                                            <p class="justify">
                                                In the company, its job is to support operations, he also handles reports,
                                                filling in the details of our customers and re-confirmation of reservations.
                                                Finally, support the areas that need it.<br><strong><a id="ablue"
                                                        href="mailto:costumerservice@andeanexclusive.com">costumerservice@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Julio_humpire.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Edith Dueñas </h3>
                                            <p><strong>Reservations</strong></p>
                                            <p class="justify">
                                                Edith has been in tourism for more than 5 years, is an expert person in many
                                                tourist areas. Within our offices she is the office of the reservation area.
                                                It aims to grow with the agency, always keeping our values ahead.
                                                <br><strong><a id="ablue"
                                                        href="mailto:reservas@andeanexclusivetours.com">reservas@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Edith_dueñas.png" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Percy Sánchez </h3>
                                            <p><strong>Organizer and quality supervisor</strong></p>
                                            <p class="justify">
                                                He is our Chef and Guide on high mountain routes. Its quality is a strict
                                                and orderly order to give a quality service. He is a very proactive,
                                                innovative person and likes challenges. He loves to play soccer, cook and
                                                like to enjoy time with her children and pets from home. He wants the agency
                                                to obtain a great prestige of service and quality within the tourism
                                                sector.<br><strong><a id="ablue"
                                                        href="mailto:outdoors@andeanexclusive.com">outdoors@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Percy_sanchez.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Merci Ortiz</h3>
                                            <p><strong>Behind the scenes</strong></p>
                                            <p class="justify">She is our tourist marketing expert. He has been dedicated
                                                to this area for 9 years, attending to each client's requirement. She is
                                                very creative and likes to design, she also speaks English, French and
                                                studies German. She loves dogs, loves to travel and go for a walk breathing
                                                fresh air.<br><strong><a id="ablue"
                                                        href="mailto:guestrelations@andeanexclusive.com">guestrelations@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Merci_ortiz.jpg" id="staffimg">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="vc_row-full-width vc_clearfix"></div>

                    </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
@endsection
