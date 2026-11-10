<?php

namespace App\Http\Controllers;

use App\Models\TarifarioAjuste;
use App\Models\TarifarioCategoria;
use App\Models\TarifarioServicio;
use App\Support\DesgloseTarifario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        $listaCategorias = TarifarioCategoria::mapa();

        if ($cat !== '' && array_key_exists($cat, $listaCategorias)) {
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
            'ceremonias'   => TarifarioServicio::where('categoria', 'ceremonia')->count(),
            'treks'        => TarifarioServicio::where('categoria', 'trek')->count(),
            'boletos'      => TarifarioServicio::where('categoria', 'boleto')->count(),
            'alimentacion' => TarifarioServicio::where('categoria', 'alimentacion')->count(),
            'componentes'  => TarifarioServicio::where('categoria', 'componente')->count(),
        ];

        $tipoCambio = TarifarioAjuste::tipoCambio();
        $categoriasLista = TarifarioCategoria::ordenadas();

        return view('tarifario-maestro.index', compact('servicios', 'q', 'cat', 'stats', 'tipoCambio', 'categoriasLista', 'listaCategorias'));
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
            'servicio'   => $tarifarioServicio,
            'categorias' => TarifarioCategoria::mapa(),
        ]);
    }

    public function update(Request $request, TarifarioServicio $tarifarioServicio): RedirectResponse
    {
        $categoriasValidas = array_keys(TarifarioCategoria::mapa());

        $data = $request->validate([
            'nombre'              => ['required', 'string', 'max:255'],
            'categoria'           => ['required', 'string', Rule::in($categoriasValidas)],
            'descripcion'         => ['nullable', 'string'],
            'lugares_visita'      => ['nullable', 'string'],
            'duracion_horas'      => ['nullable', 'numeric', 'min:0'],
            'comision_pct'        => ['required', 'numeric', 'min:0', 'max:100'],
            'aplica_igv_comision' => ['nullable', 'boolean'],
            'precio_adulto'       => ['nullable', 'numeric', 'min:0'],
            'precio_nino'         => ['nullable', 'numeric', 'min:0'],
            'costo_grupo'         => ['nullable', 'numeric', 'min:0'],
            'notas'               => ['nullable', 'string'],
        ]);

        $data['aplica_igv_comision'] = (bool) $request->input('aplica_igv_comision', true);

        $tarifarioServicio->update($data);

        return redirect()->route('tarifario-maestro.show', $tarifarioServicio)
                         ->with('exito', "Servicio maestro [{$tarifarioServicio->codigo}] actualizado correctamente.");
    }

    /**
     * Actualiza el tipo de cambio oficial de referencia del Tarifario Maestro
     * y opcionalmente recalcula los costos en USD de boletos oficiales expresados en Soles.
     */
    public function actualizarTipoCambio(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_cambio'        => ['required', 'numeric', 'min:0.1', 'max:100'],
            'recalcular_boletos' => ['nullable', 'boolean'],
        ]);

        $nuevoTc = (float) $validated['tipo_cambio'];
        TarifarioAjuste::set('tipo_cambio', number_format($nuevoTc, 2, '.', ''), 'Tipo de cambio oficial de referencia (Soles por Dólar)');

        $mensajeExtra = '';

        if ($request->boolean('recalcular_boletos')) {
            // Boletos oficiales con precio fijado por el Estado Peruano en Soles (PEN)
            $boletosSoles = [
                'BTG'                          => ['adulto' => 130.00, 'nino' => 70.00],
                'BTP-I'                        => ['adulto' => 70.00,  'nino' => 40.00],
                'BTP-II'                       => ['adulto' => 70.00,  'nino' => 40.00],
                'BTP-III'                      => ['adulto' => 70.00,  'nino' => 40.00],
                'ENT-CATEDRAL'                 => ['adulto' => 40.00,  'nino' => 20.00],
                'ENT-QORIKANCHA'               => ['adulto' => 20.00,  'nino' => 10.00],
                'ENT-MARAS'                    => ['adulto' => 20.00,  'nino' => null],
                'ENT-SAN-PEDRO'                => ['adulto' => 20.00,  'nino' => 10.00],
                'ENT-MUSEO-INKA'               => ['adulto' => 20.00,  'nino' => null],
                'ENT-ANDAHUAYLILLAS'           => ['adulto' => 15.00,  'nino' => null],
                'ENT-MACHUPICCHU-CIRCUITO'     => ['adulto' => 152.00, 'nino' => 70.00],
                'ENT-MACHUPICCHU-HUAYNAPICCHU' => ['adulto' => 200.00, 'nino' => null],
                'ENT-TIPON-PIKILLAQTA'         => ['adulto' => 20.00,  'nino' => null],
                'ENT-CHOQUEQUIRAO'             => ['adulto' => 60.00,  'nino' => 30.00],
            ];

            $actualizados = 0;
            foreach ($boletosSoles as $codigo => $precios) {
                $servicio = TarifarioServicio::where('codigo', $codigo)->first();
                if ($servicio) {
                    $precioAdultoUsd = round($precios['adulto'] / $nuevoTc, 2);
                    $precioNinoUsd = $precios['nino'] !== null ? round($precios['nino'] / $nuevoTc, 2) : null;

                    $servicio->update([
                        'precio_adulto' => $precioAdultoUsd,
                        'precio_nino'   => $precioNinoUsd,
                        'notas'         => "Tarifa oficial en Soles convertida a T/C {$nuevoTc}. Requiere pasaporte original en ingreso.",
                    ]);
                    $actualizados++;
                }
            }
            $mensajeExtra = " y se recalcularon {$actualizados} boletos oficiales en Soles";
        }

        return redirect()->route('tarifario-maestro.index')
            ->with('exito', "Tipo de cambio oficial actualizado a S/ " . number_format($nuevoTc, 2) . "{$mensajeExtra}.");
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
                  ->orWhere('lugares_visita', 'like', "%{$q}%")
                  // Las notas guardan los códigos alternos de la hoja (HTLAPT, OLLCUS, URUAPT…).
                  ->orWhere('notas', 'like', "%{$q}%");
            });
        }

        $items = $query->orderBy('id')->limit(250)->get()->map(function (TarifarioServicio $item) use ($pax, $adultos, $ninos, $feriado) {
            $costo = $item->costoPorPax($adultos, $ninos, $feriado);

            return [
                'id'              => $item->id,
                'codigo'          => $item->codigo,
                'nombre'          => $item->nombre,
                'categoria'       => $item->categoria,
                'categoria_label' => TarifarioServicio::CATEGORIAS[$item->categoria] ?? ucfirst($item->categoria),
                'tipo_calculo'    => $item->tipo_calculo,
                'duracion_horas'  => $item->duracion_horas,
                'moneda'          => $item->moneda,
                'costo_pax'       => round($costo, 2),
                'comision_pct'    => (float) $item->comision_pct,
                'precio_adulto'   => $item->precio_adulto ? (float) $item->precio_adulto : null,
                'precio_nino'     => $item->precio_nino ? (float) $item->precio_nino : null,
                'adultos'         => $adultos,
                'ninos'           => $ninos,
                'total_pax'       => $pax,
            ];
        });

        return response()->json([
            'items' => $items,
        ]);
    }

    /**
     * Partes que propone un servicio del catálogo (guía, transporte, entradas, almuerzo…) para revisarlas en el modal
     * del itinerario antes de agregarlas como filas separadas. No modifica el catálogo.
     */
    public function componentes(Request $request, TarifarioServicio $tarifarioServicio): JsonResponse
    {
        $adultos = max(1, min(20, (int) $request->query('adultos', 2)));
        $ninos = max(0, min(20, (int) $request->query('ninos', 0)));
        $feriado = filter_var($request->query('feriado', false), FILTER_VALIDATE_BOOLEAN);

        $desglose = (new DesgloseTarifario($tarifarioServicio, $adultos, $ninos, $feriado))->calcular();

        return response()->json([
            'codigo' => $tarifarioServicio->codigo,
            'nombre' => $tarifarioServicio->nombre,
            'categoria' => $tarifarioServicio->categoria,
            'adultos' => $adultos,
            'ninos' => $ninos,
            'feriado' => $feriado,
            ...$desglose,
        ]);
    }
}
