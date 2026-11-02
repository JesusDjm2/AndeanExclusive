<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('consultas')) {
            Schema::create('consultas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('email')->nullable();
                $table->string('telefono', 40)->nullable();
                $table->string('idioma', 2)->default('en');
                $table->string('origen', 30)->default('web');
                $table->string('tour')->nullable();
                $table->date('fecha_inicio')->nullable();
                $table->date('fecha_fin')->nullable();
                $table->unsignedSmallInteger('adultos')->nullable();
                $table->unsignedSmallInteger('ninos')->nullable();
                $table->text('mensaje')->nullable();
                $table->string('estado', 20)->default('nueva')->index();
                $table->string('motivo_perdida')->nullable();
                $table->text('notas')->nullable();
                $table->foreignId('asignado_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
                $table->foreignId('programa_id')->nullable()->constrained('programas')->nullOnDelete();
                $table->timestamp('atendida_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
