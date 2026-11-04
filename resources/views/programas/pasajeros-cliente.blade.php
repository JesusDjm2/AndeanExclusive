{{--
    El cliente completa los datos de sus viajeros desde su enlace privado (DatosPasajerosController).
    Una plantilla para ES y EN según $programa->lang. Estilos: resources/sass/programa/_sections.scss (.pax-form).
--}}
@php
    $isEn = $programa->lang === 'en';
    $locale = $isEn ? 'en' : 'es';
    $t = $isEn ? [
        'title' => 'Travelers’ details',
        'intro' => 'Please complete the details of each traveler exactly as they appear in the passport. We need them to book trains, entrance tickets and hotels in Peru. Your information is sent securely and is only seen by our team.',
        'back' => 'Back to itinerary',
        'traveler' => 'Traveler',
        'new' => 'New traveler',
        'name' => 'Full name (as in passport)',
        'birth' => 'Date of birth',
        'sex' => 'Sex',
        'country' => 'Nationality',
        'passport' => 'Passport number',
        'passport_kept' => 'On file: :mask — leave blank to keep it',
        'expiry' => 'Passport expiry date',
        'expiry_hint' => 'Must be valid for at least 6 months after arrival in Peru.',
        'scan' => 'Passport scan (PDF or photo, optional)',
        'scan_ok' => 'Scan received',
        'diet' => 'Diet',
        'allergies' => 'Allergies',
        'medical' => 'Medical conditions we should know about',
        'emergency' => 'Emergency contact (name and relationship)',
        'emergency_phone' => 'Emergency contact phone',
        'add' => 'Add traveler',
        'remove' => 'Remove',
        'save' => 'Send details',
        'select' => 'Select',
        'frequent' => 'Most common',
        'all' => 'All countries',
        'errors' => 'Please review the highlighted fields.',
        'required' => 'required',
    ] : [
        'title' => 'Datos de los viajeros',
        'intro' => 'Complete los datos de cada viajero tal como figuran en el pasaporte. Los necesitamos para reservar trenes, entradas y hoteles en el Perú. La información se envía de forma segura y solo la ve nuestro equipo.',
        'back' => 'Volver al itinerario',
        'traveler' => 'Viajero',
        'new' => 'Nuevo viajero',
        'name' => 'Nombre completo (como en el pasaporte)',
        'birth' => 'Fecha de nacimiento',
        'sex' => 'Sexo',
        'country' => 'Nacionalidad',
        'passport' => 'N.º de pasaporte',
        'passport_kept' => 'Registrado: :mask — déjelo en blanco para conservarlo',
        'expiry' => 'Vencimiento del pasaporte',
        'expiry_hint' => 'Debe tener al menos 6 meses de vigencia al ingresar al Perú.',
        'scan' => 'Pasaporte escaneado (PDF o foto, opcional)',
        'scan_ok' => 'Archivo recibido',
        'diet' => 'Alimentación',
        'allergies' => 'Alergias',
        'medical' => 'Condiciones médicas que debamos conocer',
        'emergency' => 'Contacto de emergencia (nombre y parentesco)',
        'emergency_phone' => 'Teléfono del contacto de emergencia',
        'add' => 'Agregar viajero',
        'remove' => 'Quitar',
        'save' => 'Enviar datos',
        'select' => 'Seleccionar',
        'frequent' => 'Más frecuentes',
        'all' => 'Todos los países',
        'errors' => 'Revise los campos marcados.',
        'required' => 'obligatorio',
    ];
    $sexos = $isEn ? ['F' => 'Female', 'M' => 'Male', 'X' => 'Other / prefer not to say'] : \App\Models\Pax::SEXOS;
    $dietas = $isEn ? \App\Models\Pax::DIETAS_EN : \App\Models\Pax::DIETAS;
    $paises = \App\Support\Paises::enIdioma($locale);
    $frecuentes = collect(\App\Support\Paises::FRECUENTES)->mapWithKeys(fn ($c) => [$c => $paises[$c] ?? $c])->sort();

    $filas = old('paxs') ?? $programa->paxs->map(fn ($p) => [
        'id' => $p->id, 'nombre' => $p->nombre, 'fecha_nacimiento' => $p->fecha_nacimiento?->toDateString(), 'sexo' => $p->sexo,
        'pais' => $p->pais, 'pasaporte' => '', 'pasaporte_vencimiento' => $p->pasaporte_vencimiento?->toDateString(),
        'dieta' => $p->dieta, 'alergias' => $p->alergias, 'condiciones_medicas' => $p->condiciones_medicas,
        'emergencia_nombre' => $p->emergencia_nombre, 'emergencia_telefono' => $p->emergencia_telefono,
    ])->all();
    if (! $filas) {
        $filas = [[]];
    }
    $paxPorId = $programa->paxs->keyBy('id');
    $mascara = fn (?string $n) => $n ? str_repeat('•', max(0, mb_strlen($n) - 3)) . mb_substr($n, -3) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $t['title'] }} · {{ $programa->nombre }} | {{ config('seo.brand') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    @vite(['resources/sass/programa.scss', 'resources/js/programa.js'])
</head>
<body class="pax-form-page">
    <main class="container pax-form" data-pax-form>
        <header class="pax-form__header">
            @include('layouts.partials.programa-show-hero-logo', ['locale' => $locale])
            <p class="pax-form__eyebrow">{{ $programa->nombre }} · {{ $programa->codigo }}</p>
            <h1 class="pax-form__title">{{ $t['title'] }}</h1>
            <p class="pax-form__intro">{{ $t['intro'] }}</p>
            <a href="{{ route('itinerario', $programa) }}" class="pax-form__back"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ $t['back'] }}</a>
        </header>

        @if (session('success'))
            <div class="pax-form__alert pax-form__alert--ok" role="status"><i class="fa fa-check-circle" aria-hidden="true"></i> {{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="pax-form__alert pax-form__alert--error" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t['errors'] }}</div>
        @endif

        <form method="POST" action="{{ route('itinerario.pasajeros.update', $programa) }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <div data-pax-list data-next-index="{{ max(array_keys($filas)) + 1 }}">
                @foreach ($filas as $i => $fila)
                    @include('programas.partials.pax-cliente-card', ['i' => $i, 'fila' => $fila, 'paxModel' => ! empty($fila['id']) ? $paxPorId->get((int) $fila['id']) : null])
                @endforeach
            </div>

            <template data-pax-template>
                @include('programas.partials.pax-cliente-card', ['i' => '__INDEX__', 'fila' => [], 'paxModel' => null])
            </template>

            <div class="pax-form__actions">
                <button type="button" class="pax-form__add" data-pax-add data-max="{{ \App\Http\Controllers\DatosPasajerosController::MAX_PASAJEROS }}">
                    <i class="fa fa-user-plus" aria-hidden="true"></i> {{ $t['add'] }}
                </button>
                <button type="submit" class="pax-form__submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{ $t['save'] }}</button>
            </div>
        </form>
    </main>

    @include('layouts.partials.programa-show-footer', ['locale' => $locale])
</body>
</html>
