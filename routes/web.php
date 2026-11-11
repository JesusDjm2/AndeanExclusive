<?php

use App\Http\Controllers\AceptacionController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\CopiaDeProgramaController;
use App\Http\Controllers\ConsultaWebController;
use App\Http\Controllers\EnblogController;
use App\Http\Controllers\EnCatController;
use App\Http\Controllers\EnEnlacesController;
use App\Http\Controllers\EntagController;
use App\Http\Controllers\EsblogController;
use App\Http\Controllers\EsCatController;
use App\Http\Controllers\EstagController;
use App\Http\Controllers\EsTourController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\OperacionesController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\CuentasPorPagarController;
use App\Http\Controllers\DatosPasajerosController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\OperacionDiariaController;
use App\Http\Controllers\MensajeProveedorController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\PaxController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RespaldoController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\TarifarioCategoriaController;
use App\Http\Controllers\TarifarioServicioController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Sin registro público: los usuarios los crea un administrador (Usuarios → Nuevo, UserController@create).
Auth::routes(['register' => false]);
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.get');
// Máx. 5 intentos de acceso por minuto (protección contra fuerza bruta).
Route::middleware(['web', 'panel.es', 'throttle:5,1'])->post('/login', [UserController::class, 'login'])->name('login.submit');

Route::middleware(['web', 'guest', 'panel.es'])->group(function () {
    Route::get('/verificacion', [UserController::class, 'verificacion'])->name('verificacion');
    Route::post('/verificacion', [UserController::class, 'comprobarVerificacion'])->middleware('throttle:10,1')->name('verificacion.comprobar');
});

