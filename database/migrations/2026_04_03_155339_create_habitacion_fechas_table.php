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
        Schema::create('habitacion_fechas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('habitacion_id')->constrained('habitacions')->cascadeOnDelete();
            $table->date('fecha_ingreso');
            $table->date('fecha_salida');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitacion_fechas');
    }
};
