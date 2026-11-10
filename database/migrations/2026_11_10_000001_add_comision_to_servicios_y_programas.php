<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Comisión por servicio (como la hoja del Tarifario Aracari: neto + comisión + IGV de la comisión).
 *  - servicios.comision_pct: la trae el Tarifario Maestro (11 %, 13 %, 0 % en propinas); vacío = la del programa.
 *  - servicios.igv_comision: si a esa comisión se le suma IGV (las propinas no).
 *  - programas.comision_defecto: comisión de los servicios sin comisión propia y de los hoteles.
 * Los programas que ya existen quedan con comisión por defecto 0 para que su precio de venta no cambie solo.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (! Schema::hasColumn('servicios', 'comision_pct')) {
                $table->decimal('comision_pct', 5, 2)->nullable()->after('tarifario_codigo');
            }
            if (! Schema::hasColumn('servicios', 'igv_comision')) {
                $table->boolean('igv_comision')->default(true)->after('comision_pct');
            }
        });

        Schema::table('programas', function (Blueprint $table) {
            if (! Schema::hasColumn('programas', 'comision_defecto')) {
                $table->decimal('comision_defecto', 5, 2)->nullable()->default(11.00)->after('porcentaje_igv');
            }
        });

        DB::table('programas')->update(['comision_defecto' => 0]);
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            foreach (['igv_comision', 'comision_pct'] as $columna) {
                if (Schema::hasColumn('servicios', $columna)) {
                    $table->dropColumn($columna);
                }
            }
        });

        Schema::table('programas', function (Blueprint $table) {
            if (Schema::hasColumn('programas', 'comision_defecto')) {
                $table->dropColumn('comision_defecto');
            }
        });
    }
};
