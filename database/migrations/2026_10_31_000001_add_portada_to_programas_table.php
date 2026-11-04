<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Imagen de portada del programa (ruta dentro de public/); vacío = la portada por defecto. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programas', fn (Blueprint $table) => $table->string('portada')->nullable()->after('presentacion'));
    }

    public function down(): void
    {
        Schema::table('programas', fn (Blueprint $table) => $table->dropColumn('portada'));
    }
};
