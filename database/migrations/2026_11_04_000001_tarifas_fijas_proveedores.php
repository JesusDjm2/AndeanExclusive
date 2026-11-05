<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tarifas')) {
            try {
                DB::statement('ALTER TABLE `tarifas` MODIFY `desde` DATE NULL, MODIFY `hasta` DATE NULL');
            } catch (\Throwable $e) {
                Schema::table('tarifas', function (Blueprint $table) {
                    $table->date('desde')->nullable()->change();
                    $table->date('hasta')->nullable()->change();
                });
            }

            Schema::table('tarifas', function (Blueprint $table) {
                if (! Schema::hasColumn('tarifas', 'es_fija')) {
                    $table->boolean('es_fija')->default(false)->after('hasta');
                }
                if (! Schema::hasColumn('tarifas', 'tipo_servicio')) {
                    $table->string('tipo_servicio', 50)->nullable()->after('concepto');
                }
                if (! Schema::hasColumn('tarifas', 'unidad_detalle')) {
                    $table->string('unidad_detalle', 100)->nullable()->after('por');
                }
            });

            // Mark any existing rates with null dates or wide coverage as fixed if applicable
            DB::statement('UPDATE `tarifas` SET `es_fija` = 1 WHERE `desde` IS NULL AND `hasta` IS NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tarifas')) {
            Schema::table('tarifas', function (Blueprint $table) {
                if (Schema::hasColumn('tarifas', 'es_fija')) {
                    $table->dropColumn('es_fija');
                }
                if (Schema::hasColumn('tarifas', 'tipo_servicio')) {
                    $table->dropColumn('tipo_servicio');
                }
                if (Schema::hasColumn('tarifas', 'unidad_detalle')) {
                    $table->dropColumn('unidad_detalle');
                }
            });
        }
    }
};
