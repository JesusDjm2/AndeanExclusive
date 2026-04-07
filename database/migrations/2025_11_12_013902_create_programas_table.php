<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();            
            $table->string('email')->nullable();
            $table->date('inicio');
            $table->date('fin');
            $table->string('lang')->nullable();
            $table->string('precioAdulto')->nullable();
            $table->string('precioChild')->nullable();
            $table->text('presentacion')->nullable();
            $table->foreignId('agente_id')->nullable()->constrained('agentes') ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programas');
    }
};
