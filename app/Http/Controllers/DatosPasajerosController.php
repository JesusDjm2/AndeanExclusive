<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Support\PassportFiles;
use App\Support\Permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Datos de los viajeros desde el enlace del itinerario: solo un administrador con sesión iniciada (no el cliente).
 * Solo mientras el viaje no haya terminado ni esté cancelado. Al guardar, el equipo recibe una tarea para revisarlos.
 */
class DatosPasajerosController extends Controller
{
    public const MAX_PASAJEROS = 20;

    public const TAREA_REVISION = 'Revisar datos de pasajeros enviados por el cliente';

    public function edit(Programa $programa)
    {
        abort_unless(auth()->user()?->hasRole(Permisos::ADMIN), 403, 'El registro de pasajeros por enlace es solo para administradores.');
        abort_unless($this->abierto($programa), 404);
        $programa->load('paxs');

        return view('programas.pasajeros-cliente', ['programa' => $programa]);
    }

    public function update(Request $request, Programa $programa)
    {
        abort_unless(auth()->user()?->hasRole(Permisos::ADMIN), 403, 'El registro de pasajeros por enlace es solo para administradores.');
        abort_unless($this->abierto($programa), 404);

        $isEn = $programa->lang === 'en';
        $request->validate(
            ['paxs' => 'required|array|max:' . self::MAX_PASAJEROS, 'paxs.*.id' => 'nullable|integer'] + ProgramaController::reglasPax('paxs.*.'),
            $isEn ? [] : [
                'paxs.*.nombre.required' => 'Falta el nombre.',
                'paxs.*.fecha_nacimiento.required' => 'Falta la fecha de nacimiento.',
                'paxs.*.pais.required' => 'Elija la nacionalidad.',
            ],
            $isEn ? [] : ['paxs.*.pasaporte_archivo' => 'pasaporte escaneado']
        );

        DB::transaction(function () use ($request, $programa) {
            foreach ((array) $request->input('paxs') as $i => $datos) {
                $pax = ! empty($datos['id']) ? $programa->paxs()->find($datos['id']) : null;
                if (! $pax && ! empty($datos['id'])) {
                    continue; // id ajeno al programa: se ignora
                }

                $valores = collect($datos)->only([
                    'nombre', 'fecha_nacimiento', 'sexo', 'pais', 'pasaporte', 'pasaporte_vencimiento', 'dieta',
                    'alergias', 'condiciones_medicas', 'emergencia_nombre', 'emergencia_telefono',
                ])->map(fn ($v) => $v === '' ? null : $v)->all() + ['actualizado_por_cliente_at' => now()];

                // El n.º de pasaporte guardado no se muestra en la página (solo enmascarado): vacío = conservarlo.
                if ($pax && blank($valores['pasaporte'] ?? null)) {
                    unset($valores['pasaporte']);
                }

                if ($request->hasFile("paxs.$i.pasaporte_archivo")) {
                    $valores['pasaporte_archivo'] = PassportFiles::store($request->file("paxs.$i.pasaporte_archivo"));
                    PassportFiles::delete($pax?->pasaporte_archivo);
                }

                $pax ? $pax->update($valores) : $programa->paxs()->create($valores);
            }

            if (! $programa->tareas()->whereNull('completada_at')->where('titulo', self::TAREA_REVISION)->exists()) {
                $programa->tareas()->create(['titulo' => self::TAREA_REVISION, 'fecha_limite' => now()->addDay()->toDateString()]);
            }
        });

        return redirect()->route('itinerario.pasajeros', $programa)
            ->with('success', $programa->lang === 'en' ? 'Thank you! We have received your travelers’ details.' : '¡Gracias! Recibimos los datos de los viajeros.');
    }

    private function abierto(Programa $programa): bool
    {
        return $programa->estado !== 'cancelado' && $programa->faseViaje() !== 'finalizado';
    }
}
