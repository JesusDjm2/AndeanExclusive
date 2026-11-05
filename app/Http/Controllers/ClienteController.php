<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Support\Paises;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Clientes titulares de programas (directos o agencias). */
class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total' => Cliente::count(),
            'directos' => Cliente::where('tipo', 'directo')->count(),
            'agencias' => Cliente::where('tipo', 'agencia')->count(),
            'con_viajes' => Cliente::has('programas')->count(),
        ];

        $clientes = Cliente::query()
            ->withCount('programas')
            ->withMax('programas', 'inicio')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->input('q')) . '%';
                $q->where(fn ($w) => $w->where('nombre', 'like', $term)
                    ->orWhere('empresa', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('telefono', 'like', $term));
            })
            ->when(array_key_exists((string) $request->input('tipo'), Cliente::TIPOS), fn ($q) => $q->where('tipo', $request->input('tipo')))
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('clientes.index', [
            'clientes' => $clientes,
            'tipos' => Cliente::TIPOS,
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        return view('clientes.form', ['cliente' => new Cliente(['tipo' => 'directo'])] + $this->catalogos());
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create(self::validar($request));

        return redirect()->route('clientes.edit', $cliente)->with('success', 'Cliente creado.');
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load(['programas' => fn ($q) => $q->with('paxs', 'pagos')]);

        return view('clientes.form', ['cliente' => $cliente] + $this->catalogos());
    }

    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update(self::validar($request));

        return redirect()->route('clientes.edit', $cliente)->with('success', 'Cambios guardados.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->programas()->exists()) {
            return back()->with('error', 'No se puede eliminar: el cliente tiene programas. Reasígnalos o elimínalos primero.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    /**
     * Reglas compartidas con el alta rápida desde el formulario de programas.
     * $prefix permite validar campos anidados (p. ej. 'cliente_nuevo.').
     */
    public static function reglas(string $prefix = ''): array
    {
        return [
            $prefix . 'tipo' => ['required', Rule::in(array_keys(Cliente::TIPOS))],
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'empresa' => 'nullable|string|max:255|required_if:' . $prefix . 'tipo,agencia',
            $prefix . 'email' => 'nullable|email|max:255',
            $prefix . 'telefono' => 'nullable|string|max:40',
            $prefix . 'pais' => ['nullable', Rule::in(array_keys(Paises::all()))],
            $prefix . 'idioma' => 'nullable|in:es,en',
            $prefix . 'origen' => ['nullable', Rule::in(array_keys(Cliente::ORIGENES))],
            $prefix . 'notas' => 'nullable|string|max:2000',
        ];
    }

    private static function validar(Request $request): array
    {
        return $request->validate(self::reglas(), [
            'empresa.required_if' => 'Indica el nombre de la agencia.',
        ]);
    }

    private function catalogos(): array
    {
        return ['tipos' => Cliente::TIPOS, 'origenes' => Cliente::ORIGENES, 'paises' => Paises::all()];
    }
}
