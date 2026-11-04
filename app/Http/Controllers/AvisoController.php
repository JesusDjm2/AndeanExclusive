<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\Programa;
use Illuminate\Http\Request;

/** Notificaciones entre áreas: cada persona ve las de su área, con su propio estado de visto. */
class AvisoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $filtro = in_array($request->input('filtro'), ['no_vistas', 'vistas', 'todas'], true) ? $request->input('filtro') : 'no_vistas';
        $base = Aviso::query()->paraUsuario($user)->when($request->filled('area'), fn ($q) => $q->where('area', $request->input('area')));

        $conteos = [
            'no_vistas' => (clone $base)->noVistosPor($user)->count(),
            'vistas' => (clone $base)->vistosPor($user)->count(),
            'todas' => (clone $base)->count(),
        ];

        $avisos = (clone $base)
            ->when($filtro === 'no_vistas', fn ($q) => $q->noVistosPor($user))
            ->when($filtro === 'vistas', fn ($q) => $q->vistosPor($user))
            ->conVisto($user)
            ->latest('updated_at')
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('avisos.index', compact('avisos', 'filtro', 'conteos'));
    }

    /** Marca como vista y lleva a donde se hace lo que pide. */
    public function abrir(Request $request, int $aviso)
    {
        $aviso = Aviso::query()->paraUsuario($request->user())->findOrFail($aviso);
        $aviso->marcarVisto($request->user());

        if ($aviso->programa_id && ! Programa::whereKey($aviso->programa_id)->exists()) {
            return redirect()->route('avisos.index', ['filtro' => 'vistas'])->with('error', "El programa {$aviso->programa_codigo} ya no existe.");
        }

        return redirect()->to(route($aviso->ruta, $aviso->ruta_params ?? []) . ($aviso->ancla ? "#{$aviso->ancla}" : ''));
    }

    /** Vista ⇄ no vista. */
    public function marcar(Request $request, int $aviso)
    {
        $aviso = Aviso::query()->paraUsuario($request->user())->findOrFail($aviso);
        $request->boolean('visto') ? $aviso->marcarVisto($request->user()) : $aviso->marcarNoVisto($request->user());

        return back();
    }

    public function marcarTodas(Request $request)
    {
        Aviso::query()->paraUsuario($request->user())->noVistosPor($request->user())->get()
            ->each(fn (Aviso $aviso) => $aviso->marcarVisto($request->user()));

        return back()->with('success', 'Todas las notificaciones quedaron como vistas.');
    }

    /** Contador y lista de la campana (se actualizan solos cada minuto). */
    public function resumen(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'noVistas' => Aviso::query()->paraUsuario($user)->noVistosPor($user)->count(),
            'html' => view('avisos.partials.items', ['avisos' => self::recientes($request)])->render(),
        ]);
    }

    public static function recientes(Request $request)
    {
        $user = $request->user();

        return Aviso::query()->paraUsuario($user)->noVistosPor($user)->conVisto($user)->latest('updated_at')->orderByDesc('id')->limit(8)->get();
    }
}
