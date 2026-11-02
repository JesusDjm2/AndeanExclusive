<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Support\Permisos;
use App\Support\Respaldos;
use Illuminate\Http\Request;
use Throwable;

/** Copias de seguridad, solo para Administración: contienen todos los datos de la agencia. */
class RespaldoController extends Controller
{
    public function index()
    {
        return view('respaldos.index', ['respaldos' => Respaldos::listar()]);
    }

    public function store(Request $request)
    {
        try {
            Respaldos::crear();
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'No se pudo crear la copia. Revisa el espacio disponible en el servidor.');
        }
        $this->anotar($request, 'creó una copia de seguridad', 'Copia manual');

        return back()->with('success', 'Copia creada.');
    }

    public function descargar(Request $request, string $nombre)
    {
        $ruta = Respaldos::ruta($nombre);
        abort_unless($ruta, 404);
        $this->anotar($request, 'descargó una copia de seguridad', $nombre);

        return response()->download($ruta)->setPrivate();
    }

    private function anotar(Request $request, string $verbo, string $titulo): void
    {
        rescue(fn () => Movimiento::create([
            'user_id' => $request->user()->id, 'usuario' => $request->user()->name, 'area' => Permisos::area($request->user()),
            'verbo' => $verbo, 'modulo' => 'Sistema', 'titulo' => $titulo,
        ]));
    }
}
