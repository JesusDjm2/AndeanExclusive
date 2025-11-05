<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EnblogController;
use App\Http\Controllers\EnCatController;
use App\Http\Controllers\EnEnlacesController;
use App\Http\Controllers\EntagController;
use App\Http\Controllers\EsblogController;
use App\Http\Controllers\EsCatController;
use App\Http\Controllers\EstagController;
use App\Http\Controllers\EsTourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



Auth::routes();
Route::middleware('web')->post('/login', [UserController::class, 'login'])->name('login.submit');


Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->names('users');
    Route::resource('tours-en-ingles', TourController::class)->names('tours');
    Route::resource('categorias-en-ingles', EnCatController::class)->names('categories');
    Route::resource('tours-espanol', EsTourController::class)->names('estours');
    Route::resource('categorias-espanol', EsCatController::class)->names('categorias');
    Route::resource('entags', EntagController::class)->names('entags');
    Route::resource('blogs-en-ingles', EnblogController::class)->names('enblogs');
    Route::resource('estags', EstagController::class)->names('estags');
    Route::resource('esblogs', EsblogController::class)->names('esblogs');
    Route::resource('imagenes', ImagenController::class)->names('imagenes');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('proveedors', ProveedorController::class)->names('proveedors');
});


Route::get('around-peru', [EnEnlacesController::class, 'around'])->name('around');
Route::get('exclusive-tours', [EnEnlacesController::class, 'experiences'])->name('experiences');
Route::get('adventures', [EnEnlacesController::class, 'adventures'])->name('adventures');
Route::get('peru-tourism-blog', [EnEnlacesController::class, 'blog'])->name('blog-en');
Route::get('about', [EnEnlacesController::class, 'about'])->name('about');
Route::get('testimonials', [EnEnlacesController::class, 'testimonials'])->name('testimonials');
Route::get('terms-conditions-aet', [EnEnlacesController::class, 'terms'])->name('terms');
Route::get('faqs', [EnEnlacesController::class, 'faqs'])->name('faqs');

// Rutas Español
Route::get('turismo-Peru', [EnEnlacesController::class, 'esIndex'])->name('inicio');
Route::get('tours-exclusivos', [EnEnlacesController::class, 'experiencias'])->name('experiencias');
Route::get('alrededor-de-peru', [EnEnlacesController::class, 'alrededor'])->name('alrededor-de-peru');
Route::get('tours-de-aventura', [EnEnlacesController::class, 'caminatas'])->name('caminatas');
Route::get('blog-turismo-peru', [EnEnlacesController::class, 'bloges'])->name('blog-es');
Route::get('preguntas-frecuentes', [EnEnlacesController::class, 'preguntas'])->name('preguntas-frecuentes');
Route::get('nosotros', [EnEnlacesController::class, 'nosotros'])->name('nosotros');
Route::get('testimonios', [EnEnlacesController::class, 'testimonios'])->name('testimonios');


Route::get('mostrar', [CategoriaController::class, 'mostrar'])->name('mostrar');
Route::get('/en/{slug}', [TourController::class, 'show'])->name('tour.show');
Route::get('/{slug}', [EsTourController::class, 'show'])->name('estour.show');
Route::get('category/{slug}', [EnCatController::class, 'show'])->name('category.show');
Route::get('categoria/{slug}', [EsCatController::class, 'show'])->name('categoria.show');
Route::get('en/blog/{slug}', [EnblogController::class, 'show'])->name('enblog.show');
Route::get('es/blog/{slug}', [EsblogController::class, 'show'])->name('esblog.show');
Route::get('en/tag/{slug}', [EntagController::class, 'show'])->name('entag.show');
Route::get('es/tag/{slug}', [EstagController::class, 'show'])->name('estag.show');

// Rutas en Ingles
/* Route::get('/', function () {return view('index');})->name('index'); */
Route::get('/', [EnEnlacesController::class, 'index'])->name('index');

