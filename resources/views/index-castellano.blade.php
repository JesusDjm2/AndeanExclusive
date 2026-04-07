@extends('layouts.general-es')
@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'home', 'og_image' => '/img/machupicchu.png'])
@endsection
@section('contenido')
    <div class="wrapper">
        <header id="header">
            <div class="container headeren">
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
                                    <li id="display">
                                        <a href="{{ route('index') }}">
                                            <button type="button" class="btn botondjm">
                                                <i class="fa fa-language"></i> English
                                            </button>
                                        </a>
                                    </li>
                                    <li id="wasanum" class='menu-item'><a href='https://bit.ly/3kYXpXr'
                                            target="_blank">+51 979 721 194</a></li>
                                    <li id="display2" class="menu-has-children">
                                        <a href="{{ route('inicio') }}">ES</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="{{ route('index') }}">EN</a>
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
                                    class="vc_row wpb_row vc_row-fluid fondoindex vc_row-has-fill vc_row-o-full-height vc_row-o-columns-middle vc_row-o-content-middle vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner vc_custom_1461317698190">
                                            <div class="wpb_wrapper">
                                                <div class='carousel-headings '>
                                                    <h2 class="text-center" style="color: #fff">Book Now!</h2>
                                                    <p class="text-center" style="color: #fff; font-weight: 800">Find
                                                        the availability of your travel date.</p>
                                                    @if (session()->has('flash'))
                                                        <center>
                                                            <div class="alert alert-success" id="alerta"
                                                                style="width: 60%;">
                                                                {{ session()->get('flash') }}<button type="button"
                                                                    onclick="bye()" style="float: right;">X</button>
                                                            </div>
                                                        </center>
                                                    @endif
                                                    <form class="form" action="{{ route('bookindex') }}" method="post">
                                                        @csrf
                                                        <div class="col-lg-12">
                                                            <div class="col-lg-2">
                                                                <input class="form-control" type="text" name="da"
                                                                    placeholder="Date Arrive" onclick="ocultarError();"
                                                                    onfocus="(this.type='date')"
                                                                    onblur="(this.type='text')">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input class="form-control" type="text" name="do"
                                                                    placeholder="Date Out" onclick="ocultarError();"
                                                                    onfocus="(this.type='date')"
                                                                    onblur="(this.type='text')">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input type="number" class="form-control" id="adults"
                                                                    placeholder="Adults" required name="adultos">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input type="number" class="form-control" id="children"
                                                                    placeholder="childrens" name="child">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input type="email" class="form-control" id="email"
                                                                    placeholder="email@peru.com" required name="email">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                {{-- <textarea class="form-control" id="mensaje" name="mensaje" rows="3" style="height: 20px"></textarea> --}}
                                                                <input type="textarea" class="form-control"
                                                                    id="text" name="mensaje" placeholder="Message">
                                                            </div><br>
                                                        </div>
                                                        <div class="col-lg-12" style="padding-top: 10px">
                                                            <div class="col-lg-4"></div>
                                                            <div class="col-lg-4">
                                                                <input id="botonindex2" type="submit" name="submit"
                                                                    value="Send" />
                                                            </div>
                                                            <div class="col-lg-4"></div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row-full-width vc_clearfix"></div>
                                <div class="container" style="padding-top: 3em; padding-bottom:5em">
                                    <div class="row">
                                        <div class="col-lg-12" style="padding-bottom: 3em">
                                            <div class='heading  text-center '>
                                                <h3>Best sellers</h3>
                                                <div class='seperator'></div>
                                                <div class='desc'>
                                                    <p class="text-center">Enjoy the best tours, trips and vacation
                                                        packages in Peru
                                                        <br /> We have the most luxury & exclusive tours for you!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>  
                                        @foreach ($tours as $tour)
                                            <div class="col-lg-6">
                                                <div class='swiper-slide'>
                                                    <div class='travel-item'>
                                                        <div class='entry-img'>
                                                            <a href='{{ route('estour.show', $tour->slug) }}' class='entry-link'>
                                                                <img src="{{ asset($tour->imgThumb) }}"
                                                                    class="attachment-post-grid-s size-post-grid-s"
                                                                    alt="{{ $tour->nombre }}" loading="lazy" style="height: 245px!important"/>
                                                            </a>
                                                        </div>
                                                        <div class='entry-info'>
                                                            <div class='readmore'>
                                                                <h3>{{$tour->nombre}}</h3>
                                                                <h5 class="justify">
                                                                    {{$tour->descripcionCorta}}
                                                                </h5>
                                                                <div
                                                                    style="width: 100%; padding-top:18px; padding-bottom:40px">
                                                                    <span style="float: left"><i
                                                                            class="fa fa-clock-o"></i>
                                                                        {{$tour->dias}} days</span>
                                                                    <span style="float: right"><i
                                                                            class="fa fa-usd"></i>
                                                                        {{$tour->precio}}.00</span>
                                                                </div>
                                                                <a href="{{ route('estour.show', $tour->slug) }}" class='button'>More
                                                                    Info</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="contenedor">
        <div class="cardAndean">
            <img src="../img/index/Belmond-hotel-Nazarenas.webp" alt="Hotel Belmond nazarenas" loading="lazy">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Cusco</h3>
                <p class="card-p">Belmond Hotel Nazarenas</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
        <div class="cardAndean">
            <img src="../img/index/Belmond-Hotel-Sol-y-Luna.webp" alt="Hotel Sol y Luna Cusco" loading="lazy">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Sacred Valley</h3>
                <p class="card-p">Sol & Luna Hotel</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
        <div class="cardAndean">
            <img src="../img/index/Belmond-sanctuary-lodge-peru.webp" alt="Hotel Belmond Santuary Lodge" loading="lazy">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Machu Picchu</h3>
                <p class="card-p">Santuary Lodge Hotel</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
    </div>
    <div class="contenedor">
        <div class="cardAndean">
            <img src="../img/index/Miraflores-park-hotel-peru.webp" alt="Hotel Miraflores Park" loading="lazy">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Lima</h3>
                <p class="card-p">Miraflores Park Hotel</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
        <div class="cardAndean">
            <img src="../img/index/Hotel-titilaka-puno.webp" alt="Hotel Titilaka Puno" loading="lazy">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Puno</h3>
                <p class="card-p">Titilaka Hotel</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
        <div class="cardAndean">
            <img src="../img/index/inkaterra-reserva-amazonica.webp" alt="Inakterra Reserva Amazonica" loading="lazy">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Puerto Maldonado</h3>
                <p class="card-p">Reserva Amazonas Hotel</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
    </div>
    <div class="contenedor">
        <div class="cardAndean">
            <img src="../img/index/junior-suite-hotel-b-lima.webp" alt="">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Lima</h3>
                <p class="card-p">B Hotel</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
        <div class="cardAndean">
            <img src="../img/index/per_cusco-casa-cartagena.webp" alt="">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Cusco</h3>
                <p class="card-p">Casa Cartagena</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
        <div class="cardAndean">
            <img src="../img/index/hotelsdotcom-hacienda-inkaterra.webp" alt="">
            <div class="card-contentido">
                <h3 class="card-titulo"><span class="fa fa-map-marker spanHotel"></span> Sacred Valley</h3>
                <p class="card-p">Casona Inkaterra</p>
                <p class="starts">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star"></i><i class="fa fa-star"></i>
                </p>
            </div>
        </div>
    </div>

    <section style="margin-top: 3em">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class='heading  text-center' style="padding-bottom: 2em">
                        <h1 style="font-family: 'Dancing Script', cursive;">Popular Destinations in Perú</h1>
                        <div class='seperator'></div>
                    </div>
                </div>
                <div class="row espacio">
                    @foreach ($tours2 as $tour)
                    <div class="col-md-4 mb-3">
                        <div class="card" style="padding-bottom: 35px">
                            <a href='{{ route('estour.show', $tour->slug) }}'>
                                <img class="img-fluid" alt="{{$tour->nombre}}"
                                    src="{{ asset($tour->imgThumb) }}" loading="lazy" style="width:100%;height: 200px; objet-fit:cover">
                            </a>
                            <div class="card-body">
                                <div class='entry-info'>
                                    <h4>{{$tour->nombre}}</h4>
                                    <p>{{$tour->descripcionCorta}}</p>
                                    <div style="width: 100%; padding-top:5px; padding-bottom:42px">
                                        <span style="float: left"><i class="fa fa-clock-o"></i>
                                            {{$tour->dias}} days</span>
                                        <span style="float: right"><i class="fa fa-dollar"></i>
                                            {{$tour->precio}}.00</span>
                                    </div>
                                    <a href='{{ route('estour.show', $tour->slug) }}' id='botonnuevo'>Read
                                        more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="vc_row-full-width vc_clearfix"></div>
    <div class="row text-center">
        <div class='heading  text-center'>
            <h3>Our Partners</h3>
            <div id="separadordjm"></div>
        </div id="separadordjm">
    </div>

    <div class="row text-center" id="divdjm">
        <div class="col-lg-1"></div>
        <div class="col-lg-2">
            <img src="img/logos/aatc.webp" loading="lazy" id="filtro" alt="aatc cusco">
        </div>
        <div class="col-lg-2">
            <img src="img/logos/marca_peru.webp" loading="lazy" id="filtro" alt="Marca Perú ">
        </div>
        <div class="col-lg-2">
            <img src="img/logos/regenera2.webp" loading="lazy" id="filtro" alt="Regenera Cusco Perú">
        </div>
        <div class="col-lg-2">
            <img src="img/logos/tripadvisor.webp" loading="lazy" id="filtro" alt="Trip Advisor">
        </div>
        <div class="col-lg-2">
            <img src="img/logos/PeruRail.webp" loading="lazy" id="filtro" alt="Peru Rail">
        </div>
        <div class="col-lg-1"></div>
    </div>
    <div class="contenedorBlogs row">
        @foreach ($blogs as $blog)
            <div class="imagen col-lg-3">
                <img src="{{ asset('img/thumb/' . $blog->imgThumb) }}" alt="{{ $blog->nombre }}" loading="lazy">
                <div class="cardBlog">
                    <h3>{{ $blog->nombre }}</h3>
                    <p>{{ $blog->descripcionCorta }} </p>
                    <div class="btn-container">
                        <a href="{{ route('enblog.show', $blog->slug) }}" target="_blank" class="btn">Read blog</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div data-vc-full-width="true" data-vc-full-width-init="false"
        class="vc_row wpb_row vc_row-fluid vc_custom_1461246190555 vc_row-has-fill">
        <div class='heading  text-center '>
            <h3 style="color:#fff">Guest review</h3>
            <div class='desc'>
                <p style="color: #fff" class="text-center">We are really responsible for what your are
                    thought and what
                    your
                    feeling now. We do what you
                    <br /> need and what you accomplish for good service.
                </p>
            </div>
            <div class='seperator' style='background-color:#9499bb;'></div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-1">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper"></div>
            </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-10">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper">
                    <div class='carousel-testimonials text-light'>
                        <div class='swiper-container'>
                            <div class='swiper-wrapper' style="height: auto;">
                                <div class='swiper-slide'>
                                    <div class='testimonial'>
                                        <div class='stars'><i class='fa fa-star'></i><i class='fa fa-star'></i><i
                                                class='fa fa-star'></i><i class='fa fa-star'></i><i
                                                class='fa fa-star'></i>
                                        </div>
                                        <div class='tcontent'>“On behalf of all Blue Parallel team, I
                                            would like to thank you a million for all the hard and
                                            excellent work over the past 12 years . It has been a real
                                            pleasure to work with you and we always felt that we could
                                            count on you regardless of the day / time and regardless of
                                            how challenging the request was. We will miss you a lot as
                                            we almost felt you were part of our day -to day team. We
                                            wish you the best of luck, and we hope that our paths will
                                            again cross in the future. ”</div>
                                        <br>
                                        <strong>Emmanuel Burgio - President Blue Parallel. Inc.</strong>
                                    </div>
                                </div>
                                <div class='swiper-slide'>
                                    <div class='testimonial'>
                                        <div class='stars'><i class='fa fa-star'></i><i class='fa fa-star'></i><i
                                                class='fa fa-star'></i><i class='fa fa-star'></i><i
                                                class='fa fa-star'></i>
                                        </div>
                                        <div class='tcontent'>“Hello Carmen:
                                            <br> Thank you for our delicious lunch with you. We enjoyed
                                            it very much and also our weaving visit and guinea pig !!
                                            You and your mother are twins!! I just wanted to confirm we
                                            leave lake titilaka hotel with octavio tomorrow at 07.00am.
                                            Thank you and until next time”
                                        </div>
                                        <br>
                                        <strong>The Anderson Family</strong>
                                    </div>
                                </div>
                                <div class='swiper-slide'>
                                    <div class='testimonial'>
                                        <div class='stars'><i class='fa fa-star'></i><i class='fa fa-star'></i><i
                                                class='fa fa-star'></i><i class='fa fa-star'></i><i
                                                class='fa fa-star'></i>
                                        </div>
                                        <div class='tcontent'>“Dear Carmen, Marleny, Luisa and Raquel:
                                            <br> I hope you are good I just wanted to tell you from the
                                            date my load left as a Travel Planner in Aracari. Keep in
                                            touch with the company and I hope we can find again in the
                                            future It has been a pleasure working together with Andean
                                            Exclusive team and I thank you very much for your
                                            professionalism, patience, support and dedication. I leave
                                            my contact information”
                                        </div>
                                        <br>
                                        <strong>Travel Planner in Aracari</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='swiper-pagination'></div>
                        <div class='swiper-button-prev'></div>
                        <div class='swiper-button-next'></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-1">
            <div class="vc_column-inner ">
                <div class="wpb_wrapper"></div>
            </div>
        </div>
    </div>
    <div class="vc_row-full-width vc_clearfix"></div>
    </div>
    </article>
    </div>
    <div class="row" id="bookindex">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="separador"></div>
            <div id="separador"></div>
            <div class='heading  text-center '>
                <h3>How to find us</h3>
                <div class='seperator'></div>
                <div class='desc'>
                    <p class="text-center">Send us message to travel this 2022</p>
                </div>
            </div>
            <!-----Booking---------->
            <div class="form text-center">
                <h4 style="padding-top: 13px">Request Booking</h4>
                <form action="{{ route('bookindex2') }}" method="post" role="form" class="book1">
                    @csrf
                    <div class="form-row">
                        <br>
                        <div class="col-lg-12">
                            <span class="form-label">Name:</span>
                            <input type="text" name="nombre" required class="form-control" data-rule="minlen:4"
                                data-msg="Please enter at least 4 chars" />
                        </div>
                        <div class="col-lg-6 mt-3">
                            <span class="form-label">E-Mail:</span>
                            <input type="email" class="form-control" required name="email" id="email"
                                data-rule="email" data-msg="Please enter a valid email" />
                            <div class="validation"></div>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <span class="form-label">Phone:</span>
                            <input type="text" class="form-control" required name="telefono" id="telefono"
                                data-rule="minlen:4" />
                            <div class="validation"></div>
                        </div>                        
                    </div>
                    <div class="col-lg-12">
                        <span class="form-label">Send us a message:</span>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="5" data-rule="required"
                            data-msg="Please write something for us"></textarea>
                        <div class="validation"></div>
                    </div><br><br>
                    <div class="col-lg-12 mx-auto text-center">
                        <p id="checkmark" style="color:#fff;display: none; text-align:center; transition: opacity 0.5s ease">
                            Correct answer! &#10004;</p>
                        <div id="captcha" style="opacity: 1; transition: opacity 0.5s ease; padding-top:1em">
                            <span for="suma" style="float:initial">Solve or Captcha before submitting:</span><br>
                            <span id="num1" style="float: initial"></span> <i style="color:#fff">+</i> <span id="num2" style="float: initial"></span> 
                            <i style="color: #fff">=</i>
                            <span style="float: initial">
                                <input type="number" id="respuesta" name="respuesta" required style="width: 120px; color:#000">
                            </span>
                            <span style="float: initial">
                                <button type="button" id="verificar"
                                    style="background:#fff; color:#000;border:1px solid grey">verify</button>
                            </span>
                            <input type="hidden" id="valorCorrecto" name="valorCorrecto">
                        </div>
                    </div>
                    <input id="botondjm2" type="submit" name="submit" value="Send" disabled />
                    <br>
                </form>

                <script>
                    var intentos = 0;
            
                    function generarSumaAleatoria() {
                        var num1 = Math.floor(Math.random() * 12) + 3;
                        var num2 = Math.floor(Math.random() * (15 - num1)) + num1;
                        var suma = num1 + num2;
                        var num1Elemento = document.getElementById("num1");
                        var num2Elemento = document.getElementById("num2");
                        num1Elemento.textContent = num1;
                        num2Elemento.textContent = num2;
                        var valorCorrecto = document.getElementById("valorCorrecto");
                        valorCorrecto.value = suma;
                    }
                    window.onload = generarSumaAleatoria;
                    document.getElementById("verificar").addEventListener("click", function() {
                        var respuestaUsuario = parseInt(document.getElementById("respuesta").value);
                        var valorCorrecto = parseInt(document.getElementById("valorCorrecto").value);
            
                        if (respuestaUsuario === valorCorrecto) {
                            var captchaDiv = document.getElementById("captcha");
                            captchaDiv.style.opacity = "0";
                            setTimeout(function() {
                                captchaDiv.style.display = "none";
                            }, 500);
                            document.getElementById("botondjm2").disabled = false;
                            document.getElementById('checkmark').style.display = 'block';
                            setTimeout(function() {
                                document.getElementById('checkmark').style.display =
                                    'none';
                            }, 1200);
                        } else {
                            intentos++;
                            if (intentos === 3) {
                                alert("Has fallado 3 veces. Se te redirigirá a la página de error.");
                                window.location.href = "404";
                            } else {
                                alert("Respuesta incorrecta. Intento " + intentos + " de 3. Por favor, verifica tu respuesta.");
                                generarSumaAleatoria();
                            }
                        }
                    });
                </script>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
    </section>
    </div>
@endsection
