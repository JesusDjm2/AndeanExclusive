<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Programa;
use App\Support\Recordatorios;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Envío de un correo al cliente desde el formulario del programa (siempre manual). */
class NotificacionController extends Controller
{
    public function store(Request $request, Programa $programa)
    {
        $request->validate(['tipo' => ['required', Rule::in(array_keys(Notificacion::TIPOS))]]);
        $volver = redirect()->route('programas.edit', [$programa, 'tab' => $request->input('_tab', 'pagos')]);

        if (! filter_var((string) $programa->email, FILTER_VALIDATE_EMAIL)) {
            return $volver->with('error', 'El cliente del programa no tiene un correo válido.');
        }

        try {
            Recordatorios::enviar($programa, $request->input('tipo'), auth()->id());
        } catch (\Throwable $e) {
            report($e);

            return $volver->with('error', 'No se pudo enviar el correo. Revisa la configuración de correo (MAIL_*) en .env.');
        }

        return $volver->with('success', Notificacion::TIPOS[$request->input('tipo')] . ' enviado a ' . $programa->email . '.');
    }
}
