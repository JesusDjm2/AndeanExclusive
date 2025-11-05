@extends('layouts.general-es')
@section('metas')
    <title>Conoce más sobre Andean Exclusive Tours. </title>
    <meta name="keywords"
        content="Cusco tours,Cusco de Lujo, Agencia de Viajes Perú, Peru viajes, Tours en Perú, Tours en Cusco, Agencia de Viajes Cusco." />
    <meta name="description"
        content="Agencia de viajes de lujo en Cusco - Perú. ✅ Disfrute de la magia de de esta increible tierra.">
    <meta property="og:url" content="https://www.andeanexclusive.com/nosotros" />
    <meta property="og:title" content="Conóce más sobre nuestra agencia de viajes: Andean Exclusive Tours.">
    <meta property="og:type" content="webside">
    <meta property="og:image" content="https://www.andeanexclusive.com/img/cusco-de-noche.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://www.andeanexclusive.com/nosotros" />
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
                                    <div class="logo2"></div>
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display" class="menu-has-children">
                                        <a href={{ route('about') }}>
                                            <button type="button" class="btn botondjm">English</button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href={{ route('about') }}>
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
                                    class="vc_row wpb_row vc_row-fluid about vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <div class='swiper-container'>
                                                        <div class='swiper-wrapper'>
                                                            <div class='swiper-slide'>
                                                                <div class='cover-text ph5 text-light text-center pv8 pvb0'>
                                                                    <h2>NOSOTROS</h2>
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
                                                            <H3>Sobre Andean Exclusive</H3>
                                                            <div class='seperator'></div>
                                                            <p>Andean Exclusive es un equipo con experiencia en el sector
                                                                turístico y muy comprometido con nuestros clientes.
                                                                Desarrollamos vacaciones a medida, adaptándonos
                                                                perfectamente a las necesidades y requerimientos. Si la
                                                                prioridad es la comodidad, la aventura, la educación o una
                                                                combinación de todo lo anterior, nos esforzamos por superar
                                                                todas las expectativas. Nuestro estilo se caracteriza por la
                                                                amabilidad, la honestidad y la sensibilidad a los deseos y
                                                                las necesidades individuales.
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
                                                            <h3 class="text-center">Nuestra ciudad</h3>
                                                            <p>Cuzco, Perú, es una joya ambientada en un entorno místico y
                                                                mágico, que es el corazón de nuestra empresa. Aunque se
                                                                describe como una ciudad colonial española, los cimientos de
                                                                muchos de los impresionantes edificios fueron construidos
                                                                por pueblos indígenas hace más de 600 años, pero también la
                                                                ancestral cultura inca que implica tradiciones, arquitectura
                                                                y misterios de una maravillosa civilización. Exploramos las
                                                                montañas que rodean la ciudad, elevándose otros diez a
                                                                catorce mil pies. Mientras interactuamos con los turistas
                                                                internacionales durante nuestra juventud, nos animaron a
                                                                compartir nuestra cultura. Esto nos inspiró a adquirir más
                                                                conocimiento de la historia y el valor del Perú para el
                                                                mundo. Al mismo tiempo, desarrollamos interés en otras
                                                                naciones y culturas, y en personas motivadas para viajar
                                                                cientos de miles de millas a nuestra patria. El orgullo y la
                                                                curiosidad fueron rasgos desarrollados por nuestra
                                                                interacción con personas de otras tierras y los cimientos
                                                                sobre los que construimos nuestra empresa.</p>
                                                        </div>
                                                        <div class="col-lg-6 justify">
                                                            <h3 class="text-center">¿Cómo trabajamos?</h3>
                                                            <p>Tus vacaciones peruanas serán inolvidables. "¡El mejor viaje
                                                                de nuestras vidas!", Han exclamado muchos de nuestros
                                                                clientes, y los grandes elogios nos han mantenido enfocados
                                                                en ser consistentes para proporcionar lo mejor en
                                                                satisfacción del cliente. Desarrollamos vacaciones a medida
                                                                que se adaptan perfectamente a sus necesidades y
                                                                solicitudes. Nuestro estilo característico se caracteriza
                                                                por la amabilidad, la honestidad y la sensibilidad a los
                                                                deseos y requisitos individuales. Además, lo "exclusivo" en
                                                                el membrete de la empresa se refleja en la asistencia de la
                                                                empresa desde el contacto inicial, durante todo el viaje y
                                                                más allá. Hemos encontrado que la atención al detalle tiene
                                                                una importancia genuina e invaluable para nuestros clientes
                                                                de viajes.
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
                                            <h3>Misión</h3>
                                            <p>Ofrecer una experiencia de vida a todas las personas con quienes tenemos el
                                                honor de trabajar Comprometidos con la innovación y el uso adecuado de los
                                                recursos, creando un turismo de aprendizaje y sostenible. Nuestro trabajo es
                                                hacer realidad los sueños de los viajeros, acercándolos a Perú, su historia
                                                y realidad.
                                            </p>
                                        </div>
                                        <div class="col-lg-6">
                                            <h3>Visión</h3>
                                            <p>Conviértase en uno de los operadores turísticos más importantes
                                                especializados en la industria del turismo. Desarrollando estándares
                                                excepcionales de satisfacción para nuestros clientes y colaboradores
                                                externos, internos. Creando nuestra propia escuela en cuanto a la operación
                                                y las relaciones interpersonales, convirtiéndonos en un modelo.
                                            </p>
                                        </div>
                                        <div id="divdjm"></div>
                                        <h3>Nuestros valores</h3>
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
                                        <h3 class="text-center">NUESTRO EQUIPO</h3>
                                        <div id="separadordjm"></div>
                                        <div id="separador"> </div>
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Carmen Espejo</h3>
                                            <p><strong>Gerente de ventas y Co-fundador</strong></p>
                                            <p class="justify">
                                                Tiene licencia en turismo y es una persona con muchas cualidades positivas,
                                                es responsable y estricta con el orden de las cosas. Ella es una líder a
                                                quien todos ven como un ejemplo. Le encanta disfrutar de su trabajo, viajar
                                                con sus seres queridos, conocer gente nueva y aprender nuevas culturas y
                                                costumbres. Su proyección personal con la agencia es hacer crecer todo
                                                dentro de la agencia como una familia. <br><strong><a id="ablue"
                                                        href="mailto:operacionesandeanexclusivetours.com">carmen.ceo@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/carmen_espejo_miranda.jpg" id="staffimg"
                                                alt="Carmen Espejo">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Walter Medina </h3>
                                            <p><strong>Gerente General y Cofundador </strong></p>
                                            <p class="justify">
                                                Es responsable de supervisar los movimientos dentro y fuera de la agencia,
                                                siempre buscando noticias que nuestros pasajeros puedan disfrutar. Es una
                                                persona confiable, un padre responsable y un esposo dedicado. Le gustan los
                                                autos y disfruta de los buenos paisajes. Es un orgulloso cusqueño de su
                                                tierra y sus costumbres. <br><strong><a id="ablue"
                                                        href="mailto:walter.ceo@andeanexclusivetours.com">walter.ceo@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Walter_medina.jpg" id="staffimg" alt="Walter Medina">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Gabriela Mora</h3>
                                            <p><strong>Behind the scenes</strong></p>
                                            <p class="justify">Está a cargo del área de finanzas de la compañía, su función
                                                principal es el pago de proveedores. Estudió administración en la
                                                universidad local. Habla inglés con fluidez. A ella le encanta viajar y
                                                bailar.<br><strong><a id="ablue"
                                                        href="mailto:guestrelations@andeanexclusive.com">guestrelations@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Gabriela_Mora.png" id="staffimg" alt="Gabriela Mora">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Yenny Human </h3>
                                            <p><strong>Contabilidad </strong></p>
                                            <p class="justify">
                                                Su área es ser asistente de contabilidad, como trabajadora de la compañía,
                                                su objetivo es el de un servicio de calidad y que el pasajero esté
                                                satisfecho con los servicios que le brindamos, y dado que siempre trabajamos
                                                en equipo, es común ver nuestro los pasajeros regresan a casa felices.
                                                <br><strong><a id="ablue"
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
                                            <p><strong>Operaciones</strong></p>
                                            <p class="justify">
                                                Ella es una guía de turismo muy entusiasta y responsable, le gusta hacer su
                                                trabajo y le gustan los desafíos. Ella trabaja desde la oficina a cargo de
                                                realizar todo tipo de operaciones en la agencia. Le encanta leer, cantar y
                                                viajar. Quiere consolidar a la agencia como la mejor dentro del sector
                                                turístico brindando servicios de calidad y brindando experiencias
                                                inolvidables a nuestros pasajeros. <br><strong><a id="ablue"
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
                                            <p><strong>Diseñador web y posicionador.</strong></p>
                                            <p class="justify">
                                                Es una persona con objetivos fijos para sus objetivos, minorista y dispuesto
                                                a resolver problemas. Es responsable de publicidad y marketing dentro de la
                                                empresa. Disfruta de tu trabajo y viaja con buena compañía. Piense que
                                                "Exclusivo Andino" está construido sobre una base grande y es un ejemplo
                                                para proyectar siempre crecer más, ya que la agencia siempre invita a ser
                                                mejor. <br><strong><a id="ablue"
                                                        href="mailto:mirandadjmdjm@gmail.com">sistemas@andeanexclusivetours.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/David_jesus_miranda.jpg" id="staffimg"
                                                alt="David Miranda">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div id="lineadjm"></div>
                                    <div class="row">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-7">
                                            <h3 id="blue">Julio Humpire </h3>
                                            <p><strong>Servicios al cliente</strong></p>
                                            <p class="justify">
                                                En la compañía, su trabajo es apoyar las operaciones, también maneja
                                                informes, completa los detalles de nuestros pasajeros y vuelve a confirmar
                                                las reservas. Finalmente, apoye las áreas que lo necesitan.<br><strong><a
                                                        id="ablue"
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
                                            <p><strong>Reservaciones</strong></p>
                                            <p class="justify">
                                                Edith ha estado en el turismo por más de 5 años, es una persona experta en
                                                muchas áreas turísticas. Dentro de nuestras oficinas ella es la oficina del
                                                área de reserva. Su objetivo es crecer con la agencia, manteniendo siempre
                                                nuestros valores por delante. <br><strong><a id="ablue"
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
                                            <p><strong>Organizador y supervisor de calidad.</strong></p>
                                            <p class="justify">
                                                Él es nuestro chef y guía en rutas de alta montaña. Su calidad es un orden
                                                estricto y ordenado para dar un servicio de calidad. Es una persona muy
                                                proactiva e innovadora y le gustan los desafíos. Le encanta jugar fútbol,
                                                cocinar y le gusta pasar tiempo con sus hijos y mascotas desde casa. Quiere
                                                que la agencia obtenga un gran prestigio de servicio y calidad dentro del
                                                sector turístico.<br><strong><a id="ablue"
                                                        href="mailto:outdoors@andeanexclusive.com">outdoors@andeanexclusive.com</a></strong>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 text-center">
                                            <img src="img/staff/Percy_sanchez.jpg" id="staffimg">
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
