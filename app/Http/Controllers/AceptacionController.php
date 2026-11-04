<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;

/** El cliente acepta su propuesta desde el enlace privado. Confirmar el programa sigue siendo decisión del equipo. */
class AceptacionController extends Controller
{
    public function store(Request $request, Programa $programa)
    {
        abort_unless($programa->aceptable(), 404);

        $isEn = $programa->lang === 'en';
        $datos = $request->validate(
            ['nombre' => 'required|string|max:255', 'terminos' => 'accepted'],
            $isEn
                ? ['nombre.required' => 'Please write your full name.', 'terminos.accepted' => 'You must accept the terms and conditions.']
                : ['nombre.required' => 'Escriba su nombre completo.', 'terminos.accepted' => 'Debe aceptar los términos y condiciones.']
        );

        if (! $programa->aceptacionVigente()) {
            $propuesta = $programa->fotoDePropuesta();
            $programa->aceptaciones()->create([
                'nombre' => $datos['nombre'], 'ip' => $request->ip(), 'navegador' => mb_substr((string) $request->userAgent(), 0, 255),
                'total' => $propuesta['total'], 'propuesta' => $propuesta, 'huella' => Programa::huellaDe($propuesta),
            ]);
        }

        return redirect()->to(route('itinerario', $programa) . '#propuesta')
            ->with('aceptada', $isEn ? 'Thank you! We received your acceptance. Our team will contact you with the next steps.' : '¡Gracias! Recibimos su aceptación. Nuestro equipo le escribirá con los siguientes pasos.');
    }
}
