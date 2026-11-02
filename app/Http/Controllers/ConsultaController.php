<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\User;
use App\Support\Avisos;
use App\Support\Permisos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Consultas de posibles clientes: de la primera respuesta a la cotización. */
class ConsultaController extends Controller
{
    /** Pestaña => estados. */
    public const BANDEJAS = [
        'nuevas' => ['Nuevas', ['nueva']],
        'en_gestion' => ['En gestión', ['en_gestion']],
        'cotizadas' => ['Cotizadas', ['cotizada']],
        'ganadas' => ['Ganadas', ['ganada']],
        'perdidas' => ['Perdidas', ['perdida']],
    ];

    public function index(Request $request)
    {
        $bandeja = array_key_exists((string) $request->input('bandeja'), self::BANDEJAS) ? $request->input('bandeja') : 'nuevas';

        $base = Consulta::query()->when($request->filled('q'), function ($q) use ($request) {
            $texto = '%' . addcslashes(trim((string) $request->input('q')), '%_\\') . '%';
            $q->where(fn ($w) => $w->where('nombre', 'like', $texto)->orWhere('email', 'like', $texto)->orWhere('telefono', 'like', $texto)->orWhere('tour', 'like', $texto));
        });

        $conteos = collect(self::BANDEJAS)->map(fn ($b) => (clone $base)->whereIn('estado', $b[1])->count());
        $consultas = (clone $base)->with(['asignado:id,name', 'programa:id,codigo'])
            ->whereIn('estado', self::BANDEJAS[$bandeja][1])->latest()->orderByDesc('id')->paginate(25)->withQueryString();

        return view('consultas.index', compact('consultas', 'conteos', 'bandeja'));
    }

    public function create()
    {
        return view('consultas.create', ['origenes' => Cliente::ORIGENES]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:40',
            'idioma' => 'required|in:es,en',
            'origen' => ['required', Rule::in(array_keys(Cliente::ORIGENES))],
            'tour' => 'nullable|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'adultos' => 'nullable|integer|min:0|max:999',
            'ninos' => 'nullable|integer|min:0|max:999',
            'mensaje' => 'nullable|string|max:5000',
        ]);

        // Quien la carga a mano ya la está atendiendo.
        $consulta = Consulta::create($datos + ['estado' => 'en_gestion', 'asignado_id' => $request->user()->id, 'atendida_at' => now()]);

        return redirect()->route('consultas.show', $consulta)->with('success', 'Consulta registrada.');
    }

    public function show(Consulta $consulta)
    {
        $consulta->load(['asignado', 'cliente', 'programa']);
        $equipo = User::role(['operaciones', Permisos::ADMIN])->orderBy('name')->get(['id', 'name']);

        return view('consultas.show', compact('consulta', 'equipo'));
    }

    public function update(Request $request, Consulta $consulta)
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(['nueva', 'en_gestion', 'perdida'])],
            'motivo_perdida' => ['nullable', 'required_if:estado,perdida', Rule::in(array_keys(Consulta::MOTIVOS_PERDIDA))],
            'asignado_id' => 'nullable|exists:users,id',
            'notas' => 'nullable|string|max:5000',
        ], ['motivo_perdida.required_if' => 'Indica por qué se perdió la consulta.']);

        // Con programa, el estado lo define el programa (ver Consulta::seguirA).
        if ($consulta->programa_id) {
            unset($datos['estado'], $datos['motivo_perdida']);
        } else {
            $datos['motivo_perdida'] = $datos['estado'] === 'perdida' ? $datos['motivo_perdida'] : null;
            if ($datos['estado'] !== 'nueva' && ! $consulta->atendida_at) {
                $datos['atendida_at'] = now();
            }
        }

        $consulta->update($datos);
        if ($consulta->estado !== 'nueva') {
            Avisos::resolver("consulta:{$consulta->id}");
        }

        return back()->with('success', 'Consulta actualizada.');
    }

    /** Lleva a una cotización nueva con los datos de la consulta y su cliente ya elegido. */
    public function cotizar(Request $request, Consulta $consulta)
    {
        if ($consulta->programa_id) {
            return redirect()->route('programas.edit', $consulta->programa_id);
        }

        $consulta->update(['asignado_id' => $consulta->asignado_id ?? $request->user()->id]);
        $cliente = $consulta->clienteOCrear();

        return redirect()->route('programas.create', ['cliente_id' => $cliente->id, 'consulta' => $consulta->id]);
    }
}
