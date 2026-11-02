<?php

namespace App\Http\Controllers;

use App\Mail\ProgramaBienvenidaMailable;
use App\Models\Agente;
use App\Models\Anio;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Mes;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Support\AlojamientoEnServicios;
use App\Support\Paises;
use App\Support\PassportFiles;
use App\Support\Portadas;
use App\Models\Movimiento;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class ProgramaController extends Controller
{
    /** Pestañas del listado: clave => etiqueta. */
    public const ESTADOS = [
        'activos' => 'Próximos y en curso',
        'finalizados' => 'Finalizados',
        'sin_fechas' => 'Sin fechas',
        'todos' => 'Todos',
    ];

    public function index(Request $request)
    {
        $estado = array_key_exists((string) $request->input('estado'), self::ESTADOS) ? $request->input('estado') : 'activos';
        $today = now()->toDateString();

        // Filtros comunes (búsqueda, año, mes) sin el de estado, para poder contar cada pestaña.
        $base = Programa::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->input('q')) . '%';
                $q->where(fn ($w) => $w->where('nombre', 'like', $term)
                    ->orWhere('codigo', 'like', $term)
                    ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', $term)->orWhere('empresa', 'like', $term)->orWhere('email', 'like', $term))
                    ->orWhereHas('paxs', fn ($p) => $p->where('nombre', 'like', $term)));
            })
            ->when($request->filled('anio_id'), fn ($q) => $q->where('anio_id', $request->integer('anio_id')))
            ->when($request->filled('mes_id'), fn ($q) => $q->where('mes_id', $request->integer('mes_id')))
            ->when(array_key_exists((string) $request->input('comercial'), Programa::ESTADOS), fn ($q) => $q->where('estado', $request->input('comercial')));

        $scopes = [
            'activos' => fn ($q) => $q->whereDate('fin', '>=', $today),
            'finalizados' => fn ($q) => $q->whereDate('fin', '<', $today),
            'sin_fechas' => fn ($q) => $q->where(fn ($w) => $w->whereNull('inicio')->orWhereNull('fin')),
            'todos' => fn ($q) => $q,
        ];

        $counts = collect($scopes)->map(fn ($scope) => $scope(clone $base)->count());

        $query = $scopes[$estado](clone $base)
            ->with(['cliente', 'agentes:id,nombre', 'paxs', 'pagos', 'servicios.proveedor', 'tareas', 'habitacionesFechas.habitacion.hotel', 'anio', 'mes']);

        // Activos: el más cercano primero; el resto, el más reciente primero.
        $estado === 'activos'
            ? $query->orderBy('inicio')
            : $query->orderByDesc('inicio')->orderByDesc('id');

        $programas = $query->paginate(15)->withQueryString();
        $anios = Anio::query()->orderByDesc('anio')->get();
        $meses = Mes::query()->orderBy('numero')->get();
        $estados = self::ESTADOS;

        $comerciales = Programa::ESTADOS;

        return view('programas.index', compact('programas', 'anios', 'meses', 'estado', 'estados', 'counts', 'comerciales'));
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

        if ($anioId !== null && ! $anios->contains(fn ($a) => (int) $a->id === (int) $anioId)) {
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

        if ($mesId !== null && ! $meses->contains(fn ($m) => (int) $m->id === (int) $mesId)) {
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
        $programa = new Programa(['estado' => 'cotizacion', 'lang' => 'es']);
        $programa->cliente_id = request()->integer('cliente_id') ?: null;

        // Viene de «Crear cotización» en una consulta: ya trae lo que el cliente pidió.
        if ($consulta = Consulta::find(request()->integer('consulta'))) {
            $programa->fill(array_filter(['nombre' => $consulta->tour, 'lang' => $consulta->idioma, 'inicio' => $consulta->fecha_inicio?->toDateString(), 'fin' => $consulta->fecha_fin?->toDateString()]));
            $programa->presentacion = $programa->presentacion ?: ($consulta->mensaje ? e($consulta->mensaje) : null);
        }

        return view('programas.create', $this->formData($programa));
    }

    /** Datos que necesita el formulario (programas/form) para crear o editar. */
    private function formData(Programa $programa): array
    {
        $programa->loadMissing(['cliente', 'agentes', 'paxs', 'habitacionesFechas.habitacion.hotel', 'pagos', 'dias', 'servicios.proveedor', 'servicios.pagos', 'tareas.completadaPor', 'notificaciones.enviadaPor']);

        return [
            'programa' => $programa,
            'agentes' => Agente::orderBy('nombre')->get(),
            'proveedoresPorCategoria' => Proveedor::with('categoria')->orderBy('nombre')->get(['id', 'nombre', 'categoria_id', 'telefono'])
                ->groupBy(fn ($proveedor) => $proveedor->categoria->nombre ?? 'Sin categoría')->sortKeys(),
            'hoteles' => Hotel::with(['habitaciones' => fn ($q) => $q->orderBy('tipo')])->orderBy('nombre')->get(),
            'estados' => Programa::ESTADOS,
            'clientes' => Cliente::orderBy('nombre')->get(['id', 'nombre', 'empresa', 'email']),
            'siguienteCodigo' => Programa::siguienteCodigo(),
            'portadas' => Portadas::opciones(),
            'movimientos' => $programa->exists ? Movimiento::where('programa_id', $programa->id)->latest()->orderByDesc('id')->limit(100)->get() : collect(),
        ];
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->mensajes());

        try {
            DB::beginTransaction();

            $programa = Programa::create($this->programaData($request));

            $programa->agentes()->sync($request->input('agentes', []));
            $this->guardarEstadias($programa, $request);
            Consulta::find($request->integer('consulta_id'))?->seguirA($programa);
            AlojamientoEnServicios::sincronizar($programa);

            foreach ((array) $request->input('paxs', []) as $index => $paxData) {
                $toCreate = $this->paxData($paxData);

                if ($request->hasFile("paxs.$index.pasaporte_archivo")) {
                    $toCreate['pasaporte_archivo'] = PassportFiles::store($request->file("paxs.$index.pasaporte_archivo"));
                }

                $programa->paxs()->create($toCreate);
            }

            DB::commit();

            return redirect()
                ->route('programas.edit', $programa)
                ->with('success', 'Programa creado. Puedes seguir completándolo o registrar pagos.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo crear el programa. Revisa los datos e inténtalo de nuevo.');
        }
    }

    public function edit(Programa $programa)
    {
        return view('programas.edit', $this->formData($programa));
    }

    public function update(Request $request, Programa $programa)
    {
        $request->validate($this->rules($programa), $this->mensajes());

        try {
            DB::beginTransaction();

            // Actualizar el programa
            $programa->update($this->programaData($request));

            // Sincronizar relaciones muchos a muchos
            $programa->agentes()->sync($request->input('agentes', []));

            // Alojamiento (solo si el formulario incluyó la pestaña: evita borrarlo desde otros envíos).
            if ($request->boolean('_estadias')) {
                $this->guardarEstadias($programa, $request);
            }

            // Pasajeros: actualizar existentes, crear nuevos y eliminar los quitados del formulario.
            $paxsIdsRecibidos = [];

            foreach ((array) $request->input('paxs', []) as $index => $paxData) {
                $toUpdate = $this->paxData($paxData);
                $pax = ! empty($paxData['id']) ? $programa->paxs()->find($paxData['id']) : null;

                if ($request->hasFile("paxs.$index.pasaporte_archivo") && $request->file("paxs.$index.pasaporte_archivo")->isValid()) {
                    $toUpdate['pasaporte_archivo'] = PassportFiles::store($request->file("paxs.$index.pasaporte_archivo"));
                    PassportFiles::delete($pax?->pasaporte_archivo);
                }

                if ($pax) {
                    $pax->update($toUpdate);
                } else {
                    $pax = $programa->paxs()->create($toUpdate);
                }
                $paxsIdsRecibidos[] = $pax->id;
            }

            $programa->paxs()->whereNotIn('id', $paxsIdsRecibidos)->get()->each(function (Pax $pax) {
                PassportFiles::delete($pax->pasaporte_archivo);
                $pax->delete();
            });

            // Itinerario (solo si el formulario incluyó la pestaña: evita borrar servicios desde otros envíos).
            $conPagos = 0;
            if ($request->boolean('_itinerario')) {
                $conPagos = $this->guardarItinerario($programa, $request);
            }

            // Después del itinerario: lo que vuelve del formulario no debe pisar lo que dicta la estadía.
            if ($request->boolean('_estadias')) {
                AlojamientoEnServicios::sincronizar($programa);
            }

            DB::commit();

            return redirect()
                ->route('programas.edit', [$programa, 'tab' => $request->input('_tab')])
                ->with('success', 'Cambios guardados.')
                ->with('warning', $this->avisosAlGuardar($programa->fresh()))
                ->with('error', $conPagos ? "{$conPagos} servicio(s) con pagos a proveedor no se quitaron: primero Contabilidad debe anular esos pagos." : null);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el programa. Revisa los datos e inténtalo de nuevo.');
        }
    }

    /** Lo que se puede guardar, pero conviene revisar: el cliente deja de ver los servicios fuera de fechas. */
    private function avisosAlGuardar(Programa $programa): ?string
    {
        $avisos = [];

        $sinCosto = $programa->habitacionesFechas()->whereNotNull('fecha_ingreso')->whereNull('costo_noche')->whereNotNull('servicio_id')->count();
        if ($sinCosto) {
            $avisos[] = $sinCosto === 1
                ? 'Falta el costo por noche de 1 estadía: el margen saldrá más alto de lo real.'
                : "Falta el costo por noche de {$sinCosto} estadías: el margen saldrá más alto de lo real.";
        }

        if ($programa->inicio && $programa->fin) {
            $fuera = $programa->servicios()->where('estado', '!=', 'cancelado')
                ->where(fn ($q) => $q->whereDate('fecha', '<', $programa->inicio)->orWhereDate('fecha', '>', $programa->fin))->count();
            if ($fuera) {
                $avisos[] = $fuera === 1
                    ? 'Hay 1 servicio fuera de las fechas del viaje: el cliente no lo verá.'
                    : "Hay {$fuera} servicios fuera de las fechas del viaje: el cliente no los verá.";
            }
        }

        if ($programa->estado === 'confirmado') {
            if ($programa->paxs()->doesntExist()) {
                $avisos[] = 'El programa está confirmado y aún no tiene pasajeros.';
            } elseif ($programa->presupuesto()['total'] <= 0) {
                $avisos[] = 'El programa está confirmado y aún no tiene precio.';
            }
        }

        return $avisos ? implode(' ', $avisos) : null;
    }

    /** Reglas de validación del formulario de programas (alta y edición). */
    private function rules(?Programa $programa = null): array
    {
        return [
            'nombre' => 'required|string|max:255',
            // Vacío al crear = se genera solo (AET-2026-0001).
            'codigo' => ($programa ? 'required' : 'nullable') . '|string|max:50|unique:programas,codigo' . ($programa ? ',' . $programa->id : ''),
            'estado' => 'required|in:' . implode(',', array_keys(Programa::ESTADOS)),
            'cliente_modo' => 'required|in:existente,nuevo',
            'cliente_id' => ['nullable', Rule::requiredIf(request('cliente_modo') === 'existente' && ! $programa?->plantilla), 'exists:clientes,id'],
            // Un programa confirmado se opera: sin fechas no aparecería en la hoja del día ni tendría itinerario.
            'inicio' => 'nullable|required_if:estado,confirmado|required_with:fin|date',
            'fin' => 'nullable|required_if:estado,confirmado|required_with:inicio|date|after_or_equal:inicio',
            'lang' => 'nullable|in:es,en',
            'presentacion' => 'nullable|string',
            'portada' => ['nullable', Rule::in(Portadas::rutas())],
            'precioAdulto' => 'nullable|numeric|min:0',
            'precioChild' => 'nullable|numeric|min:0',
            'porcentaje_ganancia' => 'nullable|numeric|min:0|max:999.99',
            'tipo_cambio' => 'nullable|numeric|min:0.5|max:99',
            'suplemento_individual' => 'nullable|numeric|min:0',
            'habitaciones_individuales' => 'nullable|integer|min:0|max:50',
            'agentes' => 'array',
            'agentes.*' => 'exists:agentes,id',
            'dias' => 'nullable|array',
            'dias.*.titulo' => 'nullable|string|max:255',
            'dias.*.descripcion' => 'nullable|string|max:3000',
            'servicios' => 'nullable|array',
            'servicios.*.fecha' => 'required|date',
            'servicios.*.hora' => 'nullable|date_format:H:i',
            'servicios.*.tipo' => ['required', Rule::in(array_keys(Servicio::TIPOS))],
            'servicios.*.titulo' => 'required|string|max:255',
            // Solicitar o confirmar exige proveedor; sin él, el servicio queda "Sin asignar".
            'servicios.*.proveedor_id' => 'nullable|required_if:servicios.*.estado,solicitado,confirmado|exists:proveedors,id',
            'servicios.*.estado' => ['required', Rule::in(array_keys(Servicio::ESTADOS))],
            'servicios.*.confirmacion' => 'nullable|string|max:80',
            'servicios.*.punto_encuentro' => 'nullable|string|max:255',
            'servicios.*.descripcion' => 'nullable|string|max:2000',
            'servicios.*.notas' => 'nullable|string|max:2000',
            'servicios.*.costo' => 'nullable|numeric|min:0',
            'servicios.*.costo_estimado' => 'nullable|numeric|min:0',
            'servicios.*.moneda' => ['nullable', Rule::in(array_keys(Servicio::MONEDAS))],
            'servicios.*.costo_por' => ['nullable', Rule::in(array_keys(Servicio::COSTO_POR))],
            'estadias' => 'nullable|array',
            'estadias.*.id' => 'nullable|integer',
            'estadias.*.habitacion_id' => 'required|exists:habitacions,id',
            'estadias.*.cantidad' => 'required|integer|min:1|max:50',
            'estadias.*.costo_noche' => 'nullable|numeric|min:0',
            'estadias.*.moneda' => ['nullable', Rule::in(array_keys(Servicio::MONEDAS))],
            'estadias.*.ingreso' => 'nullable|required_with:estadias.*.salida|date',
            'estadias.*.salida' => 'nullable|required_with:estadias.*.ingreso|date|after:estadias.*.ingreso',
            'paxs' => 'array',
        ] + self::reglasPax('paxs.*.') + (request('cliente_modo') === 'nuevo' ? ClienteController::reglas('cliente_nuevo.') : []);
    }

    /** Reglas de un pasajero (también las usa PaxController con prefijo ''). */
    public static function reglasPax(string $prefix): array
    {
        return [
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'fecha_nacimiento' => 'required|date|before_or_equal:today|after:1900-01-01',
            $prefix . 'sexo' => ['nullable', Rule::in(array_keys(Pax::SEXOS))],
            $prefix . 'pais' => ['required', Rule::in(array_keys(Paises::all()))],
            $prefix . 'pasaporte' => 'nullable|string|max:50',
            $prefix . 'pasaporte_vencimiento' => 'nullable|date',
            $prefix . 'pasaporte_archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:4096',
            $prefix . 'dieta' => ['nullable', Rule::in(array_keys(Pax::DIETAS))],
            $prefix . 'alergias' => 'nullable|string|max:255',
            $prefix . 'condiciones_medicas' => 'nullable|string|max:1000',
            $prefix . 'emergencia_nombre' => 'nullable|string|max:255',
            $prefix . 'emergencia_telefono' => 'nullable|string|max:40',
        ];
    }

    /** Mensajes legibles para los campos anidados del formulario. */
    private function mensajes(): array
    {
        return [
            'cliente_id.required_if' => 'Elige un cliente o crea uno nuevo.',
            'inicio.required_if' => 'Un programa confirmado necesita fecha de inicio.',
            'fin.required_if' => 'Un programa confirmado necesita fecha de fin.',
            'inicio.required_with' => 'Indica también la fecha de inicio.',
            'fin.required_with' => 'Indica también la fecha de fin.',
            'fin.after_or_equal' => 'El fin no puede ser anterior al inicio.',
            'estadias.*.habitacion_id.required' => 'Elige el hotel y la habitación.',
            'estadias.*.cantidad.required' => 'Indica cuántas habitaciones.',
            'estadias.*.cantidad.min' => 'Debe ser al menos 1.',
            'estadias.*.cantidad.max' => 'Máximo 50 habitaciones por fila.',
            'estadias.*.ingreso.required_with' => 'Indica la fecha de ingreso.',
            'estadias.*.salida.required_with' => 'Indica la fecha de salida.',
            'estadias.*.salida.after' => 'La salida debe ser posterior al ingreso.',
            'precioAdulto.min' => 'El precio no puede ser negativo.',
            'precioChild.min' => 'El precio no puede ser negativo.',
            'cliente_nuevo.nombre.required' => 'Indica el nombre del cliente.',
            'cliente_nuevo.empresa.required_if' => 'Indica el nombre de la agencia.',
            'paxs.*.nombre.required' => 'Falta el nombre.',
            'paxs.*.fecha_nacimiento.required' => 'Falta la fecha de nacimiento.',
            'paxs.*.fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'paxs.*.pais.required' => 'Elige el país.',
            'servicios.*.titulo.required' => 'Describe el servicio.',
            'servicios.*.fecha.required' => 'Indica la fecha.',
            'servicios.*.hora.date_format' => 'Hora no válida (HH:MM).',
            'servicios.*.proveedor_id.required_if' => 'Asigna un proveedor antes de marcarlo como solicitado o confirmado.',
        ];
    }

    /** Campos del programa a guardar (año y mes los deriva el modelo desde la fecha de inicio). */
    private function programaData(Request $request): array
    {
        return [
            'nombre' => $request->nombre,
            'codigo' => $request->filled('codigo') ? $request->codigo : null,
            'estado' => $request->input('estado', 'cotizacion'),
            'cliente_id' => $this->resolverCliente($request),
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'lang' => $request->lang,
            // Precios: columnas NOT NULL, vacío = sin tarifa (0).
            'precioAdulto' => $request->filled('precioAdulto') ? $request->input('precioAdulto') : 0,
            'precioChild' => $request->filled('precioChild') ? $request->input('precioChild') : 0,
            'porcentaje_ganancia' => $request->filled('porcentaje_ganancia') ? $request->input('porcentaje_ganancia') : null,
            'tipo_cambio' => $request->filled('tipo_cambio') ? $request->input('tipo_cambio') : null,
            'suplemento_individual' => $request->filled('suplemento_individual') ? $request->input('suplemento_individual') : 0,
            'habitaciones_individuales' => $request->integer('habitaciones_individuales'),
            'presentacion' => $request->presentacion,
            'portada' => $request->input('portada') ?: null,
        ];
    }

    /** Cliente elegido o, si se pidió, creado al vuelo con los datos de "cliente_nuevo". */
    private function resolverCliente(Request $request): ?int
    {
        if ($request->input('cliente_modo') === 'nuevo') {
            return Cliente::create($request->input('cliente_nuevo', []))->id;
        }

        return $request->integer('cliente_id') ?: null;
    }

    /** Datos de un pasajero del formulario (el archivo de pasaporte se trata aparte). */
    private function paxData(array $paxData): array
    {
        return [
            'nombre' => $paxData['nombre'] ?? null,
            'fecha_nacimiento' => $paxData['fecha_nacimiento'] ?? null,
            'sexo' => ($paxData['sexo'] ?? null) ?: null,
            'pais' => ($paxData['pais'] ?? null) ?: null,
            'pasaporte' => ($paxData['pasaporte'] ?? null) ?: null,
            'pasaporte_vencimiento' => ($paxData['pasaporte_vencimiento'] ?? null) ?: null,
            'dieta' => ($paxData['dieta'] ?? null) ?: null,
            'alergias' => ($paxData['alergias'] ?? null) ?: null,
            'condiciones_medicas' => ($paxData['condiciones_medicas'] ?? null) ?: null,
            'emergencia_nombre' => ($paxData['emergencia_nombre'] ?? null) ?: null,
            'emergencia_telefono' => ($paxData['emergencia_telefono'] ?? null) ?: null,
        ];
    }

    /**
     * Días (título y texto) y servicios del itinerario: actualiza los existentes, crea los nuevos y elimina los quitados.
     * Proveedor, estado, confirmación y costo real los gestiona Reservas: quien no lo tiene a cargo no los cambia desde aquí.
     *
     * @return int Servicios que no se quitaron porque tienen pagos registrados.
     */
    private function guardarItinerario(Programa $programa, Request $request): int
    {
        $gestionaReservas = $request->user()?->can('servicios.gestionar') ?? false;

        foreach ((array) $request->input('dias', []) as $fecha => $dia) {
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $fecha)) {
                continue;
            }
            $titulo = trim((string) ($dia['titulo'] ?? ''));
            $descripcion = trim((string) ($dia['descripcion'] ?? ''));

            if ($titulo === '' && $descripcion === '') {
                $programa->dias()->whereDate('fecha', $fecha)->get()->each->delete();
            } else {
                $programa->dias()->updateOrCreate(['fecha' => $fecha], ['titulo' => $titulo ?: null, 'descripcion' => $descripcion ?: null]);
            }
        }

        $idsRecibidos = [];
        foreach ((array) $request->input('servicios', []) as $datos) {
            $valores = [
                'fecha' => $datos['fecha'],
                'hora' => ($datos['hora'] ?? null) ?: null,
                'tipo' => $datos['tipo'],
                'titulo' => $datos['titulo'],
                'proveedor_id' => ($datos['proveedor_id'] ?? null) ?: null,
                'estado' => $datos['estado'],
                'confirmacion' => ($datos['confirmacion'] ?? null) ?: null,
                'punto_encuentro' => ($datos['punto_encuentro'] ?? null) ?: null,
                'descripcion' => ($datos['descripcion'] ?? null) ?: null,
                'notas' => ($datos['notas'] ?? null) ?: null,
                'visible_cliente' => ! empty($datos['visible_cliente']),
                'costo' => isset($datos['costo']) && $datos['costo'] !== '' ? $datos['costo'] : null,
                'costo_estimado' => isset($datos['costo_estimado']) && $datos['costo_estimado'] !== '' ? $datos['costo_estimado'] : null,
                'moneda' => ($datos['moneda'] ?? null) ?: 'USD',
                'costo_por' => ($datos['costo_por'] ?? null) ?: 'grupo',
            ];
            $servicio = ! empty($datos['id']) ? $programa->servicios()->find($datos['id']) : null;
            if (! $gestionaReservas) {
                if ($servicio) {
                    $reservados = ['proveedor_id', 'estado', 'confirmacion', 'costo'];
                    // Con costo real, moneda y forma de cobro definen lo que se le debe al proveedor.
                    $valores = array_diff_key($valores, array_flip($servicio->costo !== null ? [...$reservados, 'moneda', 'costo_por'] : $reservados));
                } else {
                    $valores = ['proveedor_id' => null, 'estado' => 'sin_asignar', 'confirmacion' => null, 'costo' => null] + $valores;
                }
            }
            if ($servicio) {
                $servicio->update($valores);
            } else {
                $servicio = $programa->servicios()->create($valores);
            }
            $idsRecibidos[] = $servicio->id;
        }

        $conPagos = 0;
        // Los servicios de una estadía se quitan desde la pestaña Alojamiento.
        $programa->servicios()->whereNotIn('id', $idsRecibidos)->whereDoesntHave('estadia')->withCount('pagos')->get()->each(function (Servicio $servicio) use (&$conPagos) {
            if ($servicio->pagos_count > 0) {
                $conPagos++;
            } else {
                $servicio->delete();
            }
        });

        return $conPagos;
    }

    /** Alojamiento: actualiza las filas existentes, crea las nuevas y elimina las quitadas. */
    private function guardarEstadias(Programa $programa, Request $request): void
    {
        $idsRecibidos = [];
        foreach ((array) $request->input('estadias', []) as $datos) {
            $valores = [
                'habitacion_id' => $datos['habitacion_id'],
                'cantidad' => $datos['cantidad'],
                'costo_noche' => isset($datos['costo_noche']) && $datos['costo_noche'] !== '' ? $datos['costo_noche'] : null,
                'moneda' => ($datos['moneda'] ?? null) ?: 'USD',
                'fecha_ingreso' => ($datos['ingreso'] ?? null) ?: null,
                'fecha_salida' => ($datos['salida'] ?? null) ?: null,
            ];
            $estadia = ! empty($datos['id']) ? $programa->habitacionesFechas()->find($datos['id']) : null;
            if ($estadia) {
                $estadia->update($valores);
            } else {
                $estadia = $programa->habitacionesFechas()->create($valores);
            }
            $idsRecibidos[] = $estadia->id;
        }

        // Una estadía cuyo hotel ya tiene pagos no se quita: primero Contabilidad debe anularlos.
        $programa->habitacionesFechas()->whereNotIn('id', $idsRecibidos)->get()
            ->each(fn (HabitacionFecha $estadia) => AlojamientoEnServicios::quitar($estadia) && $estadia->delete());
    }

    public function show(Programa $programa)
    {
        $programa->load([
            'agentes',
            'paxs',
            'habitacionesFechas.habitacion.hotel',
            'dias',
            'servicios',
        ]);

        return view('programas.show', compact('programa'));
    }

    /** PDF del programa: ?ver=1 lo muestra en el navegador (vista previa); sin parámetro, lo descarga. */
    public function exportPdf(Request $request, Programa $programa)
    {
        ['binary' => $binary, 'filename' => $filename] = $this->buildProgramaPdf($programa);
        $disposicion = $request->boolean('ver') ? 'inline' : 'attachment';

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposicion.'; filename="'.$filename.'"',
            'Cache-Control' => 'private, no-store',
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
                ->with('success', 'Correo enviado correctamente a '.$email.' con el programa en PDF adjunto.');
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
            .'Gmail: active verificación en 2 pasos y cree una contraseña de aplicación; use puerto 587 con tls. '
            .'Desde la terminal: php artisan mail:test su@correo.com';

        if (config('app.debug')) {
            return $base.'Detalle: '.$e->getMessage().' — '.$hints;
        }

        $msg = strtolower($e->getMessage());

        if (str_contains($msg, 'authenticate') || str_contains($msg, '535') || str_contains($msg, 'authentication')) {
            $base .= 'El servidor SMTP rechazó usuario o contraseña. ';
        } elseif (str_contains($msg, 'connection') || str_contains($msg, 'timed out') || str_contains($msg, 'could not connect')) {
            $base .= 'No hubo conexión con el servidor de correo (host, puerto, firewall o antivirus). ';
        } elseif (str_contains($msg, 'certificate') || str_contains($msg, 'ssl') || str_contains($msg, 'tls')) {
            $base .= 'Problema de certificado SSL/TLS. Pruebe MAIL_ENCRYPTION=tls con puerto 587 o ssl con 465. ';
        }

        return $base.$hints.' Más detalle en storage/logs/laravel.log.';
    }

    /**
     * @return array{binary: string, filename: string}
     */
    protected function buildProgramaPdf(Programa $programa): array
    {
        $programa->load([
            'cliente',
            'agentes',
            'paxs',
            'pagos',
            'habitacionesFechas.habitacion.hotel',
            'dias',
            'servicios',
        ]);

        $presentacionPdf = $programa->presentacion;
        if ($presentacionPdf && ! extension_loaded('gd')) {
            $presentacionPdf = preg_replace('/<img\b[^>]*>/i', '', $presentacionPdf);
        }

        // Una sola plantilla ES/EN (según $programa->lang), con el mismo contenido que el itinerario web.
        $html = View::make('programas.pdf', compact('programa', 'presentacionPdf'))->render();

        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $slug = preg_replace('/[^A-Za-z0-9_-]+/', '-', $programa->codigo ?: (string) $programa->id);
        $isEn = $programa->lang === 'en';
        // El nombre del archivo dice en qué etapa está: propuesta (cotización) o programa confirmado.
        $tipo = $programa->estado === 'cotizacion' ? ($isEn ? 'proposal' : 'propuesta') : ($isEn ? 'program' : 'programa');
        $filename = $tipo.'-'.$slug.'.pdf';

        return [
            'binary' => $dompdf->output(),
            'filename' => $filename,
        ];
    }

    public function destroy(Programa $programa)
    {
        // Las filas de paxs se borran en cascada; los archivos de pasaporte no.
        $programa->paxs()->pluck('pasaporte_archivo')->each(fn ($path) => PassportFiles::delete($path));
        $programa->delete();

        return redirect()->route('programas.index')
            ->with('success', 'Programa eliminado correctamente.');
    }

}
