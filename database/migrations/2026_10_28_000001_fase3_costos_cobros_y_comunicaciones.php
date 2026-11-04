<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 3:
 *  - servicios: costo (en USD o PEN, por grupo o por persona) y pago al proveedor (cuentas por pagar).
 *  - programas: tipo de cambio, suplemento individual y fecha de confirmación (plazo del depósito: 7 días).
 *  - paxes: cuándo los actualizó el propio cliente desde su enlace.
 *  - notificaciones: correos automáticos enviados (recordatorios de pago, datos de pasajeros, encuesta).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (! Schema::hasColumn('servicios', 'costo')) {
                $table->decimal('costo', 10, 2)->nullable();
                $table->char('moneda', 3)->default('USD');
                $table->string('costo_por', 8)->default('grupo');
                $table->date('pagado_at')->nullable();
                $table->string('pago_referencia')->nullable();
            }
        });

        Schema::table('programas', function (Blueprint $table) {
            if (! Schema::hasColumn('programas', 'porcentaje_ganancia')) {
                $table->decimal('porcentaje_ganancia', 8, 2)->nullable();
            }
            if (! Schema::hasColumn('programas', 'tipo_cambio')) {
                $table->decimal('tipo_cambio', 6, 3)->nullable();
                $table->decimal('suplemento_individual', 10, 2)->default(0);
                $table->unsignedTinyInteger('habitaciones_individuales')->default(0);
                $table->timestamp('confirmado_at')->nullable();
            }
        });
        DB::table('programas')->where('estado', 'confirmado')->update(['confirmado_at' => DB::raw('updated_at')]);

        Schema::table('paxes', function (Blueprint $table) {
            if (! Schema::hasColumn('paxes', 'actualizado_por_cliente_at')) {
                $table->timestamp('actualizado_por_cliente_at')->nullable();
            }
        });

        if (! Schema::hasTable('notificaciones')) {
            Schema::create('notificaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
                $table->string('tipo', 30);
                $table->string('email');
                $table->boolean('automatica')->default(true);
                $table->foreignId('enviada_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['programa_id', 'tipo']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');

        Schema::table('paxes', function (Blueprint $table) {
            $table->dropColumn('actualizado_por_cliente_at');
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn(['confirmado_at', 'habitaciones_individuales', 'suplemento_individual', 'tipo_cambio']);
        });

        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['pago_referencia', 'pagado_at', 'costo_por', 'moneda', 'costo']);
        });
    }
};
