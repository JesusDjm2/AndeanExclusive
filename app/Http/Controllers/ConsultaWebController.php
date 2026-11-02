<?php

namespace App\Http\Controllers;

use App\Mail\ConsultaRecibidaMailable;
use App\Models\Consulta;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Formularios del sitio público: la consulta queda en el panel y además llega por correo.
 * Si el correo falla, la consulta ya está guardada: el visitante no tiene que volver a escribir.
 */
class ConsultaWebController extends Controller
{
    /** Ruta => [formulario, idioma]. */
    private const FORMULARIOS = [
        'mensaje01' => ['tour', 'en'],
        'bookindex' => ['rapida', 'en'],
        'bookindex2' => ['contacto', 'en'],
        'mensaje02' => ['tour', 'es'],
        'book-castellano' => ['rapida', 'es'],
        'bookindex2castellano' => ['contacto', 'es'],
    ];

    /** Formulario => sección de la página a la que se vuelve. El de contacto reabre su ventana. */
    private const ANCLAS = ['tour' => '#book', 'rapida' => '#consulta-rapida', 'contacto' => ''];

    /** Un humano no completa y envía un formulario en menos tiempo que este. */
    private const SEGUNDOS_MINIMOS = 2;

    public function recibir(Request $request, string $formulario): RedirectResponse
    {
        [$tipo, $idioma] = self::FORMULARIOS[$formulario];
        app()->setLocale($idioma);

        // A los robots se les responde como si nada, para no darles pistas.
        if ($this->pareceRobot($request)) {
            return $this->volver($tipo)->with('form_enviado', $tipo);
        }

        $validador = Validator::make($request->all(), $this->reglas($tipo), $this->mensajes($idioma));
        if ($validador->fails()) {
            return $this->volver($tipo)->withErrors($validador)->withInput()->with('form_activo', $tipo);
        }

        $datos = $validador->validated();
        $consulta = new Consulta([
            'nombre' => ($datos['nombre'] ?? null) ?: ($datos['name'] ?? null) ?: $datos['email'],
            'email' => $datos['email'], 'telefono' => $datos['telefono'] ?? null, 'idioma' => $idioma, 'origen' => 'web',
            'tour' => $datos['tour'] ?? null, 'fecha_inicio' => $datos['da'] ?? null, 'fecha_fin' => $datos['do'] ?? null,
            'adultos' => $datos['adultos'] ?? null, 'ninos' => $datos['child'] ?? null, 'mensaje' => $datos['mensaje'] ?? null,
        ]);
        $guardada = rescue(fn () => $consulta->save(), false);

        $correoEnviado = false;
        try {
            Mail::to(config('mail.consultas_destino'))->send(new ConsultaRecibidaMailable($consulta));
            $correoEnviado = true;
        } catch (Throwable $e) {
            report($e);
        }

        // Solo se avisa del fallo si no quedó ningún rastro de la consulta.
        return $guardada || $correoEnviado
            ? $this->volver($tipo)->with('form_enviado', $tipo)
            : $this->volver($tipo)->withInput()->with('form_activo', $tipo)->with('form_fallo', $tipo);
    }

    private function pareceRobot(Request $request): bool
    {
        if ($request->filled('sitio_web')) {
            return true;
        }

        try {
            return now()->timestamp - (int) Crypt::decryptString((string) $request->input('_sello')) < self::SEGUNDOS_MINIMOS;
        } catch (DecryptException) {
            return true;
        }
    }

    private function volver(string $tipo): RedirectResponse
    {
        return redirect()->to(strtok(url()->previous(), '#') . self::ANCLAS[$tipo]);
    }

    private function reglas(string $tipo): array
    {
        $comunes = [
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:40',
            'mensaje' => 'nullable|string|max:5000',
        ];
        $viaje = [
            'adultos' => ($tipo === 'rapida' ? 'required' : 'nullable') . '|integer|min:1|max:99',
            'child' => 'nullable|integer|min:0|max:99',
            'da' => 'nullable|date',
            'do' => 'nullable|date|after_or_equal:da',
            'tour' => 'nullable|string|max:255',
        ];

        return match ($tipo) {
            'rapida' => $comunes + $viaje,
            'tour' => $comunes + $viaje + ['nombre' => 'required|string|max:255'],
            'contacto' => $comunes + ['nombre' => 'required|string|max:255'],
        };
    }

    private function mensajes(string $idioma): array
    {
        return $idioma === 'es'
            ? [
                'nombre.required' => 'Escribe tu nombre.',
                'email.required' => 'Escribe tu correo para poder responderte.',
                'email.email' => 'Revisa tu correo: parece incompleto.',
                'adultos.required' => 'Indica cuántos adultos viajan.',
                'adultos.min' => 'Debe viajar al menos un adulto.',
                'adultos.integer' => 'Escribe un número.',
                'child.integer' => 'Escribe un número.',
                'da.date' => 'Revisa la fecha de llegada.',
                'do.date' => 'Revisa la fecha de salida.',
                'do.after_or_equal' => 'La salida no puede ser antes de la llegada.',
            ]
            : [
                'nombre.required' => 'Please enter your name.',
                'email.required' => 'Please enter your email so we can reply.',
                'email.email' => 'Please check your email: it looks incomplete.',
                'adultos.required' => 'Tell us how many adults are traveling.',
                'adultos.min' => 'At least one adult must travel.',
                'adultos.integer' => 'Please enter a number.',
                'child.integer' => 'Please enter a number.',
                'da.date' => 'Please check the arrival date.',
                'do.date' => 'Please check the departure date.',
                'do.after_or_equal' => 'Departure cannot be before arrival.',
            ];
    }
}
