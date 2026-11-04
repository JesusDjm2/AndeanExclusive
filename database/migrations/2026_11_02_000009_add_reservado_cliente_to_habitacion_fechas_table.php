<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('habitacion_fechas') && ! Schema::hasColumn('habitacion_fechas', 'reservado_cliente')) {
            Schema::table('habitacion_fechas', function (Blueprint $table) {
                $table->boolean('reservado_cliente')->default(false)->after('cantidad');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('habitacion_fechas') && Schema::hasColumn('habitacion_fechas', 'reservado_cliente')) {
            Schema::table('habitacion_fechas', function (Blueprint $table) {
                $table->dropColumn('reservado_cliente');
            });
        }
    }
};
