<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Avisos a un área. Lo visto se guarda por persona: que uno lo lea no lo marca como leído a sus compañeros.
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->string('area', 40);
            $table->string('tipo', 40);
            $table->boolean('requiere_accion')->default(true);
            $table->string('titulo');
            $table->string('detalle', 500)->nullable();
            // Destino interno (nombre de ruta, nunca una URL guardada): funciona igual en cualquier dominio y no permite redirigir afuera.
            $table->string('accion', 60);
            $table->string('ruta', 80);
            $table->json('ruta_params')->nullable();
            $table->string('ancla', 60)->nullable();
            // Misma clave sin resolver = el mismo asunto: se actualiza el aviso en vez de repetirlo.
            $table->string('clave', 80)->nullable();
            $table->unsignedInteger('veces')->default(1);
            $table->unsignedBigInteger('programa_id')->nullable();
            $table->string('programa_codigo', 40)->nullable();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_nombre')->nullable();
            $table->timestamp('resuelto_at')->nullable();
            $table->timestamps();

            $table->index(['area', 'resuelto_at', 'updated_at']);
            $table->index(['clave', 'area', 'resuelto_at']);
            $table->index('programa_id');
        });

        Schema::create('aviso_lecturas', function (Blueprint $table) {
            $table->foreignId('aviso_id')->constrained('avisos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('leido_at');

            $table->primary(['aviso_id', 'user_id']);
            $table->index('user_id');
        });

        // Un pago no se borra: se anula con motivo y conserva monto, referencia y comprobante.
        foreach (['pagos', 'proveedor_pagos'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->softDeletes();
                $table->foreignId('anulado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->string('motivo_anulacion')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['pagos', 'proveedor_pagos'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropConstrainedForeignId('anulado_por');
                $table->dropColumn(['deleted_at', 'motivo_anulacion']);
            });
        }
        Schema::dropIfExists('aviso_lecturas');
        Schema::dropIfExists('avisos');
    }
};
