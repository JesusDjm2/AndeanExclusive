@extends('layouts.general-en')
@section('metas')
    <title>Frequently asked and questions</title>
    <meta name="keywords" content="days tour, Luxury Cusco, Peru tours, Machu Picchu luxury" />
    <meta name="escription" content="We present you the most popular question about your travel to Perú." />
    <meta property="og:url" content="https://www.andeanexclusive.com/faqs">
    <meta property="og:title" content="Tours en Perú">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://www.andeanexclusive.com/img/faqs.JPG" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/faqs" />
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
                                    <img src="img/andean-exclusive-logo.png" id="logo">
                                </a>
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
                                            <li><a href="preguntas-frecuentes">Español</a></li>
                                            <li><a>Ingles</a></li>
                                        </ul>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="preguntas-frecuentes">Es</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a>En</a>
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
                                    class="vc_row wpb_row vc_row-fluid faqs vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    <h2>FAQS</h2>
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
                                <div id="separador"></div>
                                <div id="separador"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h1 class="text-center">Frequently Asked Questions </h1>
                                        <div id="separadordjm"></div>
                                    </div>
                                    <div id="separador"></div>
                                    <div class="col-lg-12">
                                        <!--Accordion wrapper-->
                                        <div class="accordion md-accordion accordion-1" id="accordionEx23" role="tablist">
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading96">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="white-text font-weight-bold" data-toggle="collapse"
                                                            href="#collapse96" aria-expanded="true"
                                                            aria-controls="collapse96"> <br>
                                                            Do I need a visa to visit Peru?

                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse96" class="collapse show" role="tabpanel"
                                                    aria-labelledby="heading96" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Citizens of most American and Western European countries
                                                                    are not required visa to enter Peru. Citizens of
                                                                    Bolivia, Ecuador and Chile do not require passports or
                                                                    visa to visit certain regions of Peru. Contact the
                                                                    Peruvian diplomatic representative in your country for
                                                                    further information. Addresses and phone numbers are
                                                                    included in the website of Peru's Ministry of Foreign
                                                                    Relations: <strong>www.rree.gob.pe</strong> If the
                                                                    purpose of your visit is tourism, the maximum length of
                                                                    your stay will be 90 days (you can apply for extensions
                                                                    with the immigration authority). All travelers must
                                                                    carry a valid passport, or a safe-conduct issued by the
                                                                    Peruvian immigration authority.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading97">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse97"
                                                            aria-expanded="false" aria-controls="collapse97">
                                                            <br>
                                                            Is it safe to travel in Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse97" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading97" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Reality shows that it is very safe. You may have read or
                                                                    heard on TV, websites or newspapers about terrorism,
                                                                    crime and economic and political instability. But the
                                                                    truth is that terrorism has been defeated a long time
                                                                    ago; Peruvians live in peace and work very hard every
                                                                    day to build and democratic and safe society. We can
                                                                    assure visitors that all they need to do is follow the
                                                                    normal precautions taken in order to visit other
                                                                    destinations.</p>
                                                                <h4>When visiting the cities:</h4>
                                                                <p>
                                                                <ul>
                                                                    <li>Take the logical precautions to avoid pickpockets
                                                                        and purse-snatchers.</li>
                                                                    <li>Carry a copy of your identification documents. Keep
                                                                        the originals and the rest of valuable personal
                                                                        effects in the safety deposit box of your hotel.
                                                                    </li>
                                                                    <li>Wear valuable items discretely; don't carry large
                                                                        sums of cash and watch your bags and luggage.</li>
                                                                    <li>Refrain from exchanging currency on the street.</li>
                                                                    <li>It is advisable to use taxi companies for
                                                                        transportation (they can be requested by phone) or
                                                                        cabs authorized by the municipal authorities (in ima
                                                                        they are painted yellow and carry a bright
                                                                        identification sign on the roof).</li>
                                                                </ul>
                                                                </p>
                                                                <h4>When visiting the cities:</h4>
                                                                <p>
                                                                <ul>
                                                                    <li>Always carry your driver's license, a copy of your
                                                                        passport and the rental agreement if you're driving
                                                                        a rental car. International driver's licenses are
                                                                        valid for one year, while driver's licenses from
                                                                        other countries are valid for 30 days.</li>
                                                                    <li>If a traffic officer signals you to stop, you must
                                                                        stop. Traffic officers must be wearing uniform and
                                                                        carry identification (they are required to wear
                                                                        identification cards including their last name on
                                                                        their chest.) They are not allowed to enter the
                                                                        vehicle.</li>
                                                                    <li>Bear in mind that traffic officers are not allowed
                                                                        retain any personal or vehicle documents. Under no
                                                                        circumstances should you offer or agree to pay money
                                                                        to traffic officers.</li>
                                                                    <li>In case of an accident or collision, call a traffic
                                                                        officer. If your car is a rental, call the
                                                                        representative of the insurance company provided by
                                                                        the rental agency. Do not leave the scene of the
                                                                        accident. Keep the windows closed and the doors
                                                                        locked.</li>
                                                                </ul>
                                                                </p>
                                                                <h4>Prohibitions</h4>
                                                                <p>It is forbidden to take photographs of airports, military
                                                                    bases, police stations and areas surrounding high
                                                                    voltage towers.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading98">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse98"
                                                            aria-expanded="false" aria-controls="collapse98">
                                                            <br>
                                                            Are there services for tourists with special needs in Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse98" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading98" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>In Peru we believe in the idea of "Tourism for
                                                                    Everybody". This is why many public and private
                                                                    organizations have been working for years to ensure that
                                                                    their services are equally accessible for the
                                                                    differently-abled. We advise you to contact your service
                                                                    provider in advance to ensure that your accessibility
                                                                    needs will be met.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading98">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse001"
                                                            aria-expanded="false" aria-controls="collapse98">
                                                            <br>
                                                            How far is Peru from my home?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse001" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading98" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>In order to provide you with useful references we have
                                                                    created a table including the approximate arrival flight
                                                                    times.</p>
                                                                <table class="table table-bordered">
                                                                    <thead style="background: #676767; color: #fff">
                                                                        <tr>
                                                                            <th>Country</th>
                                                                            <th>City</th>
                                                                            <th>Approx. flight time</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>Dallas</td>
                                                                            <td>7 hours 12 minutes</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>Houston</td>
                                                                            <td>6 hours 45 minutes</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>Los Angeles</td>
                                                                            <td>8 hours 35 minutes</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>Miami</td>
                                                                            <td>5 hours 45 minutes</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>Atlanta</td>
                                                                            <td>7 hours</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Argentina</td>
                                                                            <td>Buenos Aires</td>
                                                                            <td>4 hours 15 minutes</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Chile</td>
                                                                            <td>Santiago</td>
                                                                            <td>3 hours 25 minutes</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Spain</td>
                                                                            <td>Madrid</td>
                                                                            <td>11 hours 30 minutes</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <h4>Flights with stopovers </h4>
                                                                <table class="table table-bordered">
                                                                    <thead style="background: #676767; color: #fff">
                                                                        <tr>
                                                                            <th>Country</th>
                                                                            <th>City</th>
                                                                            <th>Approx. flight time</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>New York</td>
                                                                            <td>8 hours 35 minutes / via San José</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>San Francisco</td>
                                                                            <td>10 hours / via Los Angeles</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>United Stated</td>
                                                                            <td>Washinton</td>
                                                                            <td>9 hours / via Newark</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Germany</td>
                                                                            <td>Dusseldorf</td>
                                                                            <td>18 hours 40 minutes / via Madrid</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Germany</td>
                                                                            <td>Frankfurt</td>
                                                                            <td>15 hours 30 minutes / via Madrid</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>France</td>
                                                                            <td>Paris</td>
                                                                            <td>14 hours 50 minutes/ via Miami</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Italy</td>
                                                                            <td>Milan</td>
                                                                            <td>14 hours 50 minutes / via Madrid</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Italy</td>
                                                                            <td>Rome</td>
                                                                            <td>15 hours 10 minutes / via Madrid</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>UK</td>
                                                                            <td>London</td>
                                                                            <td>15 hours 10 minutes / via Madrid</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Japan</td>
                                                                            <td>Tokio</td>
                                                                            <td>20 hours 35 minutes / via Los Angeles</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading10">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse10"
                                                            aria-expanded="false" aria-controls="headin10">
                                                            <br>
                                                            Are there services for tourists with special needs in Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse10" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading10" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>This answer depends on your personal preferences and the
                                                                    level of comfort you desire. Bartering is generally
                                                                    accepted in Peru, with the exception of larger stores
                                                                    and restaurants. In larger cities prices in general may
                                                                    be higher, particularly in Lima, and may increase during
                                                                    festivities such as the National Holidays (July), Easter
                                                                    (April) or Christmas due to higher demand for services
                                                                    such as bus and air transportation. Below is a price
                                                                    list of products and services that you may use as
                                                                    reference in order to prepare a travel budget.
                                                                </p>
                                                                <table class="table table-bordered">
                                                                    <thead style="background: #676767; color: #fff">
                                                                        <tr>
                                                                            <th>Reference product or service</th>
                                                                            <th>US Dollars</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Mineral water bottle 1.5 Lt (51 fl. oz.)
                                                                            </td>
                                                                            <td>1.40</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Medium soda pop 0.50</td>
                                                                            <td>0.50</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Burger </td>
                                                                            <td>1.40</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Apple </td>
                                                                            <td>0.30</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Shampoo bottle </td>
                                                                            <td>4.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Tooth paste </td>
                                                                            <td>1.40</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Lunch (frugal)</td>
                                                                            <td>13.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Lunch (basic)</td>
                                                                            <td>12.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Camera roll </td>
                                                                            <td>4.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Blank video tape </td>
                                                                            <td>8.50</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Pair of sneakers </td>
                                                                            <td>30.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Newspaper</td>
                                                                            <td>0.50</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Public internet booth or cybercafes (1 hour)
                                                                            </td>
                                                                            <td>1.50</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Train Cusco - Machu Picchu (return)</td>
                                                                            <td>30( backpakers) - 70 (Autovagon)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Bus (one way)</td>
                                                                            <td>5.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Flight to Cusco (return) </td>
                                                                            <td>140.00</td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading100">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse100"
                                                            aria-expanded="false" aria-controls="collapse100">
                                                            <br>
                                                            How safe are ATMs in Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse100" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading100" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>In general, using the ATMs here is fine, however we DO
                                                                    NOT recommend using them at night for obvious reasons.
                                                                    We recommend using ATMs of the bigger banks in Peru and
                                                                    not the small ATMs in petrol stations etc. as they tend
                                                                    to have higher charges. The biggest and safest banks in
                                                                    Peru are Scotiabank, Interbank, BCP, Banco de la Nacion,
                                                                    BBVA.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading101">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse101"
                                                            aria-expanded="false" aria-controls="collapse101">
                                                            <br>
                                                            Will I have any problems with altitude in Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse101" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading100" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Generally, it is recommended to give yourself a day or 2
                                                                    to acclimatize before doing any treks at altitude. It is
                                                                    also recommended to take some altitude sickness pills,
                                                                    chew Coca leaves and drink Coca Tea… our guides will
                                                                    assist you with any issues. Arequipa, Puno and Cusco are
                                                                    the 3 places where altitude could affect some travelers.
                                                                    We recommend Arequipa as a place to acclimatize on the
                                                                    way to Cusco and Puno.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading102">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse102"
                                                            aria-expanded="false" aria-controls="collapse102">
                                                            <br>
                                                            Do I need any vaccines for Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse102" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading102" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Vaccines are not necessary for any portion of the Peru.
                                                                    However, if you plan on visiting the Amazon jungle, you
                                                                    may need some vaccines so please consult your local
                                                                    doctor. We recommend getting the Yellow Fever vaccine if
                                                                    you are travelling to Bolivia, as it is a requirement if
                                                                    going to the Bolivian jungle. You do not need it if you
                                                                    are only travelling to La Paz and Uyuni.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading103">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse103"
                                                            aria-expanded="false" aria-controls="collapse103">
                                                            <br>
                                                            Will my mobile phone work in Peru?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse103" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading103" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Phone and internet coverage is quite good in cities and
                                                                    even some rural areas, including Machu Picchu - though
                                                                    there is no phone coverage on the train ride to Machu
                                                                    Picchu. Most foreign smartphones work in the region if
                                                                    you have an international calling and data plan. If your
                                                                    phone is unlocked you can buy a local SIM card that will
                                                                    work for use while traveling.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading104">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse104"
                                                            aria-expanded="false" aria-controls="collapse104">
                                                            <br>
                                                            How much am I meant to tip?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse104" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading104" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Tips are entirely at your discretion. All of our guides
                                                                    and drivers are paid fairly so tipping is not a
                                                                    requirement, though always welcomed. It is best to leave
                                                                    a tip in cash. </p>
                                                                <h4>Tipping in Restaurants:</h4>
                                                                <p>You may tip 10%, but it is totally discretionary. Credit
                                                                    cards and traveler’s cheques are widely accepted in
                                                                    smarter restaurants. Tips are already included in the
                                                                    price if the meal is part of your program.</p>
                                                                <h4>Tipping for guided excursions per day:</h4>
                                                                <p>- For a group of 2 passengers: $20br <br>
                                                                    - For a driver per day for a group of 2 passengers: $10
                                                                    <br>
                                                                    - For a guide per day for a group of 4+ passengers:
                                                                    $20-$40 <br>
                                                                    - For driver per day for a group of 4+ passengers:
                                                                    $10-$20
                                                                </p>
                                                                <h4>Tipping for camping treks per day:</h4>
                                                                <p>- For a guide for group of 2 passengers: $20 <br>
                                                                    - For a cook for group of 2 passengers: $10 <br>
                                                                    - For each member of support staff (porters) for a group
                                                                    of 2 passengers: $5 <br>
                                                                    - For a guide per day for a group of 4+ passengers: $40
                                                                    <br> - For cook per day for a group of 4+passengers: $15
                                                                    <br>
                                                                    - For each member of support staff (porters) for a group
                                                                    of 4+ passengers: $5
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading105">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse105"
                                                            aria-expanded="false" aria-controls="collapse105">
                                                            <br>
                                                            Do I need travel insurance?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse105" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading105" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Travel insurance is not usually a requirement, but we
                                                                    strongly recommend you do have insurance. We will
                                                                    request proof of medical insurance for some specific
                                                                    activities (e.g. lodge-to-lodge treks) that includes
                                                                    evacutation coverage if you're in a remote location. We
                                                                    strongly recommend you travel with insurance whatever
                                                                    your activities, to cover you for flight cancellations,
                                                                    theft, lost luggage and general cancellation by you in
                                                                    the event that you are unable to carry out your trip due
                                                                    to illness or unforeseen circumstances. It should also
                                                                    cover you for potential medical expenses incurred during
                                                                    travel in case of illness or accident. We can also
                                                                    assist you with a travel insurance, let your travel
                                                                    specialist you need one.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading106">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse106"
                                                            aria-expanded="false" aria-controls="collapse106">
                                                            <br>
                                                            What should I pack?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse106" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading106" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Given Peru's diverse geography, you're likely to
                                                                    experience different climates whatever time of year you
                                                                    travel. We strongly recommend brining layers which makes
                                                                    it easier to deal with changing temperatures, especially
                                                                    in the Andes.
                                                                </p>
                                                                <p><strong>Some Suggestions</strong></p>
                                                                <ul>
                                                                    <li>Comfortable sneakers or hiking boots</li>
                                                                    <li>Short and long-sleeved t-shirts</li>
                                                                    <li>Rain gear</li>
                                                                    <li>A fleece jacket</li>
                                                                    <li>Jeans or casual trousers</li>
                                                                    <li>Camera</li>
                                                                    <li>Daypack</li>
                                                                    <li>Insect repellent, lip balm and sun block</li>
                                                                    <li>Binoculars</li>
                                                                    <li>Personal Medicine</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header blue lighten-3 z-depth-1" role="tab"
                                                    id="heading107">
                                                    <h5 class="text-uppercase mb-0 py-1">
                                                        <a class="collapsed font-weight-bold white-text"
                                                            data-toggle="collapse" href="#collapse107"
                                                            aria-expanded="false" aria-controls="collapse107">
                                                            <br>
                                                            What currency do I need to bring?
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapse107" class="collapse" role="tabpanel"
                                                    aria-labelledby="heading107" data-parent="#accordionEx23">
                                                    <div class="card-body">
                                                        <div class="row my-4">
                                                            <div class="col-md-12">
                                                                <p>Peruvian Sol is the currency of Peru. Although US Dollars
                                                                    are often accepted in larger hotels, shops and
                                                                    restaurants in larger cities and tourist destinations
                                                                    like Cusco, they are not always accepted. As such, we
                                                                    recommend you always travel with local currency
                                                                    especially for smaller purchases in shops, markets and
                                                                    local restaurants.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Accordion wrapper-->
                                        </div>
                                    </div>
                                    <div id="separador"></div>
                                </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
