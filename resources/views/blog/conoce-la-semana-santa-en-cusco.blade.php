<!DOCTYPE html>
<html lang="es" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>Conoce la festividad de la semana santa en Cusco</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@andeanExclusive">
    <meta name="keywords"
        content="semana santa, semanata santa cusco, semana santa en cusco, cusco semana santa, conoce la semana santa">
    <meta name="twitter:image" content="https://andeanexclusive.com/img/blog/thumb/semana-santa-cusco.jpg">
    <meta name="keywords" content="Corpus christi, festividad en cusco, festejo, costumbres." />
    <meta name="description"
        content="La semana Santa en el cristianismo y año litúrgico, es la previa hacia la pascua, comenzando en el Domingo de Ramos y concluyendo en el domingo de resurr..." />
    <meta property="og:title" content="Conoce la festividad de la semana santa en Cusco">
    <meta property="og:type" content="article">
    <meta property="og:image" content="https://andeanexclusive.com/img/blog/thumb/semana-santa-cusco.jpg" />
    <meta name="author" content="Web Masters DJM2" />
    <link rel="canonical" href="https://andeanexclusive.com/blog/conoce-la-semana-santa-en-cusco">
    @include('layouts.links')
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
                                <a href="{{ route('inicio') }}" class="logo-text-link">
                                    <img src="{{ asset('img/andean-exclusive-logo.png') }}" id="logo">
                                </a>
                            </div>
                            <nav class="main-nav">
                                <ul class="one-page-menu">
                                    @include('layouts.menu-castellano')
                                    <li id="display">
                                        <a href="{{ route('easter-in-cusco') }}">
                                            <button type="button" class="botondjm">English </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children" style="border-style: none">
                                        <a href="{{ route('easter-in-cusco') }}">
                                            <button type="button" class="botondjm">English </button>
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
                <div class="col-lg-6 col-sm-10 text-center"><a href="{{ route('index') }}">Inicio</a> /
                    <a href="{{ route('blog-es') }}">Blog</a> /
                    <a>Semana santa en Cusco</a>
                </div>
                <div class="col-lg-3 col-sm-2">
                    <div class="line"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="sticky-top" style="top:100px; z-index: 40">
                        <h4 class="text-center">Tabla de contenido</h4>
                        <div id="separadordjm2"></div>
                        <ul class="tabla-de-contenido" style="list-style:none;">
                            <li><i class="fa fa-caret-right"></i> <a href="#description">Descripción y actividades</a>
                            </li>
                            <li><i class="fa fa-caret-right"></i> <a href="#history">Historia del señor de los
                                    temblores</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#holyThursday">Jueves santo</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#holyFriday">Viernes santo</a></li>
                            <li><i class="fa fa-caret-right"></i> <a href="#gastronomy">La tradición de los 12
                                    platos</a></li>
                        </ul>
                        <div class="text-center">
                            <button class="boton-info"><a href="#openModal">Solicitar información</a></button>
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
                        <h1 id="titulo1">Semana santa en Cusco</h1>
                        <div id="separadordjm2"></div>
                        <p class="text-center">Escrito por: "David Miranda"</p>
                        <p class="text-center"><i class="fa fa-calendar"></i> 10/04/2022</p>
                    </center>
                    <div class="text-center">
                        <img src="../img/blog/semana-santa-cusco_2022.webp" id="img-blog" width="100%"
                            height="auto">
                        <p>Foto: Señor de los templores</p>
                    </div>
                    <p>
                        La semana Santa en el cristianismo y año litúrgico, es la previa hacia la pascua, comenzando en
                        el Domingo de Ramos y concluyendo en el domingo de Resurrección. La ciudad imperial, Cusco, no
                        es ajena a esta festividad, ya que es una de las más importantes en torno a tradición y fe,
                        características que las personas de la ciudad del Cusco llevan en su mente y persona. A
                        diferencia de otras conmemoraciones la Semana Santa en Cusco tiene como día central el Lunes
                        Santo debido a que en dicha fecha sale en procesión el Patrón Jurado del Cusco, el Señor de los
                        Temblores. El arzobispo de la ciudad ofrece la Misa de Comunión en la Catedral, a partir de las
                        8 am. Se celebran misas cada hora hasta las 12 del mediodía.
                        La Semana Santa en Cusco se celebra entre la segunda quincena de marzo a la primera semana de
                        Abril, la fecha es movible.
                    </p>
                    <div id="description" style="padding-top:70px">
                        <h2>Descripción de actividades en la Semana Santa</h2>
                    </div>
                    <h3>Domingo de Ramos:</h3>
                    <p>
                        El domingo 10 de abril del 2022, La fiesta se inicia con el “Domingo de Ramos”, donde se
                        conmemora la entrada de Jesucristo a Jerusalén, las familias cusqueñas asisten a las diferentes
                        parroquias de la ciudad portando hojas de palma y cruces tejidas de la misma planta, las que se
                        hacen bendecir en las celebraciones Eucarísticas de los distintos templos de la ciudad; para
                        después llevarlas a las casas y colocarlas tras las puertas, como un símbolo de protección para
                        el hogar
                    </p>
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <img src="https://andeanexclusive.com/img/blog/domingo-de-ramos-1.webp" id="img-blog"
                                alt="palm sunday" loading="lazy">
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src=" https://andeanexclusive.com/img/blog/domingo-de-ramos-bendicion.webp"
                                id="img-blog" alt="Palm sunday cusco" loading="lazy">
                        </div>
                        <br>
                        <br>
                    </div>
                    <br>

                    <h3>Lunes Santo:</h3>
                    <p>
                        La semana Santa del <strong>Cusco</strong> como día central el Lunes Santo (11 de
                        abril del 2022), ya que en esta fecha sale en procesión el Patrón jurado del Cusco (Señor de los
                        Temblores). Se pone en manifiesto el sincretismo católico-andino de la población cusqueña. La
                        procesión del Taytacha (denominación en idioma quechua que significa «Papito») congrega
                        alrededor de 80 mil devotos esperando la bendición de la Sagrada Imagen.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/lunes-santo-cusco.webp" id="img-blog" width="100%"
                            alt="Señor de los temblores en procesión" loading="lazy">
                        <p>Foto: Señor de los temblores</p>
                    </div>
                    <div id="history" style="padding-top:70px">
                        <h2>Historia del Señor de los Temblores</h2>
                    </div>
                    <p>La Capital Arqueológica de América y antigua cabeza principal del otrora poderoso Imperio del
                        Tahuantinsuyo celebra la Semana Santa rindiendo culto a la imagen del Señor de los Temblores o
                        <strong>Taytacha Temblores</strong>, en quechua. Según algunas fuentes, la historia de este
                        Cristo de rasgos
                        descarnados y de sobrecogedora apariencia se remonta a cuando el emperador Carlos V envió la
                        efigie a Cusco, hecha especialmente para los indios, copiando las bruscas facciones de éstos.
                        Los españoles buscaban consolidar así la Conquista hecha por la espada e imponer su adoración.
                        Pero fue el 31 de mayo de 1650 cuando se encendió aún más la llamarada de fe del pueblo cusqueño
                        por el Señor de los Temblores. <br><br>
                        Esa tarde, un terremoto de 8 grados Richter azotó la ciudad echando abajo muchas casas y
                        templos. Para aplacar la ira del Señor, la autoridad eclesiástica ordeno que saliera en
                        procesión la Virgen Inmaculada y un cristo que no era muy conocido en la Catedral del Cusco. a
                        eso de las cuatro de la tarde, las dos imágenes iniciaron la procesión recorriendo el perímetro
                        de la <strong>Plaza de Armas</strong>. Las personas con miedo, terror y desesperación decidieron
                        seguir a las dos
                        imágenes. <br><br>
                        Fue entonces en ese momento en el que se aplaco el desastroso terremoto gracias a la imagen del
                        antes conocido Señor de la Buena muerte. Desde aquel entonces fue nominado 'Señor de los
                        Temblores'. cada Lunes Santo, seguidas por una multitud de feligreses que lo acompañan con
                        humilde fervor. En las ventanas de las casas, por donde pasa la efigie, se colocan refinadas
                        piezas de tapicerías aterciopeladas con franjas de oro, telas y alfombras brillantes, que las
                        familias reservan especialmente para esta ocasión. Mientras tanto, los Camaretos o pequeños
                        morteros, petardos y cohetes agitan el ambiente con su estruendo hacen casi inaudibles los
                        cánticos y oraciones de la multitud.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/historia-señor-de-los-temblores.webp" id="img-blog" width="100%"
                            height="auto">
                        <p>Foto: Cristo Moreno "Taytacha Temblores" </p>
                    </div>
                    <h3>Recorrido del señor de los temblores</h3>
                    <p>
                        La Procesión del Señor de los Temblores fue instituida el 31 de marzo de 1650, al recordatorio
                        del terremoto, pero en 1971, la fecha fue movida al día de Lunes Santo. La procesión del Patrono
                        Jurado del Cusco empieza a las 2:00 p.m. recorriendo el perímetro de la Plaza de Armas hasta
                        dirigirse a la Calle Plateros y llegar al Templo de Santa Teresa, en donde se cambia el sudario
                        de la imagen sagrada por uno nuevo. Culminado este proceso, el Taytacha se dirige a la Plaza San
                        Francisco, en donde realiza la primera bendición a el pueblo cusqueño. Terminado esto, la
                        sagrada imagen continua su recorrido hasta la Basílica Menor de la Merced, en donde nuevamente
                        se realiza el cambio de sudario. Acaecida la noche, el patrón Jurado se dirige a la Catedral del
                        Cusco , en donde realiza su última bendición a toda la población cusqueña, llenando a todos de
                        un emotivo y telúrico momento lleno de fe, esperanza y perdón.
                    </p>
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <img src="https://andeanexclusive.com/img/blog/recorrido-calle-señor-de-los-temblores.webp"
                                id="img-blog" alt="lord of tremors tour" loading="lazy">
                        </div>

                        <div class="col-lg-6 text-center">
                            <img src=" https://andeanexclusive.com/img/blog/recorrido-plaza-señor-de-los-temblores.webp"
                                id="img-blog" alt="lord of tremors tour" loading="lazy">
                        </div>
                    </div><br> <br>

                    <h3> Chayñas del Cusco</h3>
                    <p>
                        Las cantoras del Taytacha (Chayñas del Cusco) son mujeres cantoras que cantan durante y después
                        de la procesión del Señor de los Temblores, dichas mujeres poseen un fervor y magia en sus
                        voces, propia de los Andes y del Cusco cantando con sus agudas y delicadas voces cantos en
                        quechua.
                    </p>
                    <div class="row relacionado">
                        <div class="col-lg-4">
                            <a href="https://andeanexclusive.com/blog/inti-raymi-castellano" target="_blanc">
                                <img src="https://andeanexclusive.com/img/blog/thumb/inti-raymi-andean-exclusive.jpg"
                                    alt="Inti raymi Cusco" loading="lazy">
                            </a>
                        </div>
                        <div class="col-lg-8">
                            <div class="similar-entre">
                                <a href="https://andeanexclusive.com/blog/inti-raymi-castellano" target="_blanc">
                                    <p class="p-similar"><strong>Inti Raymi The sun's
                                            festival</strong><br>
                                        Inti Raymi is a Quechua word that means ‘Fiesta del
                                        Sol’. Every winter solstice (between June 20 and 23) the
                                        Inca performed a ritual in honor of the sun, the highest
                                        divinity in the Tahuantinsuyo.
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3> Ñucchu</h3>
                    <p>
                        Flor roja de color intenso, flor carmesí, es una flor oriunda de la región Quechua que crece en
                        alturas solo en los días de Semana Santa y especialmente Lunes Santo. Se dice que la resina de
                        dicha flor es la que provoco el color oscuro del Patrono del Cusco.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/ñucchu-procesion.webp" id="img-blog" width="100%" loading="lazy"
                            alt="Procesion nucchu">
                        <p>Foto: Flores Rojas "Ñucchu" </p>
                    </div>
                    <div id="holyThursday" style="padding-top:70px">
                        <h2>Jueves Santo:</h2>
                    </div>
                    <p>
                        La Semana Santa continúa con el “Jueves Santo”. El jueves 14 de abril del 2022, es el día donde
                        se recuerda la última cena en toda la comunidad cristiana. En la Basílica de la Catedral, el
                        Arzobispo lava los pies de doce ancianos, en memoria del lavado de pies de Jesús a sus
                        apóstoles, mientras las familias se reúnen para disfrutar de los “doce platos” recordando la
                        “Última Cena” (Esta actividad puede variar para jueves o viernes santo según preferencia de cada
                        familia). Aquí se observa la mistura de platos de la cocina andina y occidental, mientras que en
                        la Plaza San Francisco y el Mercado Central se pueden adquirir dulces como los crocantes
                        maicillos, bizcochuelos, suspiros, empanadas y otras delicias de la repostería tradicional
                        cusqueña. Por la noche las familias recorren las calles y las plazas del Centro Histórico en la
                        ruta de los “Siete Templos” que recuerdan las caídas de Jesús en su camino al Calvario.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/jueves-santo-lavado-de-pies-cusco.webp" id="img-blog" width="100%"
                            alt="Jueves santo en cusco" loading="lazy">
                        <p>Foto: Tradicional "Lavado de pies"</p>
                    </div>
                    <div id="holyFriday" style="padding-top:70px">
                        <h2>Viernes Santo:</h2>
                    </div>
                    <h3>Viacrusis:</h3>
                    <p>
                        En el Viernes Santo, Viernes 15 de Abril del 2022 al igual que en Ayacucho, acontece el
                        encuentro de las andas del Cristo en el Santo Sepulcro y de la Virgen Dolorosa. Se realiza el
                        vía crucis desde la Plaza San Francisco hasta la Cruz del Papa que se encuentra en Sacsayhuamán,
                        este evento es organizado por el Convento de San Francisco. Ese día, a diferencia a lo que
                        sucede en otros lugares, no es de abstinencia en el Cusco. La costumbre es degustar doce platos
                        típicos distintos (fecha variable para jueves o viernes santo) que incluyen desde variadas sopas
                        y potajes ya sea a base de pescado seco, trigo y olluco, hasta los deliciosos postres como los
                        dulces de manzana, maíz o choclo.
                    </p>

                    <h3>Hampi Rantikuy (Venta de flores).</h3>
                    <p>
                        Feria de plantas medicinales en donde toda la población cusqueña sale a comprar plantas, espinos
                        en forma de cruz, amuletos, etc. Estos son puestos en las puertas de los hogares cusqueños para
                        protegerse del mal.
                    </p>
                    <div class="text-center">
                        <img src="../img/blog/hampirantikuy-viernes-santo-cusco.webp" id="img-blog" width="100%"
                            alt="Hampirantikuy viernes santo" loading="lazy">
                        <p>Foto: Feria tradicional "Hampirantikuy". </p>
                    </div>
                    <div id="gastronomy" style="padding-top:70px">
                        <h2>La tradición de los 12 platos.</h2>
                    </div>
                    <p>
                        Luego de un ayuno hasta las 12 de la tarde, las familias cusqueñas tienen la tradicional
                        costumbre de degustan de los 12 platos, que se prepara en cada casa de las familias cusqueñas en
                        conmemoración a los 12 apóstoles y la última cena. Los platos más comunes son:
                    </p>
                    <div class="row">
                        <div class="col-lg-6">
                            <p> Salados: </p>
                            <ul>
                                <li> Lawa (crema de maíz) </li>
                                <li> Sopa de lisas</li>
                                <li> Sudado de trucha</li>
                                <li> Saltado de bacalao</li>
                                <li> Pescado frito</li>

                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <p> Dulces: </p>
                            <ul>
                                <li> Guisado de frutas</li>
                                <li> Arroz con leche </li>
                                <li> Mazamorra</li>
                                <li> Empanadas, maicillos</li>
                                <li> Pan torta</li>
                                <li> Chocolate</li>
                            </ul>
                        </div>
                    </div>
                    <div class="text-center">
                        <img src="../img/blog/doce-platos-salados-cusco.webp" id="img-blog" width="100%"
                            alt="Platos tipicos del Cusco" loading="lazy">
                        <i>Foto: 12 platos tradicionales del Cusco.</i>
                    </div>
                    <h3>Procesión de la Virgen Dolorosa y Santo Sepulcro:</h3>
                    <p>
                        A eso de las 5 de la tarde, la imagen de la Virgen Dolorosa y Santo Sepulcro salen en procesión
                        desde La Basílica Menor de la Merced recordando la muerte de Jesucristo. Dicha procesión es
                        acompañada por miles de fieles seguidos por su fe.
                    </p>

                    <h3>Domingo de Resurección</h3>
                    <p>
                        La Semana Santa llega a su fin el Domingo de Resurrección. Domingo 17 de abril del 2022, llega
                        el día en que Jesucristo resucita de la muerte, para ello las familias cusqueñas de dirigen a
                        las parroquias para participar de las homilías. Luego de la procesión y de la celebración de la
                        misa, por las principales calles se percibe el aroma exquisito de los manjares que deleitan a
                        propios y extraños, como el sabroso caldo de gallina, las empanadas, el dulce de maíz blanco,
                        los tamales y las tortas. Así, en forma pagana, se cierra en la milenaria ciudad imperial del
                        Cusco la Semana Santa andina.
                    </p>
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
                        <div class="fb-comments" data-href="https://andeanexclusive.com/blog/conoce-la-semana-santa-en-cusco"
                            data-width="100%" data-numposts="5"></div>

                        <!---------Fin redes sociales------------------------>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3>Temas Similares</h3>
                    <div id="separadordjm2"></div>
                </div><br><br>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('inti-raymi-castellano') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/inti-raymi-andean-exclusive.webp') }}"
                                alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">La fiesta del sol</h5>
                            <p class="card-text">El Inti Raymi es una de las fechas más importantes para la cultura
                                Inca, y en cusco se celebra a lo grande</p>
                            <a href="{{ route('inti-raymi-castellano') }}" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/lineas-de-nasca-colibri.webp') }}"
                                alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Lineas de Nazca</h5>
                            <p class="card-text">Las mas misteriosas y enormes figuras talladas en el desierto de Ica,
                                es una constante pregunta de ¡Para qu'e estan hechos?</p>
                            <a href="#" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem;">
                        <a href="{{ route('dia-de-la-pachamama') }}">
                            <img style="height: 165px; width:100%"
                                src="{{ asset('img/blog/thumb/Pachamama-andean-exclusive.webp') }}"
                                alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">Día de la Madre Tierra</h5>
                            <p class="card-text">Al ser pateísta la religion Inca, esta tenia un día especial para la
                                madre tierra</p>
                            <a href="{{ route('dia-de-la-pachamama') }}" id="botonnuevo">Leer artículo</a>
                        </div>
                    </div>
                </div>
            </div><br><br>
        </div>
    </section>
    @include('layouts.foot-castellano')

</body>

</html>
