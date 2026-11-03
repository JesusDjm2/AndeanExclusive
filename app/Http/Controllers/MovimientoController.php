<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;

/** Movimientos de la agencia: todas las áreas ven lo que pasa en las demás. */
class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $movimientos = Movimiento::query()
            ->with(['programa' => fn ($q) => $q->select(['id', 'codigo', 'nombre', 'cliente_id', 'inicio', 'fin', 'estado'])->with(['cliente:id,nombre', 'paxs:id,programa_id'])])
            ->when($request->filled('area'), fn ($q) => $q->where('area', $request->input('area')))
            ->when($request->filled('modulo'), fn ($q) => $q->where('modulo', $request->input('modulo')))
            ->when($request->date('desde'), fn ($q, $fecha) => $q->whereDate('created_at', '>=', $fecha))
            ->when($request->date('hasta'), fn ($q, $fecha) => $q->whereDate('created_at', '<=', $fecha))
            ->when($request->filled('q'), function ($q) use ($request) {
                $texto = '%' . addcslashes((string) $request->input('q'), '%_\\') . '%';
                $q->where(fn ($w) => $w->where('titulo', 'like', $texto)->orWhere('usuario', 'like', $texto)->orWhere('programa_codigo', 'like', $texto));
            })
            ->latest()
            ->orderByDesc('id')
            ->paginate(40)
            ->withQueryString();

        return view('movimientos.index', compact('movimientos'));
    }
}
