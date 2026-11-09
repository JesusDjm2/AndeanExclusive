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
        if (Schema::hasTable('tarifas')) {
            Schema::table('tarifas', function (Blueprint $table) {
                if (! Schema::hasColumn('tarifas', 'es_especial')) {
                    $table->boolean('es_especial')->default(false)->after('es_fija');
                }
                if (! Schema::hasColumn('tarifas', 'nombre_especial')) {
                    $table->string('nombre_especial', 100)->nullable()->after('es_especial');
                }
            });
        }

        if (Schema::hasTable('programa_dias')) {
            Schema::table('programa_dias', function (Blueprint $table) {
                if (! Schema::hasColumn('programa_dias', 'es_libre')) {
                    $table->boolean('es_libre')->default(false)->after('descripcion');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tarifas')) {
            Schema::table('tarifas', function (Blueprint $table) {
                if (Schema::hasColumn('tarifas', 'nombre_especial')) {
                    $table->dropColumn('nombre_especial');
                }
                if (Schema::hasColumn('tarifas', 'es_especial')) {
                    $table->dropColumn('es_especial');
                }
            });
        }

        if (Schema::hasTable('programa_dias')) {
            Schema::table('programa_dias', function (Blueprint $table) {
                if (Schema::hasColumn('programa_dias', 'es_libre')) {
                    $table->dropColumn('es_libre');
                }
            });
        }
    }
};
