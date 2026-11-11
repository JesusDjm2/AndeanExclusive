<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mensajes_proveedor', function (Blueprint $table) {
            if (!Schema::hasColumn('mensajes_proveedor', 'cc')) {
                $table->text('cc')->nullable()->after('para');
            }
            if (!Schema::hasColumn('mensajes_proveedor', 'adjunto_nombre')) {
                $table->string('adjunto_nombre')->nullable()->after('con_orden');
            }
            if (!Schema::hasColumn('mensajes_proveedor', 'adjunto_ruta')) {
                $table->string('adjunto_ruta')->nullable()->after('adjunto_nombre');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mensajes_proveedor', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('mensajes_proveedor', 'cc')) {
                $columns[] = 'cc';
            }
            if (Schema::hasColumn('mensajes_proveedor', 'adjunto_nombre')) {
                $columns[] = 'adjunto_nombre';
            }
            if (Schema::hasColumn('mensajes_proveedor', 'adjunto_ruta')) {
                $columns[] = 'adjunto_ruta';
            }
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
