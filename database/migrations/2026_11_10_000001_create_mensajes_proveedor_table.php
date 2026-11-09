<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mensajería de Reservas con los proveedores: lo que se les envía desde reservas@ (solicitudes, confirmaciones,
 * cambios, cancelaciones) y las respuestas que Reservas registra, para que cada servicio tenga su hilo.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mensajes_proveedor')) {
            return;
        }

        Schema::create('mensajes_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedors')->cascadeOnDelete();
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->foreignId('programa_id')->nullable()->constrained('programas')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('direccion', 10);        // enviado | recibido
            $table->string('tipo', 20);             // solicitud | confirmacion | modificacion | cancelacion | consulta | respuesta
            $table->string('para')->nullable();
            $table->string('asunto')->nullable();
            $table->text('cuerpo');
            $table->boolean('con_orden')->default(false);
            $table->string('resultado', 20)->nullable(); // en respuestas: confirmado | sin_disponibilidad | otro
            $table->string('error')->nullable();         // si el envío falló
            $table->timestamps();

            $table->index(['servicio_id', 'created_at']);
            $table->index(['proveedor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes_proveedor');
    }
};
