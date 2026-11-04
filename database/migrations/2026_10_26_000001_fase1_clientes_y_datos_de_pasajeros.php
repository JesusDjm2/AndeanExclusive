<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 1 de la gestión de programas:
 *  - clientes: titular del programa (viajero directo o agencia). Reemplaza a programas.email.
 *  - paxes: fecha de nacimiento (en vez de edad), país ISO (en vez de nacionalidad libre),
 *    dieta de una lista + alergias (en vez de alimentación libre), sexo, vencimiento del pasaporte,
 *    condiciones médicas y contacto de emergencia.
 */
return new class extends Migration
{
    /** Gentilicios usados hasta ahora → código ISO. */
    private const GENTILICIOS = [
        'peruana' => 'PE', 'peruano' => 'PE', 'perú' => 'PE', 'peru' => 'PE',
        'estadounidense' => 'US', 'americana' => 'US', 'americano' => 'US',
        'española' => 'ES', 'español' => 'ES', 'alemana' => 'DE', 'alemán' => 'DE',
        'francesa' => 'FR', 'francés' => 'FR', 'mexicana' => 'MX', 'mexicano' => 'MX',
        'argentina' => 'AR', 'argentino' => 'AR', 'chilena' => 'CL', 'chileno' => 'CL',
        'canadiense' => 'CA', 'británica' => 'GB', 'británico' => 'GB', 'inglesa' => 'GB',
        'italiana' => 'IT', 'italiano' => 'IT', 'brasileña' => 'BR', 'brasileño' => 'BR',
        'australiana' => 'AU', 'australiano' => 'AU', 'colombiana' => 'CO', 'colombiano' => 'CO',
    ];

    private const DIETAS = [
        'regular' => 'ninguna', 'normal' => 'ninguna', 'ninguna' => 'ninguna',
        'vegetariano' => 'vegetariana', 'vegetariana' => 'vegetariana',
        'vegano' => 'vegana', 'vegana' => 'vegana', 'sin gluten' => 'sin_gluten',
        'sin lactosa' => 'sin_lactosa', 'kosher' => 'kosher', 'halal' => 'halal',
    ];

    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20)->default('directo');   // directo | agencia
            $table->string('nombre');
            $table->string('empresa')->nullable();             // agencia / mayorista
            $table->string('email')->nullable()->index();
            $table->string('telefono', 40)->nullable();
            $table->char('pais', 2)->nullable();
            $table->string('idioma', 5)->nullable();
            $table->string('origen', 20)->nullable();          // web | whatsapp | recomendacion | ...
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->foreignId('cliente_id')->nullable()->after('estado')->constrained('clientes')->nullOnDelete();
        });

        // Un cliente por correo existente (nombre provisional: primer pasajero del programa).
        DB::table('programas')->whereNotNull('email')->where('email', '!=', '')->orderBy('id')->each(function ($programa) {
            $clienteId = DB::table('clientes')->where('email', $programa->email)->value('id');
            if (! $clienteId) {
                $nombre = DB::table('paxes')->where('programa_id', $programa->id)->orderBy('id')->value('nombre')
                    ?? ucfirst(strstr($programa->email, '@', true) ?: $programa->email);
                $clienteId = DB::table('clientes')->insertGetId([
                    'tipo' => 'directo', 'nombre' => $nombre, 'email' => $programa->email,
                    'idioma' => $programa->lang, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('programas')->where('id', $programa->id)->update(['cliente_id' => $clienteId]);
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('paxes', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('nombre');
            $table->char('sexo', 1)->nullable()->after('fecha_nacimiento');
            $table->char('pais', 2)->nullable()->after('sexo');
            $table->date('pasaporte_vencimiento')->nullable()->after('pasaporte');
            $table->string('dieta', 20)->nullable()->after('pasaporte_archivo');
            $table->string('alergias')->nullable()->after('dieta');
            $table->text('condiciones_medicas')->nullable()->after('alergias');
            $table->string('emergencia_nombre')->nullable()->after('condiciones_medicas');
            $table->string('emergencia_telefono', 40)->nullable()->after('emergencia_nombre');
        });

        $inicios = DB::table('programas')->pluck('inicio', 'id');
        DB::table('paxes')->orderBy('id')->each(function ($pax) use ($inicios) {
            $referencia = isset($inicios[$pax->programa_id]) && $inicios[$pax->programa_id]
                ? Carbon::parse($inicios[$pax->programa_id]) : Carbon::today();
            $alimentacion = trim((string) $pax->alimentacion);
            $dieta = self::DIETAS[mb_strtolower($alimentacion)] ?? ($alimentacion !== '' ? 'otra' : null);

            DB::table('paxes')->where('id', $pax->id)->update([
                // Misma edad a la fecha del viaje (un mes antes para no caer justo en el cumpleaños).
                'fecha_nacimiento' => $pax->edad !== null ? $referencia->copy()->subYears((int) $pax->edad)->subMonth()->toDateString() : null,
                'pais' => self::GENTILICIOS[mb_strtolower(trim((string) $pax->nacionalidad))] ?? null,
                'dieta' => $dieta,
                'alergias' => $dieta === 'otra' ? $alimentacion : null,
            ]);
        });

        Schema::table('paxes', function (Blueprint $table) {
            $table->dropColumn(['edad', 'nacionalidad', 'alimentacion']);
        });
    }

    public function down(): void
    {
        Schema::table('paxes', function (Blueprint $table) {
            $table->integer('edad')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('alimentacion')->nullable();
        });
        DB::table('paxes')->orderBy('id')->each(function ($pax) {
            DB::table('paxes')->where('id', $pax->id)->update([
                'edad' => $pax->fecha_nacimiento ? Carbon::parse($pax->fecha_nacimiento)->age : null,
                'nacionalidad' => $pax->pais,
                'alimentacion' => $pax->alergias ?: $pax->dieta,
            ]);
        });
        Schema::table('paxes', function (Blueprint $table) {
            $table->dropColumn(['fecha_nacimiento', 'sexo', 'pais', 'pasaporte_vencimiento', 'dieta', 'alergias',
                'condiciones_medicas', 'emergencia_nombre', 'emergencia_telefono']);
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->string('email')->nullable();
        });
        DB::table('programas')->whereNotNull('cliente_id')->orderBy('id')->each(function ($programa) {
            DB::table('programas')->where('id', $programa->id)
                ->update(['email' => DB::table('clientes')->where('id', $programa->cliente_id)->value('email')]);
        });
        Schema::table('programas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cliente_id');
        });
        Schema::dropIfExists('clientes');
    }
};
