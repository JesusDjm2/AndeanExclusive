<?php

namespace App\Http\Controllers;

use App\Models\MensajeProveedor;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Support\CorreoReservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/** Mensajería de Reservas con los proveedores: bandeja, redacción por servicio y respuestas registradas. */
class MensajeProveedorController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('reservas.index', array_merge(['bandeja' => 'mensajes'], $request->all()));
    }

    public function crear(Request $request, Servicio $servicio)
    {
        $servicio->load(['programa.paxs', 'programa.cliente', 'proveedor', 'mensajes.user:id,name']);
        $plantillas = $servicio->proveedor_id ? CorreoReservas::plantillas($servicio, $request->user()) : [];

        // Plantilla sugerida según el punto en que está la reserva.
        $sugerida = match ($servicio->estado) {
            'confirmado' => 'confirmacion',
            'solicitado', 'cancelado' => 'consulta',
            default => 'solicitud',
        };
        $tipo = array_key_exists((string) $request->input('tipo'), CorreoReservas::PLANTILLAS) ? $request->input('tipo') : $sugerida;

        return view('mensajes.crear', [
            'servicio' => $servicio,
            'plantillas' => $plantillas,
            'tipo' => $tipo,
            'correoValido' => filter_var(trim((string) $servicio->proveedor?->correo), FILTER_VALIDATE_EMAIL) !== false,
        ]);
    }

    public function enviar(Request $request, Servicio $servicio)
    {
        if (! $servicio->proveedor_id) {
            return back()->with('error', "«{$servicio->titulo}» no tiene proveedor: asígnalo antes de escribirle.");
        }

        $datos = $request->validate([
            'tipo' => ['required', Rule::in(array_keys(CorreoReservas::PLANTILLAS))],
            'para' => 'required|string|max:500',
            'cc' => 'nullable|string|max:500',
            'asunto' => 'required|string|max:255',
            'cuerpo' => 'required|string|max:10000',
            'con_orden' => 'nullable|boolean',
            'archivo_adjunto' => 'nullable|file|max:20480',
        ], [
            'para.required' => 'Indica al menos un correo de destinatario.',
            'asunto.required' => 'Escribe el asunto.',
            'cuerpo.required' => 'Escribe el mensaje.',
            'archivo_adjunto.max' => 'El archivo adjunto no puede superar los 20 MB.',
        ]);

        // Procesar destinatarios principales
        $paraList = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $datos['para']))));
        if (empty($paraList)) {
            return back()->withInput()->withErrors(['para' => 'Indica al menos un correo válido.']);
        }
        foreach ($paraList as $email) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->withInput()->withErrors(['para' => "El correo destinatario «{$email}» no tiene un formato válido."]);
            }
        }

        // Procesar destinatarios en copia (CC)
        $ccList = [];
        if (! empty($datos['cc'])) {
            $ccList = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $datos['cc']))));
            foreach ($ccList as $email) {
                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return back()->withInput()->withErrors(['cc' => "El correo en copia «{$email}» no tiene un formato válido."]);
                }
            }
        }

        // Procesar archivo adjunto desde PC
        $archivoInfo = null;
        if ($request->hasFile('archivo_adjunto') && $request->file('archivo_adjunto')->isValid()) {
            $file = $request->file('archivo_adjunto');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('mensajes_adjuntos', 'local');
            $archivoInfo = [
                'nombre' => $originalName,
                'ruta' => $path,
                'full_path' => storage_path('app/' . $path),
            ];
        }

        $mensaje = CorreoReservas::enviar(
            servicio: $servicio,
            tipo: $datos['tipo'],
            para: implode(', ', $paraList),
            asunto: $datos['asunto'],
            cuerpo: $datos['cuerpo'],
            conOrden: $request->boolean('con_orden'),
            usuario: $request->user(),
            cc: $ccList ? implode(', ', $ccList) : null,
            archivoInfo: $archivoInfo,
        );

        if ($mensaje->fallido) {
            return back()->withInput()->with('error', 'No se pudo enviar el correo: el mensaje quedó guardado como no enviado. Revisa la configuración de reservas@ o inténtalo de nuevo.');
        }

        return redirect()->route('servicios.mensaje', $servicio)->with('success', MensajeProveedor::TIPOS[$datos['tipo']] . ' enviado exitosamente.');
    }

    public function descargarAdjunto(MensajeProveedor $mensaje)
    {
        if (! $mensaje->adjunto_ruta || ! Storage::disk('local')->exists($mensaje->adjunto_ruta)) {
            abort(404, 'El archivo adjunto no existe o fue removido.');
        }

        return Storage::disk('local')->download(
            $mensaje->adjunto_ruta,
            $mensaje->adjunto_nombre ?: basename($mensaje->adjunto_ruta)
        );
    }

    public function respuesta(Request $request, Servicio $servicio)
    {
        if (! $servicio->proveedor_id) {
            return back()->with('error', "«{$servicio->titulo}» no tiene proveedor.");
        }

        $datos = $request->validate([
            'resultado' => ['required', Rule::in(array_keys(MensajeProveedor::RESULTADOS))],
            'confirmacion' => 'nullable|required_if:resultado,confirmado|string|max:80',
            'detalle' => 'nullable|string|max:2000',
        ], [
            'resultado.required' => 'Indica qué respondió el proveedor.',
            'confirmacion.required_if' => 'Para dar por confirmado el servicio indica el n.º de confirmación del proveedor.',
        ]);

        CorreoReservas::registrarRespuesta(
            $servicio, $datos['resultado'], $datos['confirmacion'] ?? null,
            filled($datos['detalle'] ?? null) ? $datos['detalle'] : MensajeProveedor::RESULTADOS[$datos['resultado']], $request->user(),
        );

        return redirect()->route('servicios.mensaje', $servicio)->with('success', 'Respuesta registrada: ' . mb_strtolower(MensajeProveedor::RESULTADOS[$datos['resultado']]) . '.');
    }
}
