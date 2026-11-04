<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Alojamiento de un programa: cada fila de habitacion_fechas es "N habitaciones de este tipo, de tal a tal fecha".
 * Un mismo tipo puede repetirse (Cusco → Machu Picchu → Cusco) y las fechas pueden faltar en una cotización.
 * Pasa a ser la única fuente: la tabla habitacion_programa deja de usarse (no se borra, por si hiciera falta revisarla).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habitacion_fechas', function (Blueprint $table) {
            $table->unsignedSmallInteger('cantidad')->default(1)->after('habitacion_id');
            $table->date('fecha_ingreso')->nullable()->change();
            $table->date('fecha_salida')->nullable()->change();
        });

        // Habitaciones elegidas que aún no tenían fechas: solo existían en la tabla anterior.
        $sinFechas = DB::table('habitacion_programa as hp')
            ->leftJoin('habitacion_fechas as hf', fn ($j) => $j->on('hf.programa_id', '=', 'hp.programa_id')->on('hf.habitacion_id', '=', 'hp.habitacion_id'))
            ->whereNull('hf.id')
            ->distinct()
            ->get(['hp.programa_id', 'hp.habitacion_id']);

        foreach ($sinFechas as $fila) {
            DB::table('habitacion_fechas')->insert([
                'programa_id' => $fila->programa_id,
                'habitacion_id' => $fila->habitacion_id,
                'cantidad' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('habitacion_fechas')->whereNull('fecha_ingreso')->orWhereNull('fecha_salida')->delete();

        Schema::table('habitacion_fechas', function (Blueprint $table) {
            $table->dropColumn('cantidad');
            $table->date('fecha_ingreso')->nullable(false)->change();
            $table->date('fecha_salida')->nullable(false)->change();
        });
    }
};
