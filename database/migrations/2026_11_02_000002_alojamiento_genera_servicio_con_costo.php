<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cada estadía genera su servicio: así el hotel suma al margen, se reserva y se paga como cualquier proveedor.
 * Los programas antiguos no cambian: las estadías que ya existen no se enlazan solas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habitacion_fechas', function (Blueprint $table) {
            $table->decimal('costo_noche', 10, 2)->nullable()->after('cantidad');
            $table->string('moneda', 3)->default('USD')->after('costo_noche');
            $table->foreignId('servicio_id')->nullable()->after('moneda')->constrained('servicios')->nullOnDelete();
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->foreignId('proveedor_id')->nullable()->after('id')->constrained('proveedors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hotels', fn (Blueprint $table) => $table->dropConstrainedForeignId('proveedor_id'));

        Schema::table('habitacion_fechas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('servicio_id');
            $table->dropColumn(['costo_noche', 'moneda']);
        });
    }
};
