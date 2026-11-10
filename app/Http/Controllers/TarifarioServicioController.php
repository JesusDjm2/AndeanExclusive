<?php

namespace App\Http\Controllers;

use App\Models\TarifarioServicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TarifarioServicioController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $cat = (string) $request->query('cat', '');

        $query = TarifarioServicio::query()->activo();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('codigo', 'like', "%{$q}%")
                  ->orWhere('nombre', 'like', "%{$q}%")
                  ->orWhere('lugares_visita', 'like', "%{$q}%")
                  ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        if ($cat !== '' && array_key_exists($cat, TarifarioServicio::CATEGORIAS)) {
            $query->where('categoria', $cat);
        }

        $servicios = $query->orderBy('categoria')
                           ->orderBy('codigo')
                           ->paginate(25)
                           ->withQueryString();

        $stats = [
            'total'        => TarifarioServicio::count(),
            'traslados'    => TarifarioServicio::where('categoria', 'traslado')->count(),
            'tours'        => TarifarioServicio::where('categoria', 'tour')->count(),
            'treks'        => TarifarioServicio::where('categoria', 'trek')->count(),
            'boletos'      => TarifarioServicio::where('categoria', 'boleto')->count(),
            'alimentacion' => TarifarioServicio::where('categoria', 'alimentacion')->count(),
        ];

        return view('tarifario-maestro.index', compact('servicios', 'q', 'cat', 'stats'));
    }

    public function show(TarifarioServicio $tarifarioServicio): View
    {
        return view('tarifario-maestro.show', [
            'servicio' => $tarifarioServicio,
        ]);
    }

    public function edit(TarifarioServicio $tarifarioServicio): View
    {
        return view('tarifario-maestro.form', [
            'servicio' => $tarifarioServicio,
        ]);
    }

    public function update(Request $request, TarifarioServicio $tarifarioServicio)
    {
        $data = $request->validate([
            'nombre'              => ['required', 'string', 'max:255'],
            'categoria'           => ['required', 'string', 'in:' . implode(',', array_keys(TarifarioServicio::CATEGORIAS))],
            'descripcion'         => ['nullable', 'string'],
            'lugares_visita'      => ['nullable', 'string'],
            'duracion_horas'      => ['nullable', 'numeric', 'min:0'],
            'comision_pct'        => ['required', 'numeric', 'min:0', 'max:100'],
            'aplica_igv_comision' => ['nullable', 'boolean'],
            'precio_adulto'       => ['nullable', 'numeric', 'min:0'],
            'precio_nino'         => ['nullable', 'numeric', 'min:0'],
            'notas'               => ['nullable', 'string'],
        ]);

        $data['aplica_igv_comision'] = (bool) $request->input('aplica_igv_comision', true);

        $tarifarioServicio->update($data);

        return redirect()->route('tarifario-maestro.show', $tarifarioServicio)
                         ->with('exito', "Servicio maestro [{$tarifarioServicio->codigo}] actualizado correctamente.");
    }

    /**
     * Endpoint API JSON: cotiza un servicio según número de pasajeros, feriado y tipo de pasajero.
     */
    public function cotizar(Request $request): JsonResponse
    {
        $codigo = $request->query('codigo');
        $id = $request->query('id');
        $pax = max(1, (int) $request->query('pax', 1));
        $feriado = filter_var($request->query('feriado', false), FILTER_VALIDATE_BOOLEAN);
        $tipoPax = in_array($request->query('tipo_pax'), ['nino', 'infante']) ? 'nino' : 'adulto';

        $servicio = null;
        if ($codigo) {
            $servicio = TarifarioServicio::where('codigo', $codigo)->first();
        } elseif ($id) {
            $servicio = TarifarioServicio::find($id);
        }

        if (! $servicio) {
            return response()->json([
                'error' => 'Servicio maestro no encontrado.',
            ], 404);
        }

        $calc = $servicio->calcularFinanciero($pax, $feriado, $tipoPax);

        return response()->json([
            'ok'        => true,
            'id'        => $servicio->id,
            'codigo'    => $servicio->codigo,
            'nombre'    => $servicio->nombre,
            'categoria' => $servicio->categoria,
            'calculo'   => $calc,
        ]);
    }

    /**
     * Endpoint API JSON: búsqueda rápida para autocompletar en el formulario de programas.
     */
    public function buscar(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $categoria = $request->query('categoria');
        $adultos = max(1, (int) $request->query('adultos', $request->query('pax', 2)));
        $ninos = max(0, (int) $request->query('ninos', 0));
        $pax = max(1, $adultos + $ninos);
        $feriado = filter_var($request->query('feriado', false), FILTER_VALIDATE_BOOLEAN);

        $query = TarifarioServicio::query()->activo();

        if ($categoria && array_key_exists($categoria, TarifarioServicio::CATEGORIAS)) {
            $query->where('categoria', $categoria);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('codigo', 'like', "%{$q}%")
                  ->orWhere('nombre', 'like', "%{$q}%")
                  ->orWhere('lugares_visita', 'like', "%{$q}%");
            });
        }

        $items = $query->limit(40)->get()->map(function (TarifarioServicio $item) use ($pax, $adultos, $ninos, $feriado) {
            if ($item->tipo_calculo === 'por_persona' && $item->precio_nino > 0) {
                $totalCosto = ($adultos * ($item->precio_adulto ?? 0)) + ($ninos * ($item->precio_nino ?? 0));
                $costo = $pax > 0 ? ($totalCosto / $pax) : ($item->precio_adulto ?? 0);
            } else {
                $costo = $item->calcularCosto($pax, $feriado);
            }

            return [
                'id'             => $item->id,
                'codigo'         => $item->codigo,
                'nombre'         => $item->nombre,
                'categoria'      => $item->categoria,
                'tipo_calculo'   => $item->tipo_calculo,
                'duracion_horas' => $item->duracion_horas,
                'moneda'         => $item->moneda,
                'costo_pax'      => round($costo, 2),
                'comision_pct'   => (float) $item->comision_pct,
                'precio_adulto'  => $item->precio_adulto ? (float) $item->precio_adulto : null,
                'precio_nino'    => $item->precio_nino ? (float) $item->precio_nino : null,
                'adultos'        => $adultos,
                'ninos'          => $ninos,
                'total_pax'      => $pax,
            ];
        });

        return response()->json([
            'items' => $items,
        ]);
    }
}
