<!-----Booking---------->
<div class="fondo-book">
    <h3 class="text-center">Solicitar Información</h4>
    <form action="{{ route('mensaje02') }}" method="post" role="form" class="book1">
        @csrf
        <div class="form-row">
            <br>
            <div class="form-group col-lg-6">
                <span class="form-label">Nombre:</span>
                <input type="text" name="name" required class="form-control" placeholder="Requerido*"
                    data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
            </div>
            <div class="form-group col-lg-6">
                <span class="form-label">E-Mail:</span>
                <input type="email" class="form-control" required name="email" id="email"
                    placeholder="Requerido*" data-rule="email" data-msg="Please enter a valid email" />
                <div class="validation"></div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-6">
                <span class="form-label">Adultos:</span>
                <input class="form-control" name="adultos" id="adultos" type="number" placeholder="0"
                    data-error="Valid email is required.">
                <div class="validation"></div>
            </div>
            <div class="form-group col-lg-6">
                <span class="form-label">Niños:</span>
                <input class="form-control" name="child" id="child" type="number" placeholder="0"
                    data-error="Valid email is required.">
                <div class="validation"></div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-12">
                <span class="form-label">Fecha Arrivo:</span>
                <input class="form-control" name="da" id="da" type="date">
                <div class="validation"></div>
            </div>
            <div class="form-group col-lg-12">
                <span class="form-label">Tour:</span>
                <input type="text" name="name" value="{{$tour->nombre}}" readonly/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-12">
                <span class="form-label">Mensaje:</span>
                <textarea class="form-control" name="mensaje" id="mensaje" rows="5" data-rule="required"
                    data-msg="Please write something for us" placeholder="Escriba acá su mensaje:"></textarea>
                <div class="validation"></div>
            </div>
        </div>
        <div class="form-group col-lg-12">
            <p id="checkmark" style="color:#fff;display: none; text-align:center; transition: opacity 0.5s ease">
                Respuesta correcta! &#10004;</p>
            <div id="captcha" style="opacity: 1; transition: opacity 0.5s ease;">
                <span for="suma">Resolver Captcha antes de enviar:</span><br>
                <span id="num1"></span> + <span id="num2"></span> =
                <span>
                    <input type="number" id="respuesta" name="respuesta" required style="width: 120px; color:#000">
                </span>
                <span>
                    <button type="button" id="verificar"
                        style="background:#fff; color:#000;border:1px solid grey">Verificar</button>
                </span>
                <input type="hidden" id="valorCorrecto" name="valorCorrecto">
            </div>
        </div>
        <center>
            <input id="botondjm2" type="submit" name="submit" value="Enviar" />
        </center><br>
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
