<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Reestructura de programas (los datos actuales son de prueba):
 *  - programas.token: enlace secreto del itinerario del cliente (reemplaza el ID secuencial en la URL).
 *  - programas.estado: estado comercial (cotizacion | confirmado | cancelado).
 *  - paxes.pasaporte_archivo: el escaneo va en su propia columna; paxes.pasaporte queda para el número.
 *  - pagos: depósitos y saldos de cada programa.
 *  - anio_id / mes_id se recalculan desde la fecha de inicio (el modelo los mantiene en adelante).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('token', 40)->nullable()->unique()->after('codigo');
            $table->string('estado', 20)->default('cotizacion')->after('token')->index();
        });

        DB::table('programas')->whereNull('token')->orderBy('id')->each(function ($programa) {
            DB::table('programas')->where('id', $programa->id)->update(['token' => Str::random(32)]);
        });

        Schema::table('paxes', function (Blueprint $table) {
            $table->string('pasaporte_archivo')->nullable()->after('pasaporte');
        });

        // Lo que parecía una ruta de archivo pasa a pasaporte_archivo; el resto se queda como número.
        DB::table('paxes')->where('pasaporte', 'like', '%/%')->update([
            'pasaporte_archivo' => DB::raw('pasaporte'),
            'pasaporte' => null,
        ]);

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained()->cascadeOnDelete();
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->string('tipo', 20)->default('deposito'); // deposito | saldo | otro
            $table->string('metodo', 30)->nullable();       // transferencia | tarjeta | efectivo | paypal | otro
            $table->string('referencia')->nullable();
            $table->string('nota')->nullable();
            $table->timestamps();
        });

        // Año y mes desde la fecha de inicio.
        $meses = DB::table('meses')->pluck('id', 'numero');
        DB::table('programas')->whereNotNull('inicio')->orderBy('id')->each(function ($programa) use ($meses) {
            $inicio = \Carbon\Carbon::parse($programa->inicio);
            $anioId = DB::table('anios')->where('anio', $inicio->year)->value('id')
                ?? DB::table('anios')->insertGetId(['anio' => $inicio->year, 'created_at' => now(), 'updated_at' => now()]);
            DB::table('programas')->where('id', $programa->id)->update([
                'anio_id' => $anioId,
                'mes_id' => $meses[$inicio->month] ?? null,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');

        DB::table('paxes')->whereNotNull('pasaporte_archivo')->update(['pasaporte' => DB::raw('pasaporte_archivo')]);
        Schema::table('paxes', function (Blueprint $table) {
            $table->dropColumn('pasaporte_archivo');
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->dropUnique(['token']);
            $table->dropIndex(['estado']);
            $table->dropColumn(['token', 'estado']);
        });
    }
};