// Formularios en inglés
Route::post('mensaje01', function () {
    $datos = request()->all();
    Mail::send('emails.mensaje1', $datos, function ($message) use ($datos) {
        $message->from($datos['email'], $datos['name'])
            ->to('routes@andeanexclusive.com', 'DJM2')
            ->subject('Envio de formulario desde la página web.');
    });

    return back()->with('flash', 'Your message was sent successfully!');
})->name('mensaje01');

Route::post('bookindex', function () {
    $datos = request()->all();
    Mail::send('emails.bookindex', $datos, function ($message) use ($datos) {
        $message->from($datos['email'])
            ->to('routes@andeanexclusive.com', 'DJM2')
            ->subject('Envio de formulario desde la página web.');
    });

    return back()->with('flash', 'Your message was sent successfully!');
})->name('bookindex');

Route::post('bookindex2', function () {
    $datos = request()->all();
    if (! empty($datos['personas'])) {
        return back()->with('error', 'Error: Invalid form submission');
    }

    Mail::send('emails.bookindex2', $datos, function ($message) use ($datos) {
        $message->from($datos['email'])
            ->to('routes@andeanexclusive.com', 'DJM2')
            ->subject('Envio de contacto desde página web.');
    });

    return back()->with('flash', 'Your message was sent successfully!');
})->name('bookindex2');

Route::post('mensaje02', function () {
    $datos = request()->all();
    Mail::send('emails.mensaje1', $datos, function ($message) use ($datos) {
        $message->from($datos['email'], $datos['name'])
            ->to('routes@andeanexclusive.com', 'DJM2')
            ->subject('Envio de contacto desde página web.');
    });

    return back()->with('flash', '¡Su mensaje fué enviado con éxito! Le responderemos en la mayor brevedad posible.');
})->name('mensaje02');

Route::post('book-castellano', function () {
    $datos = request()->all();
    Mail::send('emails.bookindex', $datos, function ($message) use ($datos) {
        $message->from($datos['email'])
            ->to('routes@andeanexclusive.com', 'DJM2')
            ->subject('Envio de contacto desde página web.');
    });

    return back()->with('flash', '¡Su mensaje fué enviado con éxito! Le responderemos en la mayor brevedad posible.');
})->name('book-castellano');

Route::post('bookindex2castellano', function () {
    $datos = request()->all();
    Mail::send('emails.bookindex2', $datos, function ($message) use ($datos) {
        $message->from($datos['email'])
            ->to('routes@andeanexclusive.com', 'DJM2')
            ->subject('Envio de contacto desde página web.');
    });

    return back()->with('flash', '¡Su mensaje fué enviado con éxito!');
})->name('bookindex2castellano');

// Aventuras Español
Route::get('adventures/tour-choquequirao', function () {
    return view('adventures/tour-choquequirao');
});
Route::get('adventures/tour-atv', function () {
    return view('adventures/tour-atv');
})->name('tour-atv');
Route::get('adventures/tour-rafting', function () {
    return view('adventures/tour-rafting');
})->name('tour-rafting');
Route::get('adventures/tour-islas-palomino', function () {
    return view('adventures/tour-islas-palomino');
});
Route::get('adventures/tour-laguna-humantay', function () {
    return view('adventures/tour-laguna-humantay');
});
Route::get('adventures/vinicunca-tour', function () {
    return view('adventures/vinicunca-tour');
});

/* Route::get('/{sendTitulo}/{prueba2}', function ($titulo = null, $pruebaVariable = null) {
    return view('emails.reserva', compact('titulo', 'pruebaVariable'));
})->name('reserva'); */
Route::get('/{sendTitulo}/{prueba2}', function ($titulo = null, $pruebaVariable = null) {
    return view('emails.reserva', compact('titulo', 'pruebaVariable'));
})->where([
    'sendTitulo' => '[A-Za-z0-9\-]+',
    'prueba2' => '[A-Za-z0-9\-]+',
])->name('reserva');