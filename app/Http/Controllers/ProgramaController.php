<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Mes;
use App\Models\Programa;
use App\Http\Controllers\Controller;
use App\Models\Agente;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Pax;
use App\Models\Proveedor;
use App\Mail\ProgramaBienvenidaMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProgramaController extends Controller
{
    public function index(Request $request)
    {
        $query = Programa::query()
            ->with(['agentes', 'paxs', 'habitaciones.hotel', 'proveedores.categoria', 'habitacionesFechas', 'anio', 'mes']);

        if ($request->filled('anio_id')) {
            $query->where('anio_id', $request->integer('anio_id'));
        }
        if ($request->filled('mes_id')) {
            $query->where('mes_id', $request->integer('mes_id'));
        }

        $programas = $query->latest()->get();
        $anios = Anio::query()->orderByDesc('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();

        return view('programas.index', compact('programas', 'anios', 'meses'));
    }

    /**
     * Vista exploración: elegir año → mes → listado visual de programas.
     */
    public function porPeriodo(Request $request)
    {
        $anios = Anio::query()->orderBy('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();

        $countsByAnio = Programa::query()
            ->whereNotNull('anio_id')
            ->selectRaw('anio_id, COUNT(*) as total')
            ->groupBy('anio_id')
            ->pluck('total', 'anio_id');

        $anioId = $request->filled('anio_id')
            ? $request->integer('anio_id')
            : null;

        if ($anioId !== null && !$anios->contains(fn ($a) => (int) $a->id === (int) $anioId)) {
            $anioId = null;
        }

        if ($anioId === null && $anios->isNotEmpty()) {
            $anioId = Anio::query()
                ->whereHas('programas', function ($q) {
                    $q->whereNotNull('mes_id');
                })
                ->orderByDesc('anio')
                ->value('id');

            if ($anioId === null) {
                $anioId = $anios->first()->id;
            }
        }

        $monthCounts = collect();
        if ($anioId) {
            $raw = Programa::query()
                ->where('anio_id', $anioId)
                ->whereNotNull('mes_id')
                ->selectRaw('mes_id, COUNT(*) as c')
                ->groupBy('mes_id')
                ->pluck('c', 'mes_id');
            foreach ($meses as $mes) {
                $monthCounts[$mes->id] = (int) ($raw[$mes->id] ?? 0);
            }
        }

        $mesId = $request->filled('mes_id') ? $request->integer('mes_id') : null;

        if ($mesId !== null && !$meses->contains(fn ($m) => (int) $m->id === (int) $mesId)) {
            $mesId = null;
        }

        $programas = collect();
        if ($anioId && $mesId) {
            $programas = Programa::query()
                ->with(['paxs', 'agentes', 'anio', 'mes'])
                ->where('anio_id', $anioId)
                ->where('mes_id', $mesId)
                ->orderBy('nombre')
                ->get();
        }

        $sinAsignarCount = Programa::query()
            ->where(function ($q) {
                $q->whereNull('anio_id')->orWhereNull('mes_id');
            })
            ->count();

        $anioActivo = $anioId ? $anios->firstWhere('id', $anioId) : null;
        $mesActivo = $mesId ? $meses->firstWhere('id', $mesId) : null;

        return view('programas.por-periodo', compact(
            'anios',
            'meses',
            'countsByAnio',
            'anioId',
            'mesId',
            'monthCounts',
            'programas',
            'sinAsignarCount',
            'anioActivo',
            'mesActivo',
        ));
    }

    public function create()
    {
        $agentes = Agente::all();
        $proveedorsPorCategoria = Proveedor::with('categoria')->get()->groupBy(fn($p) => $p->categoria->nombre);
        $hoteles = Hotel::with('habitaciones')->get();
        $anios = Anio::query()->orderByDesc('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();

        return view('programas.create', compact('agentes', 'proveedorsPorCategoria', 'hoteles', 'anios', 'meses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:programas,codigo',
            'email' => 'nullable|email|max:255',

            'inicio' => 'nullable',
            'fin' => 'nullable|date|after_or_equal:inicio',
            // Precios, idioma
            'precioAdulto' => 'nullable|numeric|min:0',
            'precioChild' => 'nullable|numeric|min:0',
            'lang' => 'nullable|string|max:10',

            'presentacion' => 'nullable|string',

            'anio_id' => 'nullable|exists:anios,id',
            'mes_id' => 'nullable|exists:meses,id',

            'agentes' => 'array',
            'agentes.*' => 'exists:agentes,id',

            'proveedors' => 'nullable|array',
            'proveedors.*' => 'exists:proveedors,id',

            'habitaciones' => 'nullable|array',
            'habitaciones.*' => 'exists:habitacions,id',

            'fechas' => 'nullable|array',
            // Cambiar 'date' a 'nullable|date' para que acepte valores vacíos
            'fechas.*.ingreso' => 'nullable|date',  // ← Cambiado de 'required_with' a 'nullable'
            'fechas.*.salida' => 'nullable|date|after:fechas.*.ingreso',  // ← Cambiado

            'paxs' => 'array',
            'paxs.*.nombre' => 'required|string|max:255',
            'paxs.*.edad' => 'required|integer|min:0',
            'paxs.*.nacionalidad' => 'required|string|max:255',
            'paxs.*.alimentacion' => 'nullable|string|max:255',
            'paxs.*.pasaporte' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Crear el programa con todos los campos
            $programa = Programa::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'email' => $request->email,
                'inicio' => $request->inicio,
                'fin' => $request->fin,
                'lang' => $request->lang,
                'precioAdulto' => $request->precioAdulto,
                'precioChild' => $request->precioChild,
                'presentacion' => $request->presentacion,
                'anio_id' => $request->anio_id,
                'mes_id' => $request->mes_id,
                'agente_id' => $request->agente_id,
            ]);

            // Sincronizar relaciones muchos a muchos
            $programa->agentes()->sync($request->input('agentes', []));
            $programa->proveedores()->sync($request->input('proveedores', [])); // CORREGIDO

            // Guardar fechas por habitación
            if ($request->has('habitaciones') && $request->has('fechas')) {
                $programa->habitaciones()->sync($request->input('habitaciones', []));
                foreach ($request->habitaciones as $habitacionId) {
                    // Verificar si existe la fecha para esta habitación
                    if (
                        isset($request->fechas[$habitacionId]) &&
                        !empty($request->fechas[$habitacionId]['ingreso']) &&
                        !empty($request->fechas[$habitacionId]['salida'])
                    ) {

                        HabitacionFecha::create([
                            'programa_id' => $programa->id,
                            'habitacion_id' => $habitacionId,
                            'fecha_ingreso' => $request->fechas[$habitacionId]['ingreso'],
                            'fecha_salida' => $request->fechas[$habitacionId]['salida'],
                        ]);
                    }
                }
            }

            // Procesar Paxs
            if ($request->has('paxs') && is_array($request->paxs)) {
                foreach ($request->paxs as $index => $paxData) {
                    $toCreate = [
                        'nombre' => $paxData['nombre'] ?? null,
                        'edad' => $paxData['edad'] ?? null,
                        'nacionalidad' => $paxData['nacionalidad'] ?? null,
                        'alimentacion' => $paxData['alimentacion'] ?? null,
                    ];

                    if ($request->hasFile("paxs.$index.pasaporte")) {
                        $file = $request->file("paxs.$index.pasaporte");
                        $nombreOriginal = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $nombreArchivo = $nombreOriginal . '_' . time() . '_' . uniqid() . '.' . $extension;
                        $file->move(public_path('img/programas/paxs'), $nombreArchivo);
                        $toCreate['pasaporte'] = 'img/programas/paxs/' . $nombreArchivo;
                    }

                    $programa->paxs()->create($toCreate);
                }
            }

            return redirect()
                ->route('programas.index')
                ->with('success', 'Programa creado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el programa: ' . $e->getMessage());
        }
    }

    public function edit(Programa $programa)
    {
        // Cargar todas las relaciones necesarias, incluyendo habitacionesFechas
        $programa->load(['agentes', 'proveedores', 'habitaciones', 'paxs', 'habitacionesFechas']);

        $agentes = Agente::all();
        $agentesAsignados = $programa->agentes->pluck('id')->toArray();
        $paxs = $programa->paxs;

        $proveedoresPorCategoria = Proveedor::with('categoria')
            ->get()
            ->groupBy(function ($proveedor) {
                return $proveedor->categoria->nombre ?? 'Sin Categoría';
            });

        $proveedoresSeleccionados = $programa->proveedores->pluck('id')->toArray();

        $hoteles = Hotel::with(['habitaciones' => function ($query) {
            $query->orderBy('tipo');
        }])->get();

        $habitacionesSeleccionadas = $programa->habitaciones->pluck('id')->toArray();

        // Crear array de fechas por habitación para fácil acceso en la vista
        $fechasPorHabitacion = $programa->habitacionesFechas->keyBy('habitacion_id');

        $anios = Anio::query()->orderByDesc('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();

        return view('programas.edit', compact(
            'programa',
            'agentes',
            'agentesAsignados',
            'paxs',
            'proveedoresPorCategoria',
            'proveedoresSeleccionados',
            'hoteles',
            'habitacionesSeleccionadas',
            'fechasPorHabitacion',
            'anios',
            'meses',
        ));
    }
    public function update(Request $request, Programa $programa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:programas,codigo,' . $programa->id,
            'email' => 'nullable|email|max:255',
            'inicio' => 'nullable|date',
            'fin' => 'nullable|date|after_or_equal:inicio',
            'precioAdulto' => 'nullable|numeric|min:0',
            'precioChild' => 'nullable|numeric|min:0',
            'lang' => 'nullable|string|max:10',
            'presentacion' => 'nullable|string',
            'anio_id' => 'nullable|exists:anios,id',
            'mes_id' => 'nullable|exists:meses,id',
            'agentes' => 'array',
            'agentes.*' => 'exists:agentes,id',
            'proveedores' => 'nullable|array',
            'proveedores.*' => 'exists:proveedors,id',
            'habitaciones' => 'nullable|array',
            'habitaciones.*' => 'exists:habitacions,id',
            'paxs' => 'array',
            'paxs.*.nombre' => 'required|string|max:255',
            'paxs.*.edad' => 'required|integer|min:0',
            'paxs.*.nacionalidad' => 'required|string|max:255',
            'paxs.*.alimentacion' => 'nullable|string|max:255',
            'paxs.*.pasaporte' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar el programa
            $programa->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'email' => $request->email,
                'inicio' => $request->inicio,
                'fin' => $request->fin,
                'lang' => $request->lang,
                'precioAdulto' => $request->precioAdulto,
                'precioChild' => $request->precioChild,
                'presentacion' => $request->presentacion,
                'anio_id' => $request->anio_id,
                'mes_id' => $request->mes_id,
                'agente_id' => $request->agente_id,
            ]);

            // Sincronizar relaciones muchos a muchos
            $programa->agentes()->sync($request->input('agentes', []));
            $programa->proveedores()->sync($request->input('proveedores', []));
            $programa->habitaciones()->sync($request->input('habitaciones', []));

            // *** ACTUALIZAR FECHAS DE HABITACIONES ***
            // Eliminar todas las fechas existentes primero (más simple y seguro)
            $programa->habitacionesFechas()->delete();

            // Crear nuevas fechas solo para las habitaciones seleccionadas que tengan fechas
            if ($request->has('habitaciones') && $request->has('fechas')) {
                foreach ($request->habitaciones as $habitacionId) {
                    if (
                        isset($request->fechas[$habitacionId]) &&
                        !empty($request->fechas[$habitacionId]['ingreso']) &&
                        !empty($request->fechas[$habitacionId]['salida'])
                    ) {

                        HabitacionFecha::create([
                            'programa_id' => $programa->id,
                            'habitacion_id' => $habitacionId,
                            'fecha_ingreso' => $request->fechas[$habitacionId]['ingreso'],
                            'fecha_salida' => $request->fechas[$habitacionId]['salida'],
                        ]);
                    }
                }
            }

            // Procesar Paxs
            // Procesar Paxs
            if ($request->has('paxs') && is_array($request->paxs)) {
                $paxsIdsRecibidos = [];

                foreach ($request->paxs as $index => $paxData) {
                    // Preparar datos básicos
                    $toUpdate = [
                        'nombre' => $paxData['nombre'] ?? null,
                        'edad' => $paxData['edad'] ?? null,
                        'nacionalidad' => $paxData['nacionalidad'] ?? null,
                        'alimentacion' => $paxData['alimentacion'] ?? null,
                    ];

                    // Verificar si se subió un nuevo archivo
                    if ($request->hasFile("paxs.$index.pasaporte")) {
                        $file = $request->file("paxs.$index.pasaporte");

                        // Validar archivo
                        if ($file->isValid()) {
                            $nombreOriginal = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $nombreOriginal = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreOriginal);
                            $extension = $file->getClientOriginalExtension();
                            $nombreArchivo = $nombreOriginal . '_' . time() . '_' . uniqid() . '.' . $extension;

                            // Crear directorio si no existe
                            $destinationPath = public_path('img/programas/paxs');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0777, true);
                            }

                            $file->move($destinationPath, $nombreArchivo);
                            $toUpdate['pasaporte'] = 'img/programas/paxs/' . $nombreArchivo;

                            // Si es un pax existente y tiene un archivo viejo, eliminarlo
                            if (isset($paxData['id']) && !empty($paxData['id'])) {
                                $paxExistente = $programa->paxs()->find($paxData['id']);
                                if ($paxExistente && $paxExistente->pasaporte && file_exists(public_path($paxExistente->pasaporte))) {
                                    unlink(public_path($paxExistente->pasaporte));
                                }
                            }
                        }
                    }

                    // Si es un pax existente, actualizar
                    if (isset($paxData['id']) && !empty($paxData['id'])) {
                        $pax = $programa->paxs()->find($paxData['id']);
                        if ($pax) {
                            $pax->update($toUpdate);
                            $paxsIdsRecibidos[] = $pax->id;
                        }
                    }
                    // Si es un nuevo pax, crear
                    else {
                        // Asegurar que pasaporte tenga un valor (puede ser null)
                        if (!isset($toUpdate['pasaporte'])) {
                            $toUpdate['pasaporte'] = null;
                        }
                        $nuevoPax = $programa->paxs()->create($toUpdate);
                        $paxsIdsRecibidos[] = $nuevoPax->id;
                    }
                }

                // Eliminar paxs que no están en el array
                if (!empty($paxsIdsRecibidos)) {
                    $programa->paxs()->whereNotIn('id', $paxsIdsRecibidos)->delete();
                } else {
                    $programa->paxs()->delete();
                }
            } else {
                // Si no hay paxs, eliminar todos
                $programa->paxs()->delete();
            }

            DB::commit();

            return redirect()
                ->route('programas.index')
                ->with('success', 'Programa actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el programa: ' . $e->getMessage());
        }
    }

    public function show(Programa $programa)
    {
        $programa->load([
            'agentes',
            'proveedores.categoria',
            'habitaciones.hotel',
            'paxs',
            'habitacionesFechas',
        ]);

        if ($programa->lang === 'en') {
            return view('programas.show-en', compact('programa'));
        }

        return view('programas.show', compact('programa'));
    }

    public function exportPdf(Programa $programa)
    {
        ['binary' => $binary, 'filename' => $filename] = $this->buildProgramaPdf($programa);

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Envía al correo del programa un saludo de bienvenida y el PDF adjunto.
     */
    public function enviarCorreo(Programa $programa)
    {
        $email = trim((string) ($programa->email ?? ''));

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()
                ->back()
                ->with('error', 'Este programa no tiene un correo electrónico válido. Edítalo y guarda un email correcto.');
        }

        $from = config('mail.from.address');
        if (empty($from) || ! filter_var(trim((string) $from), FILTER_VALIDATE_EMAIL)) {
            return redirect()
                ->back()
                ->with('error', 'Configure MAIL_FROM_ADDRESS en .env con un correo válido (el remitente debe ser una cuenta autorizada en su SMTP).');
        }

        if (config('mail.default') === 'smtp' && empty(config('mail.mailers.smtp.username'))) {
            return redirect()
                ->back()
                ->with('error', 'MAIL_USERNAME está vacío en .env. La mayoría de servidores SMTP (Gmail, Outlook, etc.) requieren usuario y contraseña.');
        }

        try {
            ['binary' => $binary, 'filename' => $filename] = $this->buildProgramaPdf($programa);

            Mail::to($email)->send(new ProgramaBienvenidaMailable($programa, $binary, $filename));

            return redirect()
                ->back()
                ->with('success', 'Correo enviado correctamente a ' . $email . ' con el programa en PDF adjunto.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', $this->mensajeErrorEnvioCorreo($e));
        }
    }

    /**
     * Texto útil para el usuario (y detalle técnico solo en depuración).
     */
    protected function mensajeErrorEnvioCorreo(\Throwable $e): string
    {
        $base = 'No se pudo enviar el correo. ';
        $hints = 'Revise MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD y MAIL_ENCRYPTION en .env. '
            . 'Gmail: active verificación en 2 pasos y cree una contraseña de aplicación; use puerto 587 con tls. '
            . 'Desde la terminal: php artisan mail:test su@correo.com';

        if (config('app.debug')) {
            return $base . 'Detalle: ' . $e->getMessage() . ' — ' . $hints;
        }

        $msg = strtolower($e->getMessage());

        if (str_contains($msg, 'authenticate') || str_contains($msg, '535') || str_contains($msg, 'authentication')) {
            $base .= 'El servidor SMTP rechazó usuario o contraseña. ';
        } elseif (str_contains($msg, 'connection') || str_contains($msg, 'timed out') || str_contains($msg, 'could not connect')) {
            $base .= 'No hubo conexión con el servidor de correo (host, puerto, firewall o antivirus). ';
        } elseif (str_contains($msg, 'certificate') || str_contains($msg, 'ssl') || str_contains($msg, 'tls')) {
            $base .= 'Problema de certificado SSL/TLS. Pruebe MAIL_ENCRYPTION=tls con puerto 587 o ssl con 465. ';
        }

        return $base . $hints . ' Más detalle en storage/logs/laravel.log.';
    }

    /**
     * @return array{binary: string, filename: string}
     */
    protected function buildProgramaPdf(Programa $programa): array
    {
        $programa->load([
            'agentes',
            'proveedores.categoria',
            'habitaciones.hotel',
            'paxs',
            'habitacionesFechas',
        ]);

        $presentacionPdf = $programa->presentacion;
        if ($presentacionPdf && ! extension_loaded('gd')) {
            $presentacionPdf = preg_replace('/<img\b[^>]*>/i', '', $presentacionPdf);
        }

        $pdfView = $programa->lang === 'es' ? 'programas.pdf' : 'programas.pdf-en';
        $html = View::make($pdfView, compact('programa', 'presentacionPdf'))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $slug = preg_replace('/[^A-Za-z0-9_-]+/', '-', $programa->codigo ?: (string) $programa->id);
        $suffix = $programa->lang === 'es' ? '' : '-en';
        $filename = 'programa-' . $slug . $suffix . '.pdf';

        return [
            'binary' => $dompdf->output(),
            'filename' => $filename,
        ];
    }

    public function destroy(Programa $programa)
    {
        $programa->delete();

        return redirect()->route('programas.index')
            ->with('success', 'Programa eliminado correctamente.');
    }
}