// Solo usuarios con un rol asignado entran al panel (una cuenta sin rol no ve nada).
Route::middleware(['panel.es', 'auth', 'role:administrador|operaciones|contabilidad|reservas|branding'])->group(function () {
    Route::middleware('role:administrador')->group(function () {
        Route::resource('users', UserController::class)->names('users');
        Route::get('copias-de-seguridad', [RespaldoController::class, 'index'])->name('respaldos.index');
        Route::post('copias-de-seguridad', [RespaldoController::class, 'store'])->name('respaldos.store');
        Route::get('copias-de-seguridad/{nombre}', [RespaldoController::class, 'descargar'])->name('respaldos.descargar');
    });

    // Todas las áreas consultan; cada una modifica solo lo suyo (App\Support\Permisos).
    Route::middleware('area')->group(function () {
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
        Route::get('movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
        Route::get('ayuda', [AyudaController::class, 'index'])->name('ayuda.index');
        Route::get('ayuda/guia', [AyudaController::class, 'guia'])->name('ayuda.guia');
        Route::get('notificaciones', [AvisoController::class, 'index'])->name('avisos.index');
        Route::get('notificaciones/resumen', [AvisoController::class, 'resumen'])->name('avisos.resumen');
        Route::post('notificaciones/marcar-todas', [AvisoController::class, 'marcarTodas'])->name('avisos.marcar-todas');
        Route::get('notificaciones/{aviso}/abrir', [AvisoController::class, 'abrir'])->whereNumber('aviso')->name('avisos.abrir');
        Route::post('notificaciones/{aviso}/marcar', [AvisoController::class, 'marcar'])->whereNumber('aviso')->name('avisos.marcar');
        Route::get('tarifario/sugerir', [TarifaController::class, 'sugerir'])->name('tarifas.sugerir');
        Route::resource('tarifario', TarifaController::class)->except('show')->parameters(['tarifario' => 'tarifa'])->names('tarifas');
        Route::post('tarifario-maestro/tipo-cambio', [TarifarioServicioController::class, 'actualizarTipoCambio'])->name('tarifario-maestro.tipo-cambio');
        Route::resource('tarifario-maestro/categorias', TarifarioCategoriaController::class)
            ->names('tarifario-categorias')
            ->parameters(['categorias' => 'categoria'])
            ->except(['show', 'create', 'edit']);
        Route::get('tarifario-maestro/cotizar', [TarifarioServicioController::class, 'cotizar'])->name('tarifario-maestro.cotizar');
        Route::get('tarifario-maestro/buscar', [TarifarioServicioController::class, 'buscar'])->name('tarifario-maestro.buscar');
        Route::get('tarifario-maestro/{tarifarioServicio}/componentes', [TarifarioServicioController::class, 'componentes'])
            ->whereNumber('tarifarioServicio')->name('tarifario-maestro.componentes');
        Route::resource('tarifario-maestro', TarifarioServicioController::class)
            ->names('tarifario-maestro')
            ->parameters(['tarifario-maestro' => 'tarifarioServicio']);
        Route::resource('proveedors', ProveedorController::class)->names('proveedors');
        Route::post('proveedores/{proveedor}/habitaciones', [ProveedorController::class, 'habitacionStore'])->name('proveedors.habitaciones.store');
        Route::delete('proveedores/{proveedor}/habitaciones/{habitacion}', [ProveedorController::class, 'habitacionDestroy'])->name('proveedors.habitaciones.destroy');
        Route::post('proveedores/{proveedor}/hotel-foto', [ProveedorController::class, 'hotelFoto'])->name('proveedors.hotel.foto');
        Route::resource('categorias-Proveedores', CategoriaController::class)->names('categoriasproveedor');
        Route::resource('agentes', AgenteController::class)->names('agentes');
        Route::post('consultas/{consulta}/cotizar', [ConsultaController::class, 'cotizar'])->name('consultas.cotizar');
        Route::resource('consultas', ConsultaController::class)->only(['index', 'create', 'store', 'show', 'update'])->names('consultas');
        Route::get('programas/plantillas', [CopiaDeProgramaController::class, 'plantillas'])->name('programas.plantillas');
        Route::get('programas/{programa}/duplicar', [CopiaDeProgramaController::class, 'create'])->name('programas.duplicar');
        Route::post('programas/{programa}/duplicar', [CopiaDeProgramaController::class, 'store'])->name('programas.duplicar.store');
        Route::post('programas/{programa}/plantilla', [CopiaDeProgramaController::class, 'guardarPlantilla'])->name('programas.plantilla.store');
        Route::delete('programas/{programa}/plantilla', [CopiaDeProgramaController::class, 'eliminarPlantilla'])->name('programas.plantilla.destroy');
        Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('reportes/exportar/{tabla}', [ReporteController::class, 'exportar'])->name('reportes.exportar');
        Route::get('programas/{programa}/lista-de-huespedes', [OperacionDiariaController::class, 'rooming'])->name('programas.rooming');
        Route::post('programas/{programa}/cerrar', [OperacionDiariaController::class, 'cerrar'])->name('programas.cerrar');
        Route::post('programas/{programa}/reabrir', [OperacionDiariaController::class, 'reabrir'])->name('programas.reabrir');
        Route::post('servicios/{servicio}/enviar-orden', [OperacionDiariaController::class, 'enviarOrden'])->name('servicios.enviar-orden');
        Route::get('mensajes-proveedores', [MensajeProveedorController::class, 'index'])->name('mensajes.index');
        Route::get('servicios/{servicio}/mensaje', [MensajeProveedorController::class, 'crear'])->name('servicios.mensaje');
        Route::post('servicios/{servicio}/mensaje', [MensajeProveedorController::class, 'enviar'])->name('servicios.mensaje.enviar');
        Route::post('servicios/{servicio}/respuesta', [MensajeProveedorController::class, 'respuesta'])->name('servicios.mensaje.respuesta');
        Route::get('programas/por-periodo', [ProgramaController::class, 'porPeriodo'])->name('programas.por-periodo');
        Route::get('programas/{programa}/pdf', [ProgramaController::class, 'exportPdf'])->name('programas.pdf');
        Route::get('programas/{programa}/historial', [ProgramaController::class, 'historial'])->name('programas.historial');
        Route::get('programas/{programa}/ficha', [ProgramaController::class, 'adminShow'])->name('programas.ficha');
        Route::post('programas/{programa}/actualizar-cotizacion', [ProgramaController::class, 'actualizarCotizacion'])->name('programas.actualizar-cotizacion');
        Route::post('programas/{programa}/enviar-correo', [ProgramaController::class, 'enviarCorreo'])->name('programas.enviar-correo');
        Route::post('programas/{programa}/pagos', [PagoController::class, 'store'])->name('programas.pagos.store');
        Route::delete('programas/{programa}/pagos/{pago}', [PagoController::class, 'destroy'])->name('programas.pagos.destroy')->scopeBindings();
        Route::post('programas/{programa}/tareas', [TareaController::class, 'store'])->name('programas.tareas.store');
        Route::post('programas/{programa}/tareas/plantillas', [TareaController::class, 'plantillas'])->name('programas.tareas.plantillas');
        Route::patch('programas/{programa}/tareas/{tarea}', [TareaController::class, 'toggle'])->name('programas.tareas.toggle')->scopeBindings();
        Route::delete('programas/{programa}/tareas/{tarea}', [TareaController::class, 'destroy'])->name('programas.tareas.destroy')->scopeBindings();
        Route::get('operaciones', [OperacionesController::class, 'index'])->name('operaciones.index');
        Route::patch('servicios/{servicio}/estado', [ServicioController::class, 'estado'])->name('servicios.estado');
        Route::patch('servicios/{servicio}/proveedor', [ServicioController::class, 'asignar'])->name('servicios.asignar');
        Route::post('servicios/{servicio}/solicitar-pago', [ServicioController::class, 'solicitarPago'])->name('servicios.solicitar-pago');
        Route::post('servicios/{servicio}/pagos', [ServicioController::class, 'pagar'])->name('servicios.pagar');
        Route::delete('servicios/{servicio}/pagos/{pago}', [ServicioController::class, 'anularPago'])->name('servicios.pagos.anular')->scopeBindings();
        Route::get('servicios/{servicio}/pagos/{pago}/comprobante', [ServicioController::class, 'comprobante'])->name('servicios.pagos.comprobante')->scopeBindings()->withTrashed();
        Route::get('servicios/{servicio}/historial', [ServicioController::class, 'historial'])->name('servicios.historial');
        Route::get('reservas', [ReservasController::class, 'index'])->name('reservas.index');
        Route::post('reservas/lote', [ReservasController::class, 'lote'])->name('reservas.lote');
        Route::get('servicios/{servicio}/orden', [ServicioController::class, 'voucher'])->name('servicios.voucher');
        Route::post('programas/{programa}/notificaciones', [NotificacionController::class, 'store'])->name('programas.notificaciones.store');
        Route::get('cuentas-por-pagar', [CuentasPorPagarController::class, 'index'])->name('cuentas.index');
        Route::post('cuentas-por-pagar/pagar', [CuentasPorPagarController::class, 'pagar'])->name('cuentas.pagar');
        Route::delete('cuentas-por-pagar/{servicio}', [CuentasPorPagarController::class, 'anular'])->name('cuentas.anular');
        Route::resource('programas', ProgramaController::class)->except('show')->names('programas');
        Route::get('paxs/{pax}/pasaporte', [PaxController::class, 'pasaporte'])->name('paxs.pasaporte');
        Route::resource('clientes', ClienteController::class)->except('show')->names('clientes');
        Route::resource('paxs', PaxController::class)->names('paxs');
        Route::post('hoteles/rapido', [ProveedorController::class, 'hotelRapido'])->name('hotel.rapido');
        Route::get('hoteles/{id}', function ($id) {
            return redirect()->route('proveedors.show', $id);
        })->name('hotel.show');
        Route::get('hoteles', function () {
            $cat = \App\Models\Categoria::where('nombre', 'like', '%hotel%')->first();
            return redirect()->route('proveedors.index', $cat ? ['categoria_id' => $cat->id] : []);
        })->name('hotel.index');
        Route::any('hoteles/{any}', function () {
            $cat = \App\Models\Categoria::where('nombre', 'like', '%hotel%')->first();
            return redirect()->route('proveedors.index', $cat ? ['categoria_id' => $cat->id] : []);
        })->where('any', '.*');
        Route::resource('habitaciones', HabitacionController::class)->names('habitacion');
    });
});

// Itinerario del cliente: enlace secreto por token (no por ID secuencial, que permitía recorrer todos los programas).
Route::get('itinerario/{programa:token}', [ProgramaController::class, 'show'])->name('itinerario');
// El cliente completa los datos de sus viajeros desde su enlace privado.
Route::post('itinerario/{programa:token}/aceptar', [AceptacionController::class, 'store'])->middleware('throttle:10,1')->name('itinerario.aceptar');
Route::get('itinerario/{programa:token}/pasajeros', [DatosPasajerosController::class, 'edit'])->name('itinerario.pasajeros');
Route::put('itinerario/{programa:token}/pasajeros', [DatosPasajerosController::class, 'update'])->middleware('throttle:10,1')->name('itinerario.pasajeros.update');

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
Route::get('terminos-y-condiciones', [EnEnlacesController::class, 'terminos'])->name('terminos');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

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

// Formularios del sitio: la consulta queda en el panel y además llega por correo
Route::middleware('throttle:6,1')->group(function () {
    foreach (['mensaje01', 'bookindex', 'bookindex2', 'mensaje02', 'book-castellano', 'bookindex2castellano'] as $formulario) {
        Route::post($formulario, [ConsultaWebController::class, 'recibir'])->defaults('formulario', $formulario)->name($formulario);
    }
});

// Aventuras Español
Route::get('adventures/tour-choquequirao', function () {
    return view('adventures.tour-choquequirao');
})->name('tour-choquequirao');
Route::get('adventures/tour-atv', function () {
    return view('adventures.tour-atv');
})->name('tour-atv');
Route::get('adventures/tour-rafting', function () {
    return view('adventures.tour-rafting');
})->name('tour-rafting');
Route::get('adventures/tour-islas-palomino', function () {
    return view('adventures.tour-islas-palomino');
})->name('tour-islas-palomino');
Route::get('adventures/tour-laguna-humantay', function () {
    return view('adventures.tour-laguna-humantay');
})->name('tour-laguna-humantay');
Route::get('adventures/vinicunca-tour', function () {
    return view('adventures.vinicunca-tour');
})->name('vinicunca-tour');
