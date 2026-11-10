<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tarifario_servicios')) {
            Schema::create('tarifario_servicios', function (Blueprint $table) {
                $table->id();
                $table->string('codigo', 50)->unique();
                $table->string('categoria', 30)->index(); // traslado, tour, trek, boleto, alimentacion, viatico, propina, otro
                $table->string('nombre', 255);
                $table->text('descripcion')->nullable();
                $table->text('lugares_visita')->nullable();
                $table->decimal('duracion_horas', 4, 1)->nullable();
                $table->string('tipo_calculo', 20)->default('escala_pax'); // escala_pax, por_persona, por_grupo
                $table->decimal('precio_adulto', 10, 2)->nullable();
                $table->decimal('precio_nino', 10, 2)->nullable();
                $table->decimal('costo_grupo', 10, 2)->nullable();
                $table->decimal('comision_pct', 5, 2)->default(11.00);
                $table->boolean('aplica_igv_comision')->default(true);
                $table->char('moneda', 3)->default('USD');
                $table->json('precios_escalonados')->nullable(); // {'normal': {'1': 152.78, ...}, 'feriado': {'1': 217.78, ...}}
                $table->json('desglose')->nullable(); // {'transporte': 60, 'guia': 65, 'snack': 5.3, ...}
                $table->text('notas')->nullable();
                $table->boolean('activo')->default(true)->index();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('servicios')) {
            Schema::table('servicios', function (Blueprint $table) {
                if (! Schema::hasColumn('servicios', 'tarifario_codigo')) {
                    $table->string('tarifario_codigo', 50)->nullable()->after('tipo')->index();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('servicios')) {
            Schema::table('servicios', function (Blueprint $table) {
                if (Schema::hasColumn('servicios', 'tarifario_codigo')) {
                    $table->dropColumn('tarifario_codigo');
                }
            });
        }

        Schema::dropIfExists('tarifario_servicios');
    }
};
