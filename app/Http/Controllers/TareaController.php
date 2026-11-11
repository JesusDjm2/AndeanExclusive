<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Tareas con fecha límite de un programa (pestaña "Tareas" del formulario). */
class TareaController extends Controller
{
    public function store(Request $request, Programa $programa)
    {
        $datos = $request->validateWithBag('tarea', [
            'titulo' => 'required|string|max:255',
            'fecha_limite' => 'nullable|date',
            'notas' => 'nullable|string|max:255',
        ], ['titulo.required' => 'Describe la tarea.']);

        $programa->tareas()->create($datos);

        return $this->volver($programa, 'Tarea agregada.');
    }

    /** Agrega tareas de la lista de control estándar; la fecha límite se calcula desde el inicio del viaje. */
    public function plantillas(Request $request, Programa $programa)
    {
        $request->validate([
            'plantillas' => 'required|array',
            'plantillas.*' => [Rule::in(array_keys(Tarea::PLANTILLAS))],
        ], ['plantillas.required' => 'Elige al menos una tarea.']);

        $existentes = $programa->tareas()->pluck('titulo');
        $inicio = $programa->inicio ? Carbon::parse($programa->inicio) : null;
        $creadas = 0;

        foreach ($request->input('plantillas') as $clave) {
            [$titulo, $diasAntes] = Tarea::PLANTILLAS[$clave];
            if ($existentes->contains($titulo)) {
                continue;
            }
            $programa->tareas()->create([
                'titulo' => $titulo,
                'fecha_limite' => $inicio?->copy()->subDays($diasAntes),
            ]);
            $creadas++;
        }

        return $this->volver($programa, $creadas ? "Se agregaron {$creadas} tareas." : 'Esas tareas ya estaban en la lista.');
    }

    public function toggle(Programa $programa, Tarea $tarea)
    {
        $tarea->update($tarea->completada_at
            ? ['completada_at' => null, 'completada_por' => null]
            : ['completada_at' => now(), 'completada_por' => auth()->id()]);

        if (request()->wantsJson() || request()->ajax()) {
            $pendientes = $programa->tareas()->whereNull('completada_at')->count();
            $total = $programa->tareas()->count();
            $vencidas = $programa->tareas()->whereNull('completada_at')->where('fecha_limite', '<', now()->startOfDay())->count();

            return response()->json([
                'ok' => true,
                'tarea_id' => $tarea->id,
                'completada' => (bool) $tarea->completada_at,
                'completada_at' => $tarea->completada_at ? $tarea->completada_at->locale('es')->isoFormat('D MMM') : null,
                'completada_por' => $tarea->completadaPor?->name,
                'situacion' => $tarea->situacion(),
                'pendientes' => $pendientes,
                'total' => $total,
                'vencidas' => $vencidas,
                'mensaje' => $tarea->completada_at ? 'Tarea completada.' : 'Tarea reabierta como pendiente.',
            ]);
        }

        if (request()->input('volver') === 'ficha') {
            return redirect()->to(route('programas.ficha', $programa) . '#tab-tareas')
                ->with('success', $tarea->completada_at ? 'Tarea completada.' : 'Tarea reabierta.');
        }

        return request()->input('volver') === 'home'
            ? redirect()->route('home')->with('success', 'Tarea actualizada.')
            : $this->volver($programa, $tarea->completada_at ? 'Tarea completada.' : 'Tarea reabierta.');
    }

    public function destroy(Programa $programa, Tarea $tarea)
    {
        $tarea->delete();

        return $this->volver($programa, 'Tarea eliminada.');
    }

    private function volver(Programa $programa, string $mensaje)
    {
        if (request()->input('volver') === 'ficha') {
            return redirect()->to(route('programas.ficha', $programa) . '#tab-tareas')->with('success', $mensaje);
        }

        return redirect()->route('programas.edit', [$programa, 'tab' => 'tareas'])->with('success', $mensaje);
    }
}
