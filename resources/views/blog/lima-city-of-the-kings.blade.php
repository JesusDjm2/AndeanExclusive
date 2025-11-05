<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Lima "The City of the Kings"</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/tours/lima-peru-tour.jpg">
    <meta name="keywords"
        content="get to know Lima, Lima, visit Lima, places to visit in Lima, activities in Lima, visit Lima, tour in Lima, tourism in Lima, Lima museums, Lima parks" />
    <meta name="description"
        content="Learn about the history of the city of Lima, and discover 10 essential places to visit so that you do not miss any detail when traveling to the City of Kings." />
    <meta property="og:url" content="https://andeanexclusive.com/blog/lima-city-of-the-kings">
    <meta property="og:title" content=" Lima 'The city of the Kings'">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/tours/lima-peru-tour.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/blog/lima-city-of-the-kings">
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
                                        <a href="{{ route('lima-ciudad-de-reyes') }}">
                                            <button type="button" class="botondjm">Español </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('lima-ciudad-de-reyes') }}">
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
                    <a>Lima: City of the Kings</a>
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
                            <li><i class="fa fa-caret-right"></i> <a href="#foundation">Foundation and the modern Lima</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#centeroflima">Historical Center of Lima</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#elolivar">Parque El Olivar</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#larco">Larco Museum</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#water">Magical Water Circuit</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#miraflores">Miraflores Boardwalk and paragliding in Lima</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#parqueamor">Park of Love</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#huaca">Huaca Puccllana</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#realfelipe">Real Felipe Fortress</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#palomino">Palomino Islands</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#puntanegra">Punta Negra (Black Tip)</a></li>

                            
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
                        <h1 id="titulo1">Get to know Lima “The city of Kings” </h1>
                        <p class="text-center">Written by: David Miranda</p>
                        <div id="separadordjm2"></div>
                        <i class="fa fa-calendar"></i> 10/05/2021
                    </center>
                    <div id="separador"></div>
                    <div class="text-center">
                        <img src="../img/blog/conoce-lima.webp" id="img-blog" alt="tourism in Lima">
                    </div>
                    <div id="separador"></div>
                    <p>
                        In the 16th century, Lima was the most important and powerful metropolis
                        in Latin America thanks to its location, natural diversity, and cultural
                        richness, currently exhibited in its museums, traditional neighborhoods,
                        and artisan galleries. The Baroque and Renaissance architecture amazes
                        travelers for its colonial-style buildings, highlighting the
                        conservation of its churches, convents, and mansions with elegant
                        traditional balconies.
                    </p>

                    <div id="foundation" style="padding-top:70px">
                        <h4 class="text-center"> <u>Foundation of Lima, the 'City of Kings'</u>
                        </h4>
                        <br>
                        <p>
                            The year 1532. A contingent of Spanish conquerors, led by Francisco
                            Pizarro, defeated Atahualpa, thus conquering the Inca Empire. As the
                            Spanish Crown granted Pizarro the power to appoint himself governor of
                            any city, town, or space he conquered, the Spanish colonizer began the
                            search for what would be his new capital, since they rejected settling
                            in Cusco.

                            <br>

                            The first option then was Jauja, in what we now know as Junín. However,
                            the Spanish explorers Ruy Díaz, Juan Tello, and Alonso Martín de Don
                            Benito dismissed this idea due to its distance from the sea, its Andean
                            altitude, and how rough it could be to get to this point when the
                            weather was not favorable. Thus, these same guides, under the command of
                            Francisco Pizarro, gave an account of an ideal place to settle where to
                            build their new city. It would be the Rimac Valley, with its temperate
                            climate, wood everywhere, spacious fields, endless supplies of water,
                            and proximity to the Pacific Ocean, which made it the perfect area.<br>

                            Thus, with a foundation date of January 18, 1535, this space in the
                            Rímac Valley (pronounced as Limac by the Yungas of the area) became the
                            'City of Kings', the new capital of Peru, which withstood invasions,
                            wars, and rebellions. But that, despite how difficult it was to sustain
                            it over time, it has always remained standing, becoming the most
                            important region of Peru to date.

                            <br>
                        </p>
                        <div class="text-center">
                            <img src="../img/blog/fundacion-lima.webp" id="img-blog" alt="Lima Main square">
                        </div> <br> <br>

                        <h4 class="text-center"> <u>The Modern Lima </u>
                        </h4> <br>
                        <p>Far from the rifles and slingshots of the past, today Lima enjoys
                            stability and attracts many tourists from all over the world. The
                            capital of Peru is rich in culture, history, and gastronomy, and has
                            endless activities to do and attractive destinations to discover. <br>
                            From visiting its historic center, which still houses buildings from the
                            colonial era, museums, and churches, to taking a route to taste its
                            renowned cuisine. <br>In addition, it has an extra attraction as it is
                            the only capital in South America with a view of the sea. This is Lima,
                            magical, emblematic, and, above all, beautiful.</p> <br>
                    </div>

                        <div class="text-center">
                            <img src="../img/blog/lima-moderna.webp" id="img-blog" alt="lima's center">
                        </div> <br> <br>

                        <h3 class="text-center"> <u>In addition, it has an extra attraction as it
                                is the only capital in South America with a view of the sea. This is
                                Lima, magical, emblematic, and, above all, beautiful.</u>
                        </h3> <br>

                        <div id="centeroflima" style="padding-top:70px"><h5>1. HISTORICAL CENTER OF LIMA </h5>
                        <p>
                            One of the main attractions of the oldest cities throughout the world is
                            the visit to its Historic Center. And the one in the majestic city of
                            Lima could not be the exception: in the streets, churches, and
                            buildings, you can leap and visit the past in the first person. As if it
                            were a time machine to which you go to see the old Lima, the one that
                            keeps its colonial touch until today. <br>
                            Touring its historic center, declared a Cultural Heritage of Humanity,
                            is living with the past and the present. Its beautiful squares and
                            monuments; its imposing mansions with colonial and republican balconies;
                            its multiple churches that house priceless works of art; and its
                            restaurants and taverns full of flavor and tradition are some of the
                            options that can be explored on foot. <br>
                            Everyone will agree that the first thing you have to see is the Plaza de
                            Armas and the attractions that surround it: in addition to witnessing
                            one of the most emblematic stalls in the city, adorned with a beautiful
                            pool from the colonial era, you will be able to see the splendor of the
                            Cathedral of Lima. This is one of the countless churches, cloisters, and
                            sanctuaries that are located throughout the center of Lima. <br>

                        </p>
                        <div class="text-center">
                            <img src="../img/blog/centro-historico-lima.webp" id="img-blog" alt="Lima cathedral">
                        </div> <br>

                        <p>Nearby is the Municipality of Lima, which keeps an elegant and fine
                            finish in each of its corridors. On the other hand, you will be able to
                            witness the Government Palace, from where the highest leader of the
                            country rules and in which you can witness the changing of the guard at
                            noon, considered almost a spectacle by tourists.
                            <br>
                            From a long walk through the always crowded Jirón de la Unión -the most
                            important street in the Historic Center- until you reach Chinatown,
                            where you can taste the famous chifa restaurants (Peruvian-Oriental
                            fusion food); to visiting the most impressive museums in the capital,
                            such as the Museum of the Holy Inquisition, without stopping to go
                            through the Catacombs of Lima, to have a chilling and acculturated tour
                            at the same time. <br>
                        </p>

                        <div class="text-center">
                            <img src="../img/blog/centro historico-lima2.webp" id="img-blog"
                                alt="historic center of lima">
                        </div> <br> <br>

                        <h6> <strong> <u>HOW TO GET: </u> </strong></h6>
                        <p> <i> The most important urban transport companies in the country surround
                                the main streets of the center, so getting to it will not be
                                complicated. Likewise, you can transport yourself to the center with
                                a taxi or a transfer application, to feel safer and gain some extra
                                time to walk its streets. </i>
                        </p>
                        </div>

                       <div id="elolivar" style="padding-top:70px"> <h5>2. PARQUE EL OLIVAR (EL OLIVAR PARK) </h5>
                        <p>
                            Parque El Olivar is a beautiful forest-type park in the heart of the
                            financial district of San Isidro, populated with more than 1,500 olive
                            trees and 227 trees of other species.
                            This park dates from the colonial period, a time when houses began to be
                            built around the park, so today we can find mansions that over the years
                            and in a modern Lima, still have the architecture of that time.
                            <br>
                            Due to its antiquity, El Olivar Park has declared a National Monument in
                            1959.<br>

                            A green lung of the city, Parque El Olivar of San Isidro is home to a
                            series of olive trees that are unique in Peru for their longevity. The
                            Park also has cultural centers, museums, libraries, a theater, and an
                            art gallery. <br>
                            This park populated with olive trees is very popular among Limeños who
                            come on weekends to enjoy the calm and greenery of the place, ideal for
                            a picnic. A few blocks away, on Esquilache street, you will find a
                            Vivanda supermarket where you can buy everything you need for a meal in
                            the park.

                            <br>
                        </p>

                        <div class="text-center">
                            <img src="../img/blog/Parque-El-Olivar-de-San-Isidro.webp" id="img-blog"
                                alt="Parque el Olivar - el olivar park">
                        </div>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>

                                To get there, from Arequipa Avenue you must head east along
                                Pallardeli, where you will reach the center of the park. The most
                                recommended option for comfort and safety is by taxi. </i>
                        </p>
                       </div>

                       <div id="larco" style="padding-top:70px"> <h5>3. LARCO MUSEUM</h5>
                        <p>
                            Peru has a gigantic pre-Columbian history that you should know when you
                            visit Lima. One of the best places to absorb that culture is by visiting
                            the Larco Museum.<br>
                            Surrounded by beautiful gardens, an 18th-century viceregal mansion
                            houses an impressive private collection of more than 40,000 pieces of
                            art from pre-Columbian Peru. dating up to 5 thousand years old.
                            Within the permanent exhibition of the museum, the Gold and Jewelry Room
                            stands out with wonderful pieces made by virtuous goldsmiths of ancient
                            Peru to honor their gods. No less attention is captured by the erotic
                            huacos room, which shows us his interesting vision of sexuality.


                            <br>
                            Visiting the Larco Museum is connecting and reconnecting with the Andean
                            worldview. <br>
                        </p>

                        <div class="text-center">
                            <img src="../img/blog/museo-larco.webp" id="img-blog" alt="Larco museum Lima">
                        </div>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                We recommend using a tour to visit the museum as it is not a
                                very touristy area.
                                The service of a taxi could also be convenient, anticipate the
                                return
                                at the same time. The journey from Miraflores can take between 15
                                and 30 minutes.
                                <strong> Opening Hours: </strong> Monday to Sunday from 11:00 a.m.
                                to 7:00 p.m.
                            </i>
                        </p>
                       </div>
                        <div id="water" style="padding-top:70px"><h5>4. MAGICAL WATER CIRCUIT </h5>
                        <p>
                            The Magic Water Circuit is a park in Lima where you can enjoy a night
                            show of water fountains and lights.
                            It is located in the Parque la Reserva and is also known as the "Parque
                            de las Aguas", being one of the must-do activities in Lima.
                            <br>
                            It was inaugurated in 2007 and in 2009 it was awarded the Guinness
                            record for being the tallest fountain complex in the world in a public
                            park.<br>
                            The Magic Water Circuit is an amazing journey through 13 ornamental
                            fountains that are electronically controlled and together with a set of
                            synchronized lights they offer a visual spectacle.
                            The most impressive are the projections of dancers in motion in a giant
                            cloud of water that forms in the main fountain of the Parque de las
                            Aguas.
                            <br>
                            People can enter some of the fountains and interact with the water jets.
                            Especially in one of the fountains that form a long pool of water and
                            that can be crossed by visitors.
                            <br>
                        </p>

                        <div class="text-center">
                            <img src="../img/blog/circuito-del-agua.webp" id="img-blog" alt="MAGICAL WATER CIRCUIT">
                        </div>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                - By metropolitan bus to the Estadio Central station. Another option
                                is using the Integrated Transportation System that runs along
                                Arequipa Avenue, to the Ramón Dagnino station on the entire front of
                                the park. <br>
                                · We also recommend using a taxi to get to the Parque de la Reserva.
                                It is safer especially if you visit it at night.<br>
                                · Finally, the best option is to take a tour that includes a tour of
                                the magical water circuit.

                                <strong> Opening hours: </strong> The Magic Water Circuit is open
                                from Tuesday to Sunday from 3:00 p.m. to 10:30 p.m. Even on
                                holidays.
                                However, the water shows start at 7:15 p.m., 8:15 p.m., and 9:30
                                p.m. </i>
                        </p>
                        </div>

                       <div id="miraflores" style="padding-top:70px"> <h5>5. EL MALECÓN DE MIRAFLORES (THE MIRAFLORES BOARDWALK)</h5>
                        <p>

                            The Malecón de Miraflores is a park with paths that runs along the upper
                            edge of the cliff of the Costa Verde that belongs to the district of
                            Miraflores. The view of the Pacific Ocean from the Malecón de Miraflores
                            is beyond dispute, it is beautiful. For this reason, it is one of the
                            things that you cannot stop doing in Lima.
                            <br>
                            And not only because of its view but also because of its abundant green
                            spaces, this boardwalk is undoubtedly one of the most beautiful areas of
                            Miraflores and also of all of Lima, we guarantee it. There are 5
                            kilometers in total that make up the route of the Malecón de Miraflores,
                            which is divided into 3 sections.<br>
                            Along the boardwalk are parks, flower gardens, sports fields, exercise
                            stations, and monuments. Everything so perfectly cared for that makes
                            you want not to leave. In summer, people take the opportunity to have a
                            picnic or lie down on the grass enjoying the sun and the paragliders
                            that abound in the place.<br>
                            You cannot miss the sunset from any point of the Malecón de la Marina,
                            from where you will have an enviable view of the Costa Verde and
                            especially the Morro Solar de Chorrillos.<br>
                            Being on the Malecón you cannot miss the famous 'Faro de la Marina', 21
                            meters high and with a range of 45 kilometers, which was built in 1900.
                            On one side of the road, along the entire length of the boardwalk, there
                            is a bike path that you can use if you want to do the bike tour, which
                            can be rented at Larcomar. <br> Get your most comfortable clothes and
                            shoes ready and get ready for a unique ride on this beautiful Malecón de
                            Miraflores.<br>
                        </p>

                        <div class="text-center">
                            <img src="../img/blog/malecon-miraflores.webp" id="img-blog" alt=" Miraflores lima">
                        </div>
                        <br>
                        <h4 class="text-center"> <u>The experience of paragliding in Lima</u>
                        </h4> <br>
                        <p>
                            Exactly in the Antonio Raimondi Park, you will see take off and land the
                            paragliders that fly over the entire Miraflores boardwalk.
                            For its cost, it is an activity that you will not want to miss, plus you
                            will have the opportunity to see Lima from a unique angle.
                            Paragliding tours in Lima last 10 minutes and start every day at 10 am
                            as long as there are the necessary wind and visibility conditions.

                        </p>
                        <div class="text-center">
                            <img src="../img/blog/vuelo-parapente.webp" id="img-blog"
                                alt="Hatun Rumiyoc - Piedra de los 12 ángulos - Cusco">
                        </div> <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                Using the Integrated Transportation System that runs along Larco
                                Avenue to the stop in front of 'Larcomar'. <br>
                                The most recommended option is by taxi. You will have to tell the
                                driver which level of the boardwalk you want to go to.
                            </i>
                        </p>
                       </div>
                        <div id="parqueamor" style="padding-top:70px"><h5>6. EL PARQUE DEL AMOR (THE PARK OF LOVE)</h5>
                        <p>
                            Parque del Amor is one of the parks in Lima with the most visits from
                            tourists and locals, due to its history and the spectacular view of the
                            sea. Inaugurated in honor of Valentine's Day, the Parque del Amor is
                            known as a product of the custom of lovers to enjoy the sunset from
                            there. <br>
                            The parks of Lima are characterized by their greenery, especially in
                            Miraflores, this park is one of the essential places in your visit to
                            the city. Enjoy the spectacular view that Parque del Amor offers towards
                            the Pacific Ocean, sitting on some benches with ceramic mosaics of
                            various colors with various messages. The park welcomes you with a
                            flower garden in the shape of hearts. Although the park is not very
                            large, it has places to sit and enjoy the sea views.<br>
                            In the center of the park, there is a sculpture called "the kiss" made
                            by the artist Víctor Delfín, in it two people kissing, being the main
                            icon of the place. Many photographs associate the boardwalks of
                            Miraflores with the Parque del Amor, and a place is truly a photo place,
                            so prepare your camera to have your memories there. <br> Miraflores is
                            one of the safest districts in Lima and is preferred by tourists, so you
                            can safely enjoy your visit to the park.<br>
                            It is recommended to visit it on tour because you will not spend much
                            time there and you can take advantage of the time to see more places in
                            the city.
                            <br>
                        </p>

                        <div class="text-center">
                            <img src="../img/blog/parque-del-amor.webp" id="img-blog" alt="Parque del amor - Lima">
                        </div> <br>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                Located in a section of the Malecón de Miraflores. The safest and
                                recommended thing is to access a taxi or visit it on a tourist tour.
                            </i> <br>
                        </p>
                        </div>
                       <div id="huaca" style="padding-top:70px"> <h5>7. HUACA PUCLLANA </h5>
                        <p>
                            Following in line with the pre-Columbian past of Peru, one of the most
                            recommended tourist places in Lima is the Huaca Pucllana. Belonging to
                            the Lima culture, it dates from the period of regional developments (200
                            – 700 AD). The archaeological complex has an installation of LED lights
                            that make your visit at night even more spectacular. <br>
                            The Huaca Pucllana is an archaeological site located in the district of
                            Miraflores.<br>
                            These vestiges belonging between the years 400 and 1400 of the Lima,
                            Wari, and Ychsma pre-Inca cultures, were abandoned and not valued for a
                            long time until 1967 when this mythical site was recovered.
                            In 1984 the Huaca Pucllana is officially inaugurated and in 1987 it was
                            declared an intangible archaeological zone by the Peruvian state. One of
                            the visits that we recommend doing during your trip to Lima.
                            <br>
                            The guided tours begin by exploring the lower parts of the pyramid where
                            vessels and vestiges of ancient squares of the ancient settlers of Lima
                            can be seen.<br>
                            In some parts of the tour, a model shows, beyond the limits of the
                            current archaeological site, a representation of spaces and the way how
                            the Lima culture worked.<br>
                            Part of the tour shows us the typical flora and fauna of the
                            pre-Hispanic era, where you can see the friendly llamas.
                            The main attraction is the 7-level truncated pyramid, which was built
                            with adobes, rectangular mud bricks dried in the sun. At the top of the
                            pyramid, it is interesting to see the contrast of the ruins with the
                            modern buildings and houses in the streets surrounding the
                            archaeological site.
                            <br>

                        <div class="text-center">
                            <img src="../img/blog/huaca-pucllana.webp" id="img-blog" alt="Huaca Pucllana - Lima">
                        </div>
                        </p>
                        <h4 class="text-center"> <u>Huaca Pucllana Restaurant</u>
                        </h4> <br>
                        <p>Within the archaeological site is the Huaca Pucllana Restaurant with its
                            chef Marilú Madueño and her proposal of Peruvian food with a modern
                            style. It is without a doubt a different experience to try these modern
                            versions of typical Peruvian cuisine while admiring the ruins of the
                            ancient inhabitants of Lima.<br>
                            The Huaca Pucllana Restaurant is open every day from 12:30 p.m. to 4:00
                            p.m. and from 7:00 p.m. to 12:00 a.m. where we recommend you taste some
                            of its specialties such as: <br>
                            · "Arequipeña-style stuffed rocoto pepper"
                            · Dessert perhaps the “lucuma mousse, crispy red quinoa, and double
                            chocolate”.
                            <br>

                        </p>
                       </div>
                        <div class=row>
                            <div class="col-lg-6 text-center">
                                <img style="margin-bottom: 1em"
                                    src="{{ asset('img/blog/restaurante-huaca-pucllana.webp') }}" id="img-blog"
                                    alt="Huaca Pucllana restaurante" loading="lazy">
                            </div>

                            <div class="col-lg-6 text-center">
                                <img src=" {{ asset('img/blog/comida-huaca-pucllana.webp') }}" id="img-blog"
                                    alt="Restaurante Huaca Pucllana" loading="lazy">
                            </div>
                            <br>
                            <br>
                        </div>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                · By metropolitan bus to Angamos station. When leaving the station,
                                it is necessary to walk 7 blocks along Angamos Avenue heading west
                                until you reach General Borgoño Street where you must cross to the
                                right. You will immediately see the truncated pyramid.<br>
                                · In the same way, it is recommended to arrive through a taxi
                                service bound for this place.
                                <br>

                                <strong> Opening hours: </strong> Daytime visit: Wednesday to Monday
                                from 9 am to 5 pm. Night visit: From Wednesday to Sunday from 7:00
                                p.m. to 10:00 p.m. (During the night visit you will not be able to
                                reach the highest level of the pyramid for security reasons).

                            </i>

                        <div id="realfelipe" style="padding-top:70px"><h5>8. REAL FELIPE FORTRESS (FORTALEZA DEL REAL SAN FELIPE)</h5>
                        <p>
                            The Real Felipe Fortress is one of the best tourist attractions in
                            Callao, which is also known as the Army Museum. The construction of this
                            military building culminated in 1775 and was carried out at the request
                            of Viceroy José Antonio Manso de Velasco to avoid pirate attacks, taking
                            advantage of the visit of the Frenchman Luis Godin and other Spanish
                            experts. Today it is one of the most visited tourist places near
                            Lima.<br>
                            It was built in stone and calicanto (a mixture of sand, lime, and guano
                            bird egg white), occupying an area of approximately 7 hectares. Its
                            shape is pentagonal, and at each point of the pentagon, there is a
                            bastion.<br>
                            The guided visits last approximately 120 minutes and they explain all
                            the points of interest located in each of the 5 bastions of the Real
                            Felipe Fortress.<br>
                            <strong>The first stop</strong> is at the artillery park. A courtyard
                            with a sample of cannons that the army used on different occasions.
                            There is also the armored car park with an exhibition of war tanks. <br>
                            <strong>The second attraction </strong> is the Casa del Gobernador, a
                            museum with exhibits of pieces and military uniforms from different
                            eras. Formerly it was the place of residence of the military chief of
                            the fortress.<br>
                            The tour continues with a look at the partial replica of the House of
                            Response (house of historical relevance located in Arica), accompanied
                            by the monument to the unknown soldier, which honors the fallen
                            military. <br>
                            <strong>Follow the Torreón de la Reina</strong>, which cannot be
                            accessed and you have to admire it from the outside. This place served
                            as an ammunition depot and also as dungeons for prisoners.<br>
                            And finally, you reach the <strong>Torreón del Rey</strong>, which has 3
                            levels, which can be accessed. The interior of this tower served as a
                            bunker with narrow corridors that give the impression of being a
                            labyrinth. <br> In the upper part, there are 2 levels where there are
                            platforms with ancient cannons. The views of the coast are very
                            beautiful.
                        </p>
                        <br>

                        <div class="text-center">
                            <img src="../img/blog/real-felipe.webp" id="img-blog"
                                alt=" REAL FELIPE FORTRESS  - Lima - Callao">
                        </div>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                It is not easy to get from Lima to this military museum. So we
                                recommend taking a tour that includes this attraction. The most
                                recognized tour companies are located in Kennedy Park where you can
                                buy your ticket.

                                <strong> Opening Hours: </strong> Tuesday to Sunday from 9 a.m. to 4
                                p.m.

                            </i>
                        </p>
                        </div>
                       <div id="palomino" style="padding-top:70px"> <h5>9. PALOMINO ISLANDS</h5>
                        <p>
                            One of the most popular tourist places in Lima for those who want a
                            direct experience with the marine fauna of the capital. The Palomino
                            Islands, also called Islotes Palominos, are a group of small islands
                            that are home to a large population of sea lions and seabirds.<br>
                            This group of islands is full of stories, such as San Lorenzo Island,
                            which is known as the mysterious island because it is believed that
                            there were pirates and their treasures. <br> There is also Frontón
                            Island, which in the colony was a political prison for which it earned
                            the name Dead Island.<br>
                            A few minutes from Lima, Palominos Island becomes a highly recommended
                            option as a tourist place near the city, especially for nature
                            lovers.<br>

                            · Palominos Island is home to sea lions, which inhabit the coasts of
                            South America, very characteristic for their reddish mane and for having
                            a life of up to 50 years.<br>
                            · Live the experience of swimming with these animals from Palominos
                            Island.<br>
                            Also, enjoy the landscape that this island offers since it is worthy of
                            very good photographs <br>
                            · The neighboring island is called Cavinzas, where old sea lions take
                            refuge, but it is also home to a variety of guano birds such as
                            pelicans.
                            <br>

                        </p>
                        <br>

                        <div class="text-center">
                            <img src="../img/blog/islas-palomino.webp" id="img-blog" alt="palomino islands- Lima">
                        </div>
                        <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                To get to the Palomino Islands, it is enough to set sail from the
                                Punta in Callao, and in approximately 45 minutes you are already
                                next to the sea lions, the walk there is usually 4 hours.<br>
                                Walk that is recommended to do in the summer of Lima, since the
                                temperature of the sea is not so low for many tourists, although
                                tour operators usually supply specialized suits to swim without a
                                problem.
                            </i> <br>
                        </p>
                       </div>
                        <div id="puntanegra" style="padding-top:70px"><h5>10. PUNTA NEGRA (BLACK TIP)</h5>
                        <p>

                            Located south of Lima, Punta Negra is a resort that has a series of
                            beautiful beaches. A spectacular destination if you are interested in
                            surfing since its waves are among the best in the district. <br> If you
                            are looking for a nature retreat, Punta Negra beach has huge, dark rocks
                            that offer a particularly beautiful landscape at sunset.<br>
                            Located exactly between San Bartolo and Punta Hermosa, this district is
                            mainly known for Punta Rocas where it is usual to practice national and
                            international surfing and bodyboarding championships. <br> These sports
                            competitions are regularly held here and it has an excellent hotel and
                            commercial infrastructure specialized in these practices.<br>
                            The Pocitas is also famous for being suitable for children. These are
                            formations made of stones that simulate small pools.
                        </p>
                        <br>

                        <div class="text-center">
                            <img src="../img/blog/punta-negra.webp" id="img-blog" alt="Punta Negra beach -lima">
                        </div> <br>
                        <h6> <strong> <u>HOW TO GET </u> </strong></h6>
                        <p> <i>
                                From Lima travel along the old Panamericana Sur until kilometer 47.
                                There is a public transport service with a route to the south of
                                Lima, the journey time is 1 hour or saves time in a private vehicle,
                                whose trip is approximately 45 minutes.
                            </i> <br> <br>
                        </p>
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
                            <div class="fb-comments"
                                data-href="https://andeanexclusive.com/blog/lima-city-of-the-kings" data-width="100%"
                                data-numposts="5"></div>

                            <!---------Fin redes sociales------------------------>
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
