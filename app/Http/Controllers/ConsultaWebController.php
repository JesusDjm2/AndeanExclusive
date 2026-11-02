<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Formularios del sitio público: la consulta queda en el panel y además llega por correo.
 * Si el correo falla, la consulta ya está guardada: el visitante no tiene que volver a escribir.
 */
class ConsultaWebController extends Controller
{
    private const DESTINO = 'routes@andeanexclusive.com';

    /** Formulario => [plantilla del correo, idioma, clave del mensaje de éxito]. */
    private const FORMULARIOS = [
        'mensaje01' => ['emails.mensaje1', 'en', 'flash'],
        'bookindex' => ['emails.bookindex', 'en', 'flash'],
        'bookindex2' => ['emails.bookindex2', 'en', 'contact_flash'],
        'mensaje02' => ['emails.mensaje1', 'es', 'flash'],
        'book-castellano' => ['emails.bookindex', 'es', 'flash'],
        'bookindex2castellano' => ['emails.bookindex2', 'es', 'contact_flash'],
    ];

    public function recibir(Request $request, string $formulario)
    {
        [$plantilla, $idioma, $claveExito] = self::FORMULARIOS[$formulario];
        app()->setLocale($idioma);
        $exito = $idioma === 'es' ? '¡Su mensaje fue enviado con éxito! Le responderemos a la brevedad.' : 'Your message was sent successfully!';

        // Campo trampa: solo lo llenan los robots. Se les responde igual para no darles pistas.
        if ($request->filled('personas')) {
            return back()->with($claveExito, $exito);
        }

        $datos = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:40',
            'tour' => 'nullable|string|max:255',
            'adultos' => 'nullable|integer|min:0|max:999',
            'child' => 'nullable|integer|min:0|max:999',
            'da' => 'nullable|date',
            'do' => 'nullable|date',
            'mensaje' => 'nullable|string|max:5000',
        ]);
        $nombre = ($datos['name'] ?? null) ?: ($datos['nombre'] ?? null) ?: $datos['email'];

        rescue(fn () => Consulta::create([
            'nombre' => $nombre, 'email' => $datos['email'], 'telefono' => $datos['telefono'] ?? null, 'idioma' => $idioma, 'origen' => 'web',
            'tour' => $datos['tour'] ?? null, 'fecha_inicio' => $datos['da'] ?? null, 'fecha_fin' => $datos['do'] ?? null,
            'adultos' => $datos['adultos'] ?? null, 'ninos' => $datos['child'] ?? null, 'mensaje' => $datos['mensaje'] ?? null,
        ]));

        try {
            $variables = $datos + array_fill_keys(['name', 'nombre', 'telefono', 'tour', 'adultos', 'child', 'da', 'do', 'mensaje'], null);
            $variables['name'] = $variables['nombre'] = $nombre;

            // El remitente es la agencia: suplantar el correo del visitante hace que los servidores rechacen el envío.
            Mail::send($plantilla, $variables, function ($message) use ($datos, $nombre) {
                $message->to(self::DESTINO, 'Andean Exclusive')->replyTo($datos['email'], $nombre)->subject('Consulta desde la página web');
            });
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with($claveExito, $exito);
    }
}
