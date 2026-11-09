<?php

namespace App\Http\Controllers;

use App\Models\MensajeProveedor;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Support\CorreoReservas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Mensajería de Reservas con los proveedores: bandeja, redacción por servicio y respuestas registradas. */
class MensajeProveedorController extends Controller
{
    public function index(Request $request)
    {
        $mensajes = MensajeProveedor::query()
            ->with(['proveedor:id,nombre', 'servicio:id,titulo,fecha,estado,programa_id', 'programa:id,codigo,nombre', 'user:id,name'])
            ->when($request->filled('proveedor_id'), fn ($q) => $q->where('proveedor_id', $request->integer('proveedor_id')))
            ->when(array_key_exists((string) $request->input('tipo'), MensajeProveedor::TIPOS), fn ($q) => $q->where('tipo', $request->input('tipo')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->input('q')) . '%';
                $q->where(fn ($w) => $w->where('asunto', 'like', $term)
                    ->orWhereHas('programa', fn ($p) => $p->where('codigo', 'like', $term)->orWhere('nombre', 'like', $term)));
            })
            ->latest()->orderByDesc('id')
            ->paginate(25)->withQueryString();

        // Esperando respuesta: servicios solicitados cuyo último mensaje fue nuestro.
        $esperando = Servicio::query()
            ->where('estado', 'solicitado')
            ->whereHas('mensajes')
            ->with(['programa:id,codigo,nombre', 'proveedor:id,nombre', 'mensajes' => fn ($q) => $q->latest()->limit(1)])
            ->get()
            ->filter(fn (Servicio $s) => $s->mensajes->first()?->direccion === 'enviado')
            ->sortBy(fn (Servicio $s) => $s->mensajes->first()->created_at)
            ->values();

        $proveedores = Proveedor::query()->whereHas('mensajes')->orderBy('nombre')->get(['id', 'nombre']);

        return view('mensajes.index', compact('mensajes', 'esperando', 'proveedores'));
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
            'para' => 'required|email:rfc|max:255',
            'asunto' => 'required|string|max:255',
            'cuerpo' => 'required|string|max:10000',
            'con_orden' => 'nullable|boolean',
        ], [
            'para.required' => 'Indica el correo del proveedor.',
            'para.email' => 'El correo del proveedor no es válido.',
            'asunto.required' => 'Escribe el asunto.',
            'cuerpo.required' => 'Escribe el mensaje.',
        ]);

        $mensaje = CorreoReservas::enviar(
            $servicio, $datos['tipo'], trim($datos['para']), $datos['asunto'], $datos['cuerpo'], $request->boolean('con_orden'), $request->user(),
        );

        if ($mensaje->fallido) {
            return back()->withInput()->with('error', 'No se pudo enviar el correo: el mensaje quedó guardado como no enviado. Revisa la configuración de reservas@ o inténtalo de nuevo.');
        }

        return redirect()->route('servicios.mensaje', $servicio)->with('success', MensajeProveedor::TIPOS[$datos['tipo']] . " enviada a {$datos['para']}.");
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
