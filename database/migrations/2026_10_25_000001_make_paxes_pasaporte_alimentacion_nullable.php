<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * El formulario de programas y su validación tratan pasaporte y alimentación como opcionales,
 * pero las columnas eran NOT NULL: cualquier pasajero sin esos datos hacía fallar el guardado.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paxes', function (Blueprint $table) {
            $table->string('pasaporte')->nullable()->change();
            $table->string('alimentacion')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('paxes', function (Blueprint $table) {
            $table->string('pasaporte')->nullable(false)->change();
            $table->string('alimentacion')->nullable(false)->change();
        });
    }
};
