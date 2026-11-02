<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Los formularios ya los tratan como opcionales: sin esto, dejarlos vacíos terminaba en un error del servidor.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedors', fn (Blueprint $table) => $table->string('ruc')->nullable()->change());
        Schema::table('tours', fn (Blueprint $table) => $table->string('mapa')->nullable()->change());
        Schema::table('estours', fn (Blueprint $table) => $table->string('mapa')->nullable()->change());
    }

    public function down(): void
    {
        Schema::table('proveedors', fn (Blueprint $table) => $table->string('ruc')->default('')->change());
        Schema::table('tours', fn (Blueprint $table) => $table->string('mapa')->default('')->change());
        Schema::table('estours', fn (Blueprint $table) => $table->string('mapa')->default('')->change());
    }
};
