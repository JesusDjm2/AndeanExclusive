<?php

namespace App\Http\Controllers;

use App\Models\TarifarioCategoria;
use App\Models\TarifarioServicio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TarifarioCategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = TarifarioCategoria::withCount('servicios')
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view('tarifario-categorias.index', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'slug'   => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:tarifario_categorias,slug'],
            'icono'  => ['nullable', 'string', 'max:50'],
            'orden'  => ['nullable', 'integer', 'min:0'],
        ]);

        $slug = ! empty($validated['slug'])
            ? $validated['slug']
            : TarifarioCategoria::generarSlug($validated['nombre']);

        TarifarioCategoria::create([
            'slug'   => $slug,
            'nombre' => $validated['nombre'],
            'icono'  => $validated['icono'] ?: 'fa-tag',
            'orden'  => (int) ($validated['orden'] ?? 0),
            'activo' => true,
        ]);

        return redirect()->route('tarifario-categorias.index')
            ->with('exito', "Categoría [{$validated['nombre']}] creada correctamente.");
    }

    public function update(Request $request, TarifarioCategoria $categoria): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'slug'   => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('tarifario_categorias', 'slug')->ignore($categoria->id)],
            'icono'  => ['nullable', 'string', 'max:50'],
            'orden'  => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $oldSlug = $categoria->slug;
        $newSlug = $validated['slug'];

        $categoria->update([
            'nombre' => $validated['nombre'],
            'slug'   => $newSlug,
            'icono'  => $validated['icono'] ?: 'fa-tag',
            'orden'  => (int) ($validated['orden'] ?? 0),
            'activo' => (bool) $request->input('activo', true),
        ]);

        // Si el identificador (slug) cambió, sincronizar los servicios existentes para no dejarlos huérfanos
        if ($oldSlug !== $newSlug) {
            TarifarioServicio::where('categoria', $oldSlug)->update(['categoria' => $newSlug]);
        }

        return redirect()->route('tarifario-categorias.index')
            ->with('exito', "Categoría [{$categoria->nombre}] actualizada correctamente.");
    }

    public function destroy(TarifarioCategoria $categoria): RedirectResponse
    {
        $cantServicios = TarifarioServicio::where('categoria', $categoria->slug)->count();

        if ($cantServicios > 0) {
            return redirect()->route('tarifario-categorias.index')
                ->with('error', "No se puede eliminar la categoría [{$categoria->nombre}] porque tiene {$cantServicios} servicio(s) asociado(s). Reasigna los servicios a otra categoría antes de eliminarla.");
        }

        $nombre = $categoria->nombre;
        $categoria->delete();

        return redirect()->route('tarifario-categorias.index')
            ->with('exito', "Categoría [{$nombre}] eliminada con éxito.");
    }
}
