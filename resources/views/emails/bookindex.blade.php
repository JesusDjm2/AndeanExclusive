<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Notificaciones</title>
</head>
<style>
    #boton{background: #2d9cf5;
    color: #fff;
    font-size: 14px;
    border-radius: 5px;
    border-style: solid;
    border-color: #2d9cf5;
    height: 40px;}

    #boton:hover{background: #fff; color: #2d9cf5; font-weight: 700; border-width: 2px;}
</style>

<body>
    <section>
        <div style="margin-top: 50px;"></div>
        <center><img src="https://andeanexclusive.com/img/andean-exclusive-logo.png" width="200px" </center>
            <center>
                <div style="background: #000; width: 70%; height: 3px; margin-top:8px;margin-bottom:2px;"></div>
            </center>
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6" style="text-align: left; padding: 20px 50px 0px 50px;">Hola Carmen: <br>
                    <p style="text-align: justify;">Andean Exclusive Tours acaba de recibir un nuevo mensaje desde la pagina de inicio.</p>
                    
                    <p style="text-align: justify;"></p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Detalles del formulario:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de llegada:</th>
                                <td>{{ $da}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Fecha de salida:</th>
                                <td>{{ $do}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Adultos:</th>
                                <td>{{$adultos}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Niños:</th>
                                <td>{{$child}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Email:</th>
                                <td>{{$email}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Mensaje:</th>
                                <td>{{$mensaje}}</td>
                            </tr>
                        </tbody>
                    </table>              
                    <br>
                    <p>Copyright© 2022 Andean Exclusive Tours, Todos los derechos reservados</p>               
                </div>
                <div class="col-lg-3"></div>
            </div>
    </section>

</body>

</html>
